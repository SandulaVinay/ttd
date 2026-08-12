<?php

namespace Database\Seeders;

use App\Models\AssetPrice;
use App\Models\FundBalance;
use App\Models\Investor;
use App\Models\InvestorContribution;
use App\Models\PortfolioAsset;
use Illuminate\Database\Seeder;

class GrowthCircleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Available Cash Fund Balance (From P&L Sheet: ₹106,557.33)
        FundBalance::updateOrCreate(
            ['id' => 1],
            ['available_cash' => 106557.33, 'notes' => 'Available liquid fund balance from P&L Sheet']
        );

        // 2. Create Investors / Partners
        $partners = [
            ['name' => 'Balaji', 'type' => 'partner'],
            ['name' => 'Nikhil', 'type' => 'partner'],
            ['name' => 'Vinay', 'type' => 'partner'],
            ['name' => 'Tharun', 'type' => 'partner'],
            ['name' => 'Gowtham', 'type' => 'partner'],
            ['name' => 'Business', 'type' => 'business'],
        ];

        $investorModels = [];
        foreach ($partners as $p) {
            $investorModels[$p['name']] = Investor::updateOrCreate(
                ['name' => $p['name']],
                ['type' => $p['type']]
            );
        }

        // 3. Monthly Contributions Grid (From Sheet 1: Investment - Nov 2024 to Aug 2025)
        $contributionsData = [
            'November 2024' => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 4850],
            'December 2024' => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 12700],
            'January 2025'  => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 3800],
            'February 2025' => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 1950],
            'March 2025'    => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 6850],
            'April 2025'    => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 8900],
            'May 2025'      => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 10000],
            'June 2025'     => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 6500],
            'July 2025'     => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 0],
            'August 2025'   => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 0],
        ];

        foreach ($contributionsData as $month => $partnerAmounts) {
            $parts = explode(' ', $month);
            $monthName = $parts[0];
            $year = isset($parts[1]) ? (int)$parts[1] : 2025;

            foreach ($partnerAmounts as $name => $amount) {
                if (isset($investorModels[$name])) {
                    InvestorContribution::updateOrCreate(
                        [
                            'investor_id' => $investorModels[$name]->id,
                            'month'       => $month,
                        ],
                        [
                            'year'        => $year,
                            'amount'      => $amount,
                        ]
                    );
                }
            }
        }

        // Update total contributed per investor
        foreach ($investorModels as $model) {
            $total = InvestorContribution::where('investor_id', $model->id)->sum('amount');
            $model->update(['total_contributed' => $total]);
        }

        // 4. Create Stock & Crypto Assets from Sheet 2 (Assets) & Sheet 4 (Formulas)
        $assets = [
            [
                'symbol' => 'DOGE',
                'name' => 'Dogecoin',
                'asset_type' => 'crypto',
                'quantity' => 179.3412,
                'buy_price' => 13.7777,
                'buy_sell_charges' => 26.24,
                'investment_amount' => 2500.15,
                'api_identifier' => 'dogecoin',
                'initial_price' => 6.832703955,
            ],
            [
                'symbol' => 'TATAPOWER',
                'name' => 'Tata Power Co Ltd',
                'asset_type' => 'stock_nse',
                'quantity' => 5.0,
                'buy_price' => 377.25,
                'buy_sell_charges' => 3.00,
                'investment_amount' => 1889.25,
                'api_identifier' => 'TATAPOWER.NS',
                'initial_price' => 374.85,
            ],
            [
                'symbol' => 'ADANIGREEN',
                'name' => 'Adani Green Energy Ltd',
                'asset_type' => 'stock_nse',
                'quantity' => 3.0,
                'buy_price' => 1008.00,
                'buy_sell_charges' => 3.00,
                'investment_amount' => 3027.00,
                'api_identifier' => 'ADANIGREEN.NS',
                'initial_price' => 1322.60,
            ],
            [
                'symbol' => 'VET',
                'name' => 'VeChain (VET)',
                'asset_type' => 'crypto',
                'quantity' => 34311.6979,
                'buy_price' => 0.96,
                'buy_sell_charges' => 385.77,
                'investment_amount' => 33328.00,
                'api_identifier' => 'vechain',
                'initial_price' => 0.4418691805,
            ],
            [
                'symbol' => 'SOL',
                'name' => 'Solana (SOL)',
                'asset_type' => 'crypto',
                'quantity' => 0.99,
                'buy_price' => 8123.99,
                'buy_sell_charges' => 124.28,
                'investment_amount' => 8248.27,
                'api_identifier' => 'solana',
                'initial_price' => 7250.623845,
            ],
        ];

        // Remove sold assets like SOUTHBANK from active holdings
        PortfolioAsset::where('symbol', 'SOUTHBANK')->delete();
        AssetPrice::where('symbol', 'SOUTHBANK')->delete();

        foreach ($assets as $assetData) {
            $initialPrice = $assetData['initial_price'];
            unset($assetData['initial_price']);

            PortfolioAsset::updateOrCreate(
                ['symbol' => $assetData['symbol']],
                $assetData
            );

            AssetPrice::updateOrCreate(
                ['symbol' => $assetData['symbol']],
                [
                    'current_price_inr' => $initialPrice,
                    'change_24h' => 0,
                    'last_updated_at' => now(),
                ]
            );
        }
    }
}
