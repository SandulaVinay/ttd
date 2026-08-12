<?php

namespace App\Services;

use App\Models\AssetPrice;
use App\Models\CompanyExpense;
use App\Models\FundBalance;
use App\Models\Investor;
use App\Models\InvestorContribution;
use App\Models\PortfolioAsset;

class PortfolioService
{
    /**
     * Get complete dashboard summary & metrics.
     */
    public function getDashboardSummary(): array
    {
        $assets = PortfolioAsset::with('price')->get();
        $prices = AssetPrice::all()->keyBy('symbol');

        $totalInvestment = 0;
        $totalCurrentValue = 0;
        $totalStockValue = 0;
        $totalCryptoValue = 0;
        $totalStockInvestment = 0;
        $totalCryptoInvestment = 0;

        $holdings = $assets->map(function ($asset) use ($prices, &$totalInvestment, &$totalCurrentValue, &$totalStockValue, &$totalCryptoValue, &$totalStockInvestment, &$totalCryptoInvestment) {
            $cachedPrice = $prices->get($asset->symbol);
            $livePrice = $cachedPrice ? $cachedPrice->current_price_inr : $asset->buy_price;
            $change24h = $cachedPrice ? $cachedPrice->change_24h : 0;
            $lastUpdated = $cachedPrice ? $cachedPrice->last_updated_at : null;

            $currentValue = $asset->quantity * $livePrice;
            $pnl = $currentValue - $asset->investment_amount;
            $pnlPercentage = $asset->investment_amount > 0 ? ($pnl / $asset->investment_amount) * 100 : 0;

            $totalInvestment += $asset->investment_amount;
            $totalCurrentValue += $currentValue;

            if ($asset->asset_type === 'stock_nse') {
                $totalStockValue += $currentValue;
                $totalStockInvestment += $asset->investment_amount;
            } else {
                $totalCryptoValue += $currentValue;
                $totalCryptoInvestment += $asset->investment_amount;
            }

            return [
                'id' => $asset->id,
                'symbol' => $asset->symbol,
                'name' => $asset->name,
                'asset_type' => $asset->asset_type,
                'quantity' => (float)$asset->quantity,
                'buy_price' => (float)$asset->buy_price,
                'sell_price' => (float)$asset->sell_price,
                'buy_sell_charges' => (float)$asset->buy_sell_charges,
                'investment_amount' => (float)$asset->investment_amount,
                'live_price' => (float)$livePrice,
                'current_value' => (float)$currentValue,
                'pnl' => (float)$pnl,
                'pnl_percentage' => (float)$pnlPercentage,
                'change_24h' => (float)$change24h,
                'last_updated' => $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->diffForHumans() : 'N/A',
            ];
        });

        $fundBalance = FundBalance::first();
        $availableCash = $fundBalance ? (float)$fundBalance->available_cash : 66557.00;

        $totalPortfolioValue = $availableCash + $totalCurrentValue;
        $totalNetPnl = $totalCurrentValue - $totalInvestment;
        $totalNetPnlPercentage = $totalInvestment > 0 ? ($totalNetPnl / $totalInvestment) * 100 : 0;

        // Investor partner contribution totals
        $investors = Investor::with('contributions')->get();
        $investorContributions = [];
        
        $dbMonths = InvestorContribution::select('month')->distinct()->pluck('month')->toArray();
        $defaultMonths = [
            'November 2025',
            'December 2025',
            'January 2026',
            'February 2026',
            'March 2026',
            'April 2026',
            'May 2026',
            'June 2026',
            'July 2026',
            'August 2026'
        ];
        $months = !empty($dbMonths) ? array_unique(array_merge($defaultMonths, $dbMonths)) : $defaultMonths;

        foreach ($investors as $investor) {
            $contributionsByMonth = $investor->contributions->keyBy('month');
            $investorTotal = 0;
            $monthlyBreakdown = [];

            foreach ($months as $month) {
                $amount = isset($contributionsByMonth[$month]) ? (float)$contributionsByMonth[$month]->amount : 0;
                $monthlyBreakdown[$month] = $amount;
                $investorTotal += $amount;
            }

            $investorContributions[] = [
                'id' => $investor->id,
                'name' => $investor->name,
                'type' => $investor->type,
                'monthly' => $monthlyBreakdown,
                'total' => $investorTotal,
                'equity_share' => $totalPortfolioValue > 0 ? round(($investorTotal / max(1, InvestorContribution::sum('amount'))) * 100, 2) : 0,
            ];
        }

        $expenses = CompanyExpense::orderBy('expense_date', 'desc')->get();
        $totalExpenses = CompanyExpense::sum('amount');

        $stockHoldingsList = $assets->where('asset_type', 'stock_nse')->map(fn($a) => $a->symbol . ' (' . (float)$a->quantity . ')')->implode(', ');
        $cryptoHoldingsList = $assets->where('asset_type', 'crypto')->map(fn($a) => $a->symbol . ' (' . (float)$a->quantity . ')')->implode(', ');

        return [
            'total_portfolio_value' => $totalPortfolioValue,
            'available_cash' => $availableCash,
            'total_investment' => $totalInvestment,
            'total_current_value' => $totalCurrentValue,
            'total_net_pnl' => $totalNetPnl,
            'total_net_pnl_percentage' => $totalNetPnlPercentage,
            'total_stock_value' => $totalStockValue,
            'total_crypto_value' => $totalCryptoValue,
            'total_stock_investment' => $totalStockInvestment,
            'total_crypto_investment' => $totalCryptoInvestment,
            'stock_holdings_list' => $stockHoldingsList ?: 'None',
            'crypto_holdings_list' => $cryptoHoldingsList ?: 'None',
            'holdings' => $holdings,
            'investors' => $investorContributions,
            'months' => $months,
            'expenses' => $expenses,
            'total_expenses' => $totalExpenses,
        ];
    }
}
