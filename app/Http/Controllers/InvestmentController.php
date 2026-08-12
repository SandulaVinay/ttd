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
            'quantity' => 'required|numeric|min:0.000001',
            'buy_price' => 'required|numeric|min:0',
            'buy_sell_charges' => 'nullable|numeric|min:0',
            'api_identifier' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $charges = $validated['buy_sell_charges'] ?? 0;
        $investmentAmount = ($validated['quantity'] * $validated['buy_price']) + $charges;

        PortfolioAsset::create(array_merge($validated, [
            'investment_amount' => $investmentAmount,
            'buy_sell_charges' => $charges,
        ]));

        // Sync prices after adding new asset
        $this->marketPriceService->updateAllPrices();

        return redirect()->route('investments.index')->with('success', 'New asset added to portfolio successfully!');
    }

    /**
     * Delete an asset holding.
     */
    public function destroyAsset($id)
    {
        $asset = PortfolioAsset::findOrFail($id);
        $asset->delete();

        return redirect()->route('investments.index')->with('success', 'Asset removed from portfolio.');
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

        FundBalance::updateOrCreate(
            ['id' => 1],
            ['available_cash' => $validated['available_cash']]
        );

        return redirect()->route('investments.index')->with('success', 'Available cash fund updated successfully!');
    }
}
