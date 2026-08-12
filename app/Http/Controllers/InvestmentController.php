<?php

namespace App\Http\Controllers;

use App\Models\CompanyExpense;
use App\Models\FundBalance;
use App\Models\InvestorContribution;
use App\Models\PortfolioAsset;
use App\Services\MarketPriceService;
use App\Services\PortfolioService;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    protected PortfolioService $portfolioService;
    protected MarketPriceService $marketPriceService;

    public function __construct(PortfolioService $portfolioService, MarketPriceService $marketPriceService)
    {
        $this->portfolioService = $portfolioService;
        $this->marketPriceService = $marketPriceService;
    }

    public function index(Request $request)
    {
        try {
            $this->marketPriceService->updateAllPrices();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Live price sync on page load error: ' . $e->getMessage());
        }

        $summary = $this->portfolioService->getDashboardSummary();
        return view('investments.index', compact('summary'));
    }

    /**
     * AJAX Endpoint to fetch live stock & crypto prices.
     */
    public function syncLivePrices()
    {
        try {
            $updatedPrices = $this->marketPriceService->updateAllPrices();
            $summary = $this->portfolioService->getDashboardSummary();

            return response()->json([
                'success' => true,
                'message' => 'Live market prices synced successfully!',
                'summary' => $summary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch live prices: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add new stock or crypto holding.
     */
    public function storeAsset(Request $request)
    {
        $validated = $request->validate([
            'symbol' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'asset_type' => 'required|in:stock_nse,crypto',
            'quantity' => 'required|numeric|min:0',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'buy_sell_charges' => 'nullable|numeric|min:0',
            'api_identifier' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['symbol'] = strtoupper(trim($validated['symbol']));
        $charges = (float)($validated['buy_sell_charges'] ?? 0);
        $quantity = (float)$validated['quantity'];
        $buyPrice = (float)$validated['buy_price'];
        $investmentAmount = ($quantity * $buyPrice) + $charges;

        if (empty($validated['api_identifier'])) {
            if ($validated['asset_type'] === 'stock_nse') {
                $validated['api_identifier'] = str_contains($validated['symbol'], '.') ? $validated['symbol'] : "{$validated['symbol']}.NS";
            } else {
                $validated['api_identifier'] = strtolower($validated['symbol']);
            }
        }

        PortfolioAsset::create(array_merge($validated, [
            'investment_amount' => $investmentAmount,
            'buy_sell_charges' => $charges,
        ]));

        try {
            $this->marketPriceService->updateAllPrices();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Price sync after storeAsset error: ' . $e->getMessage());
        }

        return redirect()->route('investments.index')->with('success', "Asset {$validated['symbol']} added to portfolio!");
    }

    /**
     * Update existing stock or crypto holding.
     */
    public function updateAsset(Request $request, $id)
    {
        $asset = PortfolioAsset::findOrFail($id);

        $validated = $request->validate([
            'symbol' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'asset_type' => 'required|in:stock_nse,crypto',
            'quantity' => 'required|numeric|min:0',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'buy_sell_charges' => 'nullable|numeric|min:0',
            'api_identifier' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['symbol'] = strtoupper(trim($validated['symbol']));
        $charges = (float)($validated['buy_sell_charges'] ?? 0);
        $quantity = (float)$validated['quantity'];
        $buyPrice = (float)$validated['buy_price'];
        $investmentAmount = ($quantity * $buyPrice) + $charges;

        $asset->update(array_merge($validated, [
            'investment_amount' => $investmentAmount,
            'buy_sell_charges' => $charges,
        ]));

        try {
            $this->marketPriceService->updateAllPrices();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Price sync after updateAsset error: ' . $e->getMessage());
        }

        return redirect()->route('investments.index')->with('success', "Asset {$asset->symbol} updated successfully!");
    }

    /**
     * Delete an asset holding.
     */
    public function destroyAsset($id)
    {
        $asset = PortfolioAsset::findOrFail($id);
        $symbol = $asset->symbol;
        $asset->delete();

        if (!PortfolioAsset::where('symbol', $symbol)->exists()) {
            \App\Models\AssetPrice::where('symbol', $symbol)->delete();
        }

        return redirect()->route('investments.index')->with('success', "Asset {$symbol} removed from portfolio.");
    }

    /**
     * Add a real-time company expense.
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'paid_by' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        CompanyExpense::create($validated);

        return redirect()->route('investments.index')->with('success', 'Company expense recorded successfully!');
    }

    /**
     * Update an existing company expense.
     */
    public function updateExpense(Request $request, $id)
    {
        $expense = CompanyExpense::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'paid_by' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('investments.index')->with('success', 'Company expense updated successfully!');
    }

    /**
     * Delete a company expense.
     */
    public function destroyExpense($id)
    {
        $expense = CompanyExpense::findOrFail($id);
        $expense->delete();

        return redirect()->route('investments.index')->with('success', 'Company expense deleted.');
    }

    /**
     * Save/Update partner monthly contribution.
     */
    public function updateContribution(Request $request)
    {
        $validated = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);

        InvestorContribution::updateOrCreate(
            [
                'investor_id' => $validated['investor_id'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            [
                'amount' => $validated['amount'],
            ]
        );

        return redirect()->route('investments.index')->with('success', 'Partner contribution updated successfully!');
    }

    /**
     * Update available cash fund balance.
     */
    public function updateCashFund(Request $request)
    {
        $validated = $request->validate([
            'available_cash' => 'required|numeric|min:0',
        ]);

        $totalExpenses = CompanyExpense::sum('amount');
        $baseCash = $validated['available_cash'] + $totalExpenses;

        FundBalance::updateOrCreate(
            ['id' => 1],
            ['available_cash' => $baseCash]
        );

        return redirect()->route('investments.index')->with('success', 'Available cash fund updated successfully!');
    }
}
