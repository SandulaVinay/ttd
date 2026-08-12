<?php

namespace App\Services;

use App\Models\AssetPrice;
use App\Models\PortfolioAsset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MarketPriceService
{
    /**
     * Fetch and update prices for all portfolio assets.
     */
    public function updateAllPrices(): array
    {
        $assets = PortfolioAsset::all();
        $results = [];

        $cryptoAssets = $assets->where('asset_type', 'crypto');
        $stockAssets = $assets->where('asset_type', 'stock_nse');

        // 1. Fetch Cryptos via CoinGecko API
        if ($cryptoAssets->count() > 0) {
            $cryptoIds = $cryptoAssets->pluck('api_identifier')->filter()->implode(',');
            if ($cryptoIds) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
                    ])->timeout(10)->get("https://api.coingecko.com/api/v3/simple/price", [
                        'ids' => $cryptoIds,
                        'vs_currencies' => 'inr',
                        'include_24hr_change' => 'true'
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        foreach ($cryptoAssets as $asset) {
                            $id = $asset->api_identifier;
                            if (isset($data[$id]['inr'])) {
                                $price = $data[$id]['inr'];
                                $change = $data[$id]['inr_24h_change'] ?? 0;
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
                } catch (\Exception $e) {
                    Log::error("CoinGecko API price fetch error: " . $e->getMessage());
                }
            }
        }

        // 2. Fetch NSE Stocks via Yahoo Finance API
        foreach ($stockAssets as $asset) {
            $symbol = $asset->symbol;
            $yahooSymbol = str_contains($symbol, '.') ? $symbol : "{$symbol}.NS";

            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
                ])->timeout(10)->get("https://query1.finance.yahoo.com/v8/finance/chart/{$yahooSymbol}");

                if ($response->successful()) {
                    $meta = $response->json('chart.result.0.meta');
                    if (isset($meta['regularMarketPrice'])) {
                        $price = $meta['regularMarketPrice'];
                        $prevClose = $meta['chartPreviousClose'] ?? $meta['previousClose'] ?? $price;
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
                    }
                }
            } catch (\Exception $e) {
                Log::error("Yahoo Finance API price fetch error for {$symbol}: " . $e->getMessage());
            }
        }

        return $results;
    }
}
