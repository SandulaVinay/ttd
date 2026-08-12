@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Top Header Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom border-gold">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--garuda-gold, #d4af37);">
                <i class="fas fa-chart-line me-2"></i> The Growth Circle - Investment & Expense Hub
            </h2>
            <p class="text-muted mb-0 small">Real-time Stock Market (NSE), Crypto Portfolio & Company Expense Tracker</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <button id="btnSyncPrices" onclick="syncLivePrices()" class="btn btn-warning shadow-sm fw-semibold" style="background: linear-gradient(135deg, #d4af37, #aa820a); border: none; color: #fff;">
                <i class="fas fa-sync-alt me-1" id="syncSpinner"></i> Sync Live Market Prices
            </button>
            <button class="btn btn-primary shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addAssetModal">
                <i class="fas fa-plus-circle me-1"></i> Add Asset
            </button>
            <button class="btn btn-outline-danger shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                <i class="fas fa-receipt me-1"></i> Log Expense
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Key Metrics Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Total Portfolio Net Worth -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 bg-dark text-white rounded-3 overflow-hidden position-relative" style="background: linear-gradient(135deg, #1b2838, #101721) !important;">
                <div class="card-body p-3">
                    <span class="text-uppercase text-muted fw-bold small">Total Portfolio Net Worth</span>
                    <h3 class="fw-bold text-warning mt-2 mb-1" id="valTotalNetWorth">₹{{ number_format($summary['total_portfolio_value'], 2) }}</h3>
                    <div class="small text-muted">Includes Holdings + Cash Balance</div>
                </div>
                <div class="position-absolute end-0 bottom-0 p-3 opacity-25 text-warning" style="font-size: 3rem;">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Available Cash Fund -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-3 bg-white border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-uppercase text-secondary fw-bold small">Available Cash Fund</span>
                        <button class="btn btn-link p-0 text-info" data-bs-toggle="modal" data-bs-target="#editCashModal" title="Update Cash Balance">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    <h3 class="fw-bold text-info mt-2 mb-1" id="valAvailableCash">₹{{ number_format($summary['available_cash'], 2) }}</h3>
                    <div class="small text-muted">Liquid cash in account</div>
                </div>
            </div>
        </div>

        <!-- Invested Amount vs Current Value -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-3 bg-white border-start border-4 border-primary">
                <div class="card-body p-3">
                    <span class="text-uppercase text-secondary fw-bold small">Total Asset Investment</span>
                    <h3 class="fw-bold text-primary mt-2 mb-1" id="valTotalInvestment">₹{{ number_format($summary['total_investment'], 2) }}</h3>
                    <div class="small text-muted">Current Value: <strong id="valTotalCurrentValue">₹{{ number_format($summary['total_current_value'], 2) }}</strong></div>
                </div>
            </div>
        </div>

        <!-- Total P&L -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0 rounded-3 bg-white border-start border-4 {{ $summary['total_net_pnl'] >= 0 ? 'border-success' : 'border-danger' }}">
                <div class="card-body p-3">
                    <span class="text-uppercase text-secondary fw-bold small">Real-Time Total P&L</span>
                    <h3 class="fw-bold {{ $summary['total_net_pnl'] >= 0 ? 'text-success' : 'text-danger' }} mt-2 mb-1" id="valTotalNetPnl">
                        {{ $summary['total_net_pnl'] >= 0 ? '+' : '' }}₹{{ number_format($summary['total_net_pnl'], 2) }}
                    </h3>
                    <div class="small fw-semibold {{ $summary['total_net_pnl'] >= 0 ? 'text-success' : 'text-danger' }}" id="valTotalNetPnlPercentage">
                        <i class="fas {{ $summary['total_net_pnl'] >= 0 ? 'fa-caret-up' : 'fa-caret-down' }} me-1"></i>
                        {{ number_format($summary['total_net_pnl_percentage'], 2) }}% Overall Return
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Breakdown Quick Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="p-3 bg-white rounded shadow-sm border d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-primary text-uppercase me-2"><i class="fas fa-landmark me-1"></i> Indian Stock Market (NSE)</span>
                    <span class="text-muted small fw-semibold">{{ $summary['stock_holdings_list'] }}</span>
                </div>
                <div class="fw-bold text-dark fs-5" id="valStockValue">
                    ₹{{ number_format($summary['total_stock_value'], 2) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 bg-white rounded shadow-sm border d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-warning text-dark text-uppercase me-2"><i class="fab fa-bitcoin me-1"></i> Crypto Assets</span>
                    <span class="text-muted small fw-semibold">{{ $summary['crypto_holdings_list'] }}</span>
                </div>
                <div class="fw-bold text-dark fs-5" id="valCryptoValue">
                    ₹{{ number_format($summary['total_crypto_value'], 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-fill bg-white p-2 rounded shadow-sm mb-4 border" id="portfolioTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold py-2" id="holdings-tab" data-bs-toggle="tab" data-bs-target="#tab-holdings" type="button" role="tab">
                <i class="fas fa-coins me-2"></i> Live Portfolio Holdings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2" id="contributions-tab" data-bs-toggle="tab" data-bs-target="#tab-contributions" type="button" role="tab">
                <i class="fas fa-users-cog me-2"></i> Partner Contributions Matrix
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#tab-expenses" type="button" role="tab">
                <i class="fas fa-file-invoice-dollar me-2"></i> Real-Time Expenses
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-2" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#tab-analytics" type="button" role="tab">
                <i class="fas fa-chart-pie me-2"></i> Portfolio Analytics
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="portfolioTabsContent">
        
        <!-- Tab 1: Live Holdings -->
        <div class="tab-pane fade show active" id="tab-holdings" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-layer-group text-primary me-2"></i> Stock Market & Crypto Holdings</h5>
                    <small class="text-muted" id="lastUpdatedTime">Auto-updates from CoinGecko & Yahoo Finance</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th>Asset Symbol & Name</th>
                                <th>Type</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Avg Buy Price</th>
                                <th class="text-end">Live Price (₹)</th>
                                <th class="text-end">Invested</th>
                                <th class="text-end">Current Value</th>
                                <th class="text-end">P&L (₹ / %)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="holdingsTableBody">
                            @foreach($summary['holdings'] as $asset)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 rounded-circle bg-light p-2 text-center" style="width:40px; height:40px;">
                                            @if($asset['asset_type'] == 'crypto')
                                                <i class="fab fa-bitcoin text-warning fs-5"></i>
                                            @else
                                                <i class="fas fa-building text-primary fs-5"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $asset['symbol'] }} <span class="badge bg-light text-primary border ms-1">({{ (float)$asset['quantity'] }})</span></div>
                                            <div class="small text-muted">{{ $asset['name'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($asset['asset_type'] == 'crypto')
                                        <span class="badge bg-warning text-dark"><i class="fab fa-bitcoin me-1"></i> Crypto</span>
                                    @else
                                        <span class="badge bg-primary"><i class="fas fa-landmark me-1"></i> NSE Stock</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($asset['quantity'], 4) }}</td>
                                <td class="text-end">₹{{ number_format($asset['buy_price'], 2) }}</td>
                                <td class="text-end fw-bold text-dark">
                                    ₹{{ number_format($asset['live_price'], 2) }}
                                    @if($asset['change_24h'] != 0)
                                        <small class="{{ $asset['change_24h'] >= 0 ? 'text-success' : 'text-danger' }} d-block" style="font-size:0.75rem;">
                                            {{ $asset['change_24h'] >= 0 ? '+' : '' }}{{ number_format($asset['change_24h'], 2) }}%
                                        </small>
                                    @endif
                                </td>
                                <td class="text-end text-muted">₹{{ number_format($asset['investment_amount'], 2) }}</td>
                                <td class="text-end fw-bold text-dark">₹{{ number_format($asset['current_value'], 2) }}</td>
                                <td class="text-end fw-bold {{ $asset['pnl'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $asset['pnl'] >= 0 ? '+' : '' }}₹{{ number_format($asset['pnl'], 2) }}
                                    <div class="small font-monospace" style="font-size:0.75rem;">
                                        ({{ number_format($asset['pnl_percentage'], 2) }}%)
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <!-- Edit Asset Button -->
                                        <button class="btn btn-sm btn-outline-primary border-0" data-bs-toggle="modal" data-bs-target="#editAssetModal_{{ $asset['id'] }}" title="Edit Asset">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Asset Form -->
                                        <form action="{{ route('investments.destroyAsset', $asset['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $asset['symbol'] }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Delete Asset">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Asset Modal -->
                                    <div class="modal fade text-start" id="editAssetModal_{{ $asset['id'] }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('investments.updateAsset', $asset['id']) }}" method="POST" class="modal-content">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i> Edit {{ $asset['symbol'] }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Asset Type</label>
                                                        <select name="asset_type" class="form-select" required>
                                                            <option value="stock_nse" {{ $asset['asset_type'] == 'stock_nse' ? 'selected' : '' }}>Indian Share Market (NSE Stock)</option>
                                                            <option value="crypto" {{ $asset['asset_type'] == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Ticker Symbol</label>
                                                        <input type="text" name="symbol" class="form-control" value="{{ $asset['symbol'] }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Asset Full Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $asset['name'] }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-semibold">Quantity</label>
                                                            <input type="number" step="0.000001" name="quantity" class="form-control" value="{{ $asset['quantity'] }}" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-semibold">Avg Buy Price (₹)</label>
                                                            <input type="number" step="0.01" name="buy_price" class="form-control" value="{{ $asset['buy_price'] }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Buy/Sell Charges (₹)</label>
                                                        <input type="number" step="0.01" name="buy_sell_charges" class="form-control" value="{{ $asset['buy_sell_charges'] }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold">Update Asset</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Partner Contributions Matrix -->
        <div class="tab-pane fade" id="tab-contributions" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-users text-gold me-2"></i> Partner Monthly Investments (The Growth Circle)</h5>
                        <small class="text-muted">Monthly contributions pooled into the fund</small>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addContributionModal">
                        <i class="fas fa-plus me-1"></i> Update Contribution
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-start">Month / Year</th>
                                @foreach($summary['investors'] as $investor)
                                    <th>
                                        {{ $investor['name'] }}
                                        @if($investor['type'] == 'business')
                                            <span class="badge bg-warning text-dark d-block text-lowercase font-monospace">business</span>
                                        @endif
                                    </th>
                                @endforeach
                                <th class="bg-secondary text-white">Monthly Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary['months'] as $m)
                            @php $rowTotal = 0; @endphp
                            <tr>
                                <td class="text-start fw-bold bg-light">{{ $m }}</td>
                                @foreach($summary['investors'] as $investor)
                                    @php 
                                        $amt = $investor['monthly'][$m] ?? 0; 
                                        $rowTotal += $amt;
                                    @endphp
                                    <td class="{{ $amt > 0 ? 'fw-semibold text-dark' : 'text-muted' }}">
                                        {{ $amt > 0 ? '₹' . number_format($amt) : '-' }}
                                    </td>
                                @endforeach
                                <td class="fw-bold bg-light text-primary">₹{{ number_format($rowTotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td class="text-start text-uppercase">Total Contributed</td>
                                @foreach($summary['investors'] as $investor)
                                    <td class="text-dark fs-6">₹{{ number_format($investor['total']) }}</td>
                                @endforeach
                                <td class="text-success fs-5">
                                    ₹{{ number_format(array_sum(array_column($summary['investors'], 'total'))) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start text-uppercase text-muted small">Equity Share %</td>
                                @foreach($summary['investors'] as $investor)
                                    <td class="text-primary small">{{ $investor['equity_share'] }}%</td>
                                @endforeach
                                <td class="small">100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: Real-Time Expenses -->
        <div class="tab-pane fade" id="tab-expenses" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-file-invoice-dollar text-danger me-2"></i> Company Operational Expenses</h5>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                        <i class="fas fa-plus me-1"></i> Log New Expense
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small">
                            <tr>
                                <th>Date</th>
                                <th>Title / Description</th>
                                <th>Category</th>
                                <th>Paid By</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($summary['expenses'] as $exp)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</td>
                                <td class="fw-semibold text-dark">{{ $exp->title }}</td>
                                <td><span class="badge bg-secondary">{{ $exp->category }}</span></td>
                                <td>{{ $exp->paid_by ?? 'Company Fund' }}</td>
                                <td class="text-end fw-bold text-danger">₹{{ number_format($exp->amount, 2) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <!-- Edit Expense Button -->
                                        <button class="btn btn-sm btn-outline-primary border-0" data-bs-toggle="modal" data-bs-target="#editExpenseModal_{{ $exp->id }}" title="Edit Expense">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Expense Form -->
                                        <form action="{{ route('investments.destroyExpense', $exp->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Delete Expense">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Expense Modal -->
                                    <div class="modal fade text-start" id="editExpenseModal_{{ $exp->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('investments.updateExpense', $exp->id) }}" method="POST" class="modal-content">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"><i class="fas fa-edit text-danger me-2"></i> Edit Expense</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Title / Description</label>
                                                        <input type="text" name="title" class="form-control" value="{{ $exp->title }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-semibold">Category</label>
                                                            <select name="category" class="form-select" required>
                                                                <option value="Operations" {{ $exp->category == 'Operations' ? 'selected' : '' }}>Operations</option>
                                                                <option value="Tech & Servers" {{ $exp->category == 'Tech & Servers' ? 'selected' : '' }}>Tech & Servers</option>
                                                                <option value="Salaries" {{ $exp->category == 'Salaries' ? 'selected' : '' }}>Salaries</option>
                                                                <option value="Marketing" {{ $exp->category == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                                                <option value="Miscellaneous" {{ $exp->category == 'Miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-semibold">Amount (₹)</label>
                                                            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $exp->amount }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-semibold">Date</label>
                                                            <input type="date" name="expense_date" class="form-control" value="{{ \Carbon\Carbon::parse($exp->expense_date)->format('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-semibold">Paid By</label>
                                                            <input type="text" name="paid_by" class="form-control" value="{{ $exp->paid_by }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger fw-semibold">Update Expense</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No expenses recorded yet. Click "Log New Expense" to add.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end text-uppercase">Total Operational Expenses:</td>
                                <td class="text-end text-danger fs-5">₹{{ number_format($summary['total_expenses'], 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 4: Analytics Charts -->
        <div class="tab-pane fade" id="tab-analytics" role="tabpanel">
            <div class="row g-4">
                <!-- Asset Allocation Chart -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 p-3">
                        <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie text-primary me-2"></i> Asset Allocation (Cash vs Stocks vs Crypto)</h6>
                        <div style="max-height:300px;">
                            <canvas id="chartAssetAllocation"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Partner Equity Share Chart -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 p-3">
                        <h6 class="fw-bold mb-3"><i class="fas fa-user-chart text-success me-2"></i> Partner Capital Share Distribution</h6>
                        <div style="max-height:300px;">
                            <canvas id="chartPartnerShare"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal 1: Add Asset -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('investments.storeAsset') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i> Add Asset Holding</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Asset Type</label>
                    <select name="asset_type" id="asset_type_select" class="form-select" required onchange="toggleApiIdentifierField()">
                        <option value="stock_nse">Indian Share Market (NSE Stock)</option>
                        <option value="crypto">Cryptocurrency</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ticker Symbol</label>
                    <input type="text" name="symbol" class="form-control" placeholder="e.g. TATAPOWER, DOGE, SOL" required uppercase>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Asset Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Tata Power Ltd or Dogecoin" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="number" step="0.000001" name="quantity" class="form-control" placeholder="10" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Avg Buy Price (₹)</label>
                        <input type="number" step="0.01" name="buy_price" class="form-control" placeholder="375.00" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Buy/Sell Charges (₹)</label>
                    <input type="number" step="0.01" name="buy_sell_charges" class="form-control" value="0">
                </div>
                <div class="mb-3" id="api_id_group" style="display:none;">
                    <label class="form-label fw-semibold">CoinGecko ID (For Cryptos)</label>
                    <input type="text" name="api_identifier" class="form-control" placeholder="e.g. dogecoin, vechain, solana">
                    <small class="text-muted">Used for automatic live crypto price fetching</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Save Holding</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Log Expense -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('investments.storeExpense') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-receipt text-danger me-2"></i> Log Company Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Title / Description</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Server hosting / Office supplies" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="Operations">Operations</option>
                            <option value="Tech & Servers">Tech & Servers</option>
                            <option value="Salaries">Salaries</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Miscellaneous">Miscellaneous</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="1500" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Paid By</label>
                        <input type="text" name="paid_by" class="form-control" placeholder="Company Cash / Gowtham">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger fw-semibold">Record Expense</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Edit Cash Balance -->
<div class="modal fade" id="editCashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('investments.updateCashFund') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-wallet text-info me-2"></i> Update Cash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-semibold">Available Cash (₹)</label>
                <input type="number" step="0.01" name="available_cash" class="form-control" value="{{ $summary['available_cash'] }}" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info text-white fw-semibold">Update Balance</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Add/Update Partner Contribution -->
<div class="modal fade" id="addContributionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('investments.updateContribution') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus text-primary me-2"></i> Update Partner Contribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Partner / Investor</label>
                    <select name="investor_id" class="form-select" required>
                        @foreach($summary['investors'] as $inv)
                            <option value="{{ $inv['id'] }}">{{ $inv['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Month</label>
                        <select name="month" class="form-select" required>
                            @foreach($summary['months'] as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Year</label>
                        <input type="number" name="year" class="form-control" value="2025" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contribution Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="2000" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Save Entry</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleApiIdentifierField() {
        const type = document.getElementById('asset_type_select').value;
        const apiGroup = document.getElementById('api_id_group');
        apiGroup.style.display = (type === 'crypto') ? 'block' : 'none';
    }

    function syncLivePrices() {
        const btn = document.getElementById('btnSyncPrices');
        const spinner = document.getElementById('syncSpinner');

        spinner.classList.add('fa-spin');
        btn.disabled = true;

        fetch("{{ route('investments.syncLivePrices') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            spinner.classList.remove('fa-spin');
            btn.disabled = false;

            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Live Market Prices Updated!',
                    text: 'Stock & Crypto prices synced in real-time.',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Reload page after sync to update view metrics cleanly
                setTimeout(() => location.reload(), 1000);
            } else {
                Swal.fire('Error', data.message || 'Failed to update prices', 'error');
            }
        })
        .catch(err => {
            spinner.classList.remove('fa-spin');
            btn.disabled = false;
            Swal.fire('Error', 'Network connection issue while fetching prices.', 'error');
        });
    }

    // Chart.js Visualizations
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Asset Allocation Chart
        const ctxAllocation = document.getElementById('chartAssetAllocation').getContext('2d');
        new Chart(ctxAllocation, {
            type: 'doughnut',
            data: {
                labels: ['Available Cash', 'NSE Stocks', 'Crypto Assets'],
                datasets: [{
                    data: [
                        {{ $summary['available_cash'] }},
                        {{ $summary['total_stock_value'] }},
                        {{ $summary['total_crypto_value'] }}
                    ],
                    backgroundColor: ['#0dcaf0', '#0d6efd', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 2. Partner Capital Distribution Chart
        const ctxPartners = document.getElementById('chartPartnerShare').getContext('2d');
        new Chart(ctxPartners, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($summary['investors'] as $inv)
                        "{{ $inv['name'] }}",
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($summary['investors'] as $inv)
                            {{ $inv['total'] }},
                        @endforeach
                    ],
                    backgroundColor: ['#20c997', '#fd7e14', '#6f42c1', '#d63384', '#0d6efd', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush
@endsection
