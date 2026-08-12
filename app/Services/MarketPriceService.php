<?php

namespace App\Services;

use App\Models\AssetPrice;
use App\Models\PortfolioAsset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MarketPriceService
{
    /**
     * Fetch and update live market prices for all portfolio assets (Crypto & NSE Stocks).
     */
    public function updateAllPrices(): array
    {
        $assets = PortfolioAsset::all();
        $results = [];

        $cryptoAssets = $assets->where('asset_type', 'crypto');
        $stockAssets = $assets->where('asset_type', 'stock_nse');

        // 1. Fetch Cryptos (CoinGecko Primary + WazirX Fallback)
        if ($cryptoAssets->count() > 0) {
            $cryptoMap = [
                'DOGE' => ['cg' => 'dogecoin', 'wazirx' => 'dogeinr'],
                'VET'  => ['cg' => 'vechain',  'wazirx' => 'vetinr'],
                'SOL'  => ['cg' => 'solana',   'wazirx' => 'solinr'],
            ];

            // 1A. CoinGecko API
            try {
                $cgIds = [];
                foreach ($cryptoAssets as $asset) {
                    $sym = strtoupper($asset->symbol);
                    $cgIds[] = $cryptoMap[$sym]['cg'] ?? strtolower($asset->api_identifier ?? $asset->name);
                }
                $cgIdsStr = implode(',', array_filter(array_unique($cgIds)));

                if ($cgIdsStr) {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
                    ])->timeout(8)->get("https://api.coingecko.com/api/v3/simple/price", [
                        'ids' => $cgIdsStr,
                        'vs_currencies' => 'inr',
                        'include_24hr_change' => 'true'
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        foreach ($cryptoAssets as $asset) {
                            $sym = strtoupper($asset->symbol);
                            $cgId = $cryptoMap[$sym]['cg'] ?? strtolower($asset->api_identifier ?? $asset->name);

                            if (isset($data[$cgId]['inr'])) {
                                $price = (float)$data[$cgId]['inr'];
                                $change = (float)($data[$cgId]['inr_24h_change'] ?? 0);

                                AssetPrice::updateOrCreate(
                                    ['symbol' => $asset->symbol],
                                    [
                                        'current_price_inr' => $price,
                                        'change_24h' => $change,
                                        'last_updated_at' => now(),
                                    ]
                                );
                                $results[$asset->symbol] = $price;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("CoinGecko API fetch failed: " . $e->getMessage());
            }

            // 1B. WazirX Fallback for missing cryptos
            $missingCryptos = $cryptoAssets->filter(fn($a) => !isset($results[$a->symbol]));
            if ($missingCryptos->count() > 0) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ])->timeout(8)->get("https://api.wazirx.com/sapi/v1/tickers/24hr");

                    if ($response->successful()) {
                        $tickers = collect($response->json())->keyBy('symbol');
                        foreach ($missingCryptos as $asset) {
                            $sym = strtoupper($asset->symbol);
                            $wazirxSymbol = $cryptoMap[$sym]['wazirx'] ?? strtolower($asset->symbol) . 'inr';

                            if ($tickers->has($wazirxSymbol)) {
                                $item = $tickers->get($wazirxSymbol);
                                $price = (float)($item['lastPrice'] ?? $item['openPrice'] ?? 0);
                                $change = (float)($item['priceChangePercent'] ?? 0);

                                if ($price > 0) {
                                    AssetPrice::updateOrCreate(
                                        ['symbol' => $asset->symbol],
                                        [
                                            'current_price_inr' => $price,
                                            'change_24h' => $change,
                                            'last_updated_at' => now(),
                                        ]
                                    );
                                    $results[$asset->symbol] = $price;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("WazirX API fallback fetch failed: " . $e->getMessage());
                }
            }
        }

        // 2. Fetch NSE Stocks via Yahoo Finance API (v8 query1 & query2)
        foreach ($stockAssets as $asset) {
            $symbol = strtoupper($asset->symbol);
            $yahooSymbol = str_contains($symbol, '.') ? $symbol : "{$symbol}.NS";

            $fetched = false;
            $hosts = ['query1.finance.yahoo.com', 'query2.finance.yahoo.com'];

            foreach ($hosts as $host) {
                if ($fetched) break;

                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                        'Accept' => 'application/json'
                    ])->timeout(8)->get("https://{$host}/v8/finance/chart/{$yahooSymbol}?interval=1d&range=1d");

                    if ($response->successful()) {
                        $meta = $response->json('chart.result.0.meta');
                        if (isset($meta['regularMarketPrice']) && $meta['regularMarketPrice'] > 0) {
                            $price = (float)$meta['regularMarketPrice'];
                            $prevClose = (float)($meta['chartPreviousClose'] ?? $meta['previousClose'] ?? $meta['regularMarketPreviousClose'] ?? $price);
                            $change = $prevClose > 0 ? (($price - $prevClose) / $prevClose) * 100 : 0;

                            AssetPrice::updateOrCreate(
                                ['symbol' => $asset->symbol],
                                [
                                    'current_price_inr' => $price,
                                    'change_24h' => $change,
                                    'last_updated_at' => now(),
                                ]
                            );
                            $results[$asset->symbol] = $price;
                            $fetched = true;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Yahoo Finance API ({$host}) error for {$yahooSymbol}: " . $e->getMessage());
                }
            }
        }

        return $results;
    }
}
