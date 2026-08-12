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
        // 1. Create Available Cash Fund Balance
        FundBalance::updateOrCreate(
            ['id' => 1],
            ['available_cash' => 66557.00, 'notes' => 'Available liquid fund balance']
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

        // 3. Monthly Contributions Grid (from Excel)
        $contributionsData = [
            'November 2025' => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 4850],
            'December 2025' => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 12700],
            'January'       => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 3800],
            'February'      => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 1950],
            'March'         => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 6850],
            'April'         => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 8900],
            'May'           => ['Balaji' => 2000, 'Nikhil' => 2000, 'Vinay' => 2000, 'Tharun' => 2000, 'Gowtham' => 2000, 'Business' => 6500],
        ];

        foreach ($contributionsData as $month => $partnerAmounts) {
            foreach ($partnerAmounts as $name => $amount) {
                if (isset($investorModels[$name])) {
                    InvestorContribution::updateOrCreate(
                        [
                            'investor_id' => $investorModels[$name]->id,
                            'month'       => $month,
                            'year'        => 2025,
                        ],
                        [
                            'amount' => $amount,
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

        // 4. Create Stock & Crypto Assets from Excel
        $assets = [
            [
                'symbol' => 'DOGE',
                'name' => 'Dogecoin',
                'asset_type' => 'crypto',
                'quantity' => 179,
                'buy_price' => 13.78,
                'buy_sell_charges' => 26.24,
                'investment_amount' => 2500,
                'api_identifier' => 'dogecoin',
                'initial_price' => 6.84,
            ],
            [
                'symbol' => 'TATAPOWER',
                'name' => 'Tata Power Co Ltd',
                'asset_type' => 'stock_nse',
                'quantity' => 5,
                'buy_price' => 377.25,
                'buy_sell_charges' => 0,
                'investment_amount' => 1889,
                'api_identifier' => 'TATAPOWER.NS',
                'initial_price' => 375.15,
            ],
            [
                'symbol' => 'ADANIGREEN',
                'name' => 'Adani Green Energy Ltd',
                'asset_type' => 'stock_nse',
                'quantity' => 3,
                'buy_price' => 1008.00,
                'buy_sell_charges' => 0,
                'investment_amount' => 3027,
                'api_identifier' => 'ADANIGREEN.NS',
                'initial_price' => 1320.70,
            ],
            [
                'symbol' => 'VET',
                'name' => 'VeChain (VET)',
                'asset_type' => 'crypto',
                'quantity' => 34312,
                'buy_price' => 0.96,
                'buy_sell_charges' => 386.00,
                'investment_amount' => 33328,
                'api_identifier' => 'vechain',
                'initial_price' => 0.44,
            ],
            [
                'symbol' => 'SOL',
                'name' => 'Solana (SOL)',
                'asset_type' => 'crypto',
                'quantity' => 0.99,
                'buy_price' => 8123.99,
                'buy_sell_charges' => 124.28,
                'investment_amount' => 8248,
                'api_identifier' => 'solana',
                'initial_price' => 7268.91,
            ],
            [
                'symbol' => 'SOUTHBANK',
                'name' => 'South Indian Bank Ltd',
                'asset_type' => 'stock_nse',
                'quantity' => 70,
                'buy_price' => 40.13,
                'sell_price' => 40.39,
                'buy_sell_charges' => 18.00,
                'investment_amount' => 2827,
                'api_identifier' => 'SOUTHBANK.NS',
                'initial_price' => 46.27,
            ],
        ];

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
