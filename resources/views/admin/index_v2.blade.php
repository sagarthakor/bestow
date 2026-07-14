@extends('admin.layout.master_v2')

@section('title', 'Dashboard')

@section('head')
    <link rel="stylesheet" href="{{ asset('admin-v2/css/dashboard.css') }}">
@endsection

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Dashboard',
        'items' => [],
    ])
    @section('breadcrumb-actions')
        <a href="{{ route('admin.quotation.add') }}" class="btn btn-soft-primary btn-sm"><i class="mdi mdi-plus"></i> New Quotation</a>
        <a href="{{ route('admin.sales.create') }}" class="btn btn-soft-success btn-sm"><i class="mdi mdi-plus"></i> New Sales Order</a>
        <a href="{{ route('admin.purchase.add') }}" class="btn btn-soft-warning btn-sm"><i class="mdi mdi-plus"></i> New Purchase</a>
    @endsection
@endsection

@section('content')
    @php
        $hour = (int) date('G');
        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    @endphp

    <p class="dash-welcome reveal">{{ $greeting }}, {{ Auth::user()->name ?? 'Admin' }} 👋 — here's what's happening today, {{ date('l, d F Y') }}.</p>

    <!-- KPI ROW -->
    <div class="row g-3 mb-1">
        <div class="col-xl col-lg-4 col-md-6 col-sm-6 reveal" style="--d:0">
            <a href="{{ route('admin.quotation.list') }}" class="kpi-card-v2 kpi-v2-purple">
                <i class="mdi mdi-file-document-box kpi-v2-icon"></i>
                <div class="kpi-v2-value" data-countup="{{ (int) ($todayQuotation ?? 0) }}">0</div>
                <div class="kpi-v2-label">Quotations Today</div>
                <div class="kpi-v2-sub">₹<span data-countup="{{ (int) ($todayQuotationAmount ?? 0) }}">0</span> value</div>
            </a>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-6 reveal" style="--d:1">
            <a href="{{ route('admin.sales.list') }}" class="kpi-card-v2 kpi-v2-red">
                <i class="mdi mdi-cart-outline kpi-v2-icon"></i>
                <div class="kpi-v2-value" data-countup="{{ (int) ($todaySales ?? 0) }}">0</div>
                <div class="kpi-v2-label">Sales Orders Today</div>
                <div class="kpi-v2-sub">₹<span data-countup="{{ (int) ($todaySalesAmount ?? 0) }}">0</span> value</div>
            </a>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-6 reveal" style="--d:2">
            <a href="{{ route('admin.purchase.list') }}" class="kpi-card-v2 kpi-v2-blue">
                <i class="mdi mdi-truck kpi-v2-icon"></i>
                <div class="kpi-v2-value" data-countup="{{ (int) ($todayPurchase ?? 0) }}">0</div>
                <div class="kpi-v2-label">Purchase Orders Today</div>
                <div class="kpi-v2-sub">₹<span data-countup="{{ (int) ($todayPurchaseAmount ?? 0) }}">0</span> value</div>
            </a>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-6 reveal" style="--d:3">
            <a href="{{ route('admin.challan.list') }}" class="kpi-card-v2 kpi-v2-orange">
                <i class="mdi mdi-truck-delivery kpi-v2-icon"></i>
                <div class="kpi-v2-value" data-countup="{{ (int) ($todayDelivery ?? 0) }}">0</div>
                <div class="kpi-v2-label">Delivery Challans Today</div>
                <div class="kpi-v2-sub">₹<span data-countup="{{ (int) ($todayDeliveryAmount ?? 0) }}">0</span> value</div>
            </a>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-6 reveal" style="--d:4">
            <a href="{{ route('admin.invoice.list') }}" class="kpi-card-v2 kpi-v2-green">
                <i class="mdi mdi-receipt kpi-v2-icon"></i>
                <div class="kpi-v2-value" data-countup="{{ (int) ($todayInvoice ?? 0) }}">0</div>
                <div class="kpi-v2-label">Invoices Today</div>
                <div class="kpi-v2-sub">₹<span data-countup="{{ (int) ($todayInvoiceAmount ?? 0) }}">0</span> value</div>
            </a>
        </div>
    </div>

    <!-- REVENUE WIDGETS -->
    <div class="row g-3 mt-1">
        <div class="col-lg-4 col-md-6 reveal" style="--d:0">
            <div class="card revenue-card">
                <div class="revenue-card-head">
                    <span class="revenue-icon" style="background:var(--color-primary);"><i class="mdi mdi-cart-outline"></i></span>
                    <div>
                        <div class="revenue-value">₹<span data-countup="{{ (int) ($monthSalesAmount ?? 0) }}">0</span></div>
                        <div class="revenue-label">Monthly Sales</div>
                    </div>
                </div>
                <canvas class="revenue-sparkline" data-spark="sales" height="40"></canvas>
                <div class="revenue-foot">{{ $monthSales ?? 0 }} orders this month</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 reveal" style="--d:1">
            <div class="card revenue-card">
                <div class="revenue-card-head">
                    <span class="revenue-icon" style="background:var(--color-success);"><i class="mdi mdi-receipt"></i></span>
                    <div>
                        <div class="revenue-value">₹<span data-countup="{{ (int) ($monthInvoiceAmount ?? 0) }}">0</span></div>
                        <div class="revenue-label">Monthly Invoices</div>
                    </div>
                </div>
                <canvas class="revenue-sparkline" data-spark="invoice" height="40"></canvas>
                <div class="revenue-foot">{{ $monthInvoice ?? 0 }} invoices this month</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 reveal" style="--d:2">
            <div class="card revenue-card">
                <div class="revenue-card-head">
                    <span class="revenue-icon" style="background:var(--color-info);"><i class="mdi mdi-account-multiple"></i></span>
                    <div>
                        <div class="revenue-value" data-countup="{{ (int) ($totcustomer ?? 0) }}">0</div>
                        <div class="revenue-label">Total Customers</div>
                    </div>
                </div>
                <div class="revenue-mini-grid">
                    <div><span class="fw-semibold">{{ $totproduct ?? 0 }}</span><span class="text-muted text-sm d-block">Products</span></div>
                    <div><span class="fw-semibold">{{ $totcategory ?? 0 }}</span><span class="text-muted text-sm d-block">Categories</span></div>
                    <div><span class="fw-semibold">{{ $totbrand ?? 0 }}</span><span class="text-muted text-sm d-block">Brands</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="row g-3 mt-1">
        <div class="col-lg-8 reveal" style="--d:0">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Sales vs Invoice Trend <small class="text-muted fw-normal">(last 6 months)</small></span>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="90"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 reveal" style="--d:1">
            <div class="card h-100">
                <div class="card-header">This Month Breakdown</div>
                <div class="card-body d-flex flex-column align-items-center">
                    <canvas id="breakdownChart" height="200"></canvas>
                    <div class="breakdown-legend mt-3 w-100">
                        <div class="d-flex justify-content-between"><span class="badge-dot" style="color:#6f42c1;">Quotations</span><strong>{{ $monthQuotation ?? 0 }}</strong></div>
                        <div class="d-flex justify-content-between"><span class="badge-dot" style="color:#e74a3b;">Sales Orders</span><strong>{{ $monthSales ?? 0 }}</strong></div>
                        <div class="d-flex justify-content-between"><span class="badge-dot" style="color:#4e73df;">Purchase Orders</span><strong>{{ $monthPurchase ?? 0 }}</strong></div>
                        <div class="d-flex justify-content-between"><span class="badge-dot" style="color:#f6a623;">Delivery Challans</span><strong>{{ $monthDelivery ?? 0 }}</strong></div>
                        <div class="d-flex justify-content-between"><span class="badge-dot" style="color:#1cc88a;">Invoices</span><strong>{{ $monthInvoice ?? 0 }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ACTIVITY + LATEST QUOTATIONS -->
    <div class="row g-3 mt-1">
        <div class="col-lg-6 reveal" style="--d:0">
            <div class="card h-100">
                <div class="card-header"><i class="mdi mdi-clock-outline"></i> Recent Sales Orders <a href="{{ route('admin.sales.list') }}" class="view-all float-end">View all &rarr;</a></div>
                <div class="table-responsive">
                    <table class="table table-compact mb-0">
                        <thead><tr><th>SO No</th><th>Customer</th><th>Date</th><th class="text-end">Amount</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($recentSalesOrders ?? [] as $so)
                            <tr>
                                <td><a href="{{ route('admin.sales.view', $so->id) }}">{{ $so->salaesorder_no }}</a></td>
                                <td>{{ $so->customer_name }}</td>
                                <td>{{ $so->salaesorder_date ? date('d-m-Y', strtotime($so->salaesorder_date)) : '-' }}</td>
                                <td class="text-end">₹{{ number_format($so->grand_total ?? 0) }}</td>
                                <td>
                                    @php
                                        $statusClass = ['Created'=>'badge-soft-primary','Approved'=>'badge-soft-success','Delivered'=>'badge-soft-success','Cancelled'=>'badge-soft-danger'][$so->status] ?? 'badge-soft-neutral';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $so->status ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="table-empty"><span>📭</span>No sales orders yet</div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 reveal" style="--d:1">
            <div class="card h-100">
                <div class="card-header"><i class="mdi mdi-file-document-outline"></i> Latest Quotations <a href="{{ route('admin.quotation.list') }}" class="view-all float-end">View all &rarr;</a></div>
                <div class="table-responsive">
                    <table class="table table-compact mb-0">
                        <thead><tr><th>Quot No</th><th>Customer</th><th>Date</th><th class="text-end">Amount</th><th>Stage</th></tr></thead>
                        <tbody>
                        @forelse($latestQuotations ?? [] as $q)
                            <tr>
                                <td>{{ $q->quotation_no }}</td>
                                <td>{{ $q->customer_name }}</td>
                                <td>{{ $q->quot_date ? date('d-m-Y', strtotime($q->quot_date)) : '-' }}</td>
                                <td class="text-end">₹{{ number_format($q->grand_total ?? 0) }}</td>
                                <td>
                                    @php
                                        $qClass = ['Accepted'=>'badge-soft-success','Invoiced'=>'badge-soft-success','Canceled'=>'badge-soft-danger','Cancelled'=>'badge-soft-danger'][$q->quot_stage] ?? 'badge-soft-info';
                                    @endphp
                                    <span class="badge {{ $qClass }}">{{ $q->quot_stage ?? 'Pending' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="table-empty"><span>📭</span>No quotations yet</div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ANALYTICS: TOP PRODUCTS / TOP CUSTOMERS / PENDING -->
    <div class="row g-3 mt-1 mb-3">
        <div class="col-lg-4 reveal" style="--d:0">
            <div class="card h-100">
                <div class="card-header"><i class="mdi mdi-trophy"></i> Top Products <small class="text-muted fw-normal">({{ date('F') }})</small></div>
                <div class="card-body">
                    @forelse($topProducts ?? [] as $i => $p)
                        <div class="lb-row">
                            <span class="lb-name"><span class="rank-badge {{ $i==0?'rank-1':($i==1?'rank-2':($i==2?'rank-3':'rank-other')) }}">{{ $i+1 }}</span>{{ $p->product_name ?? 'Unknown' }}</span>
                            <span class="lb-sub">{{ number_format($p->total_qty, 0) }} sold</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No sales recorded this month</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4 reveal" style="--d:1">
            <div class="card h-100">
                <div class="card-header"><i class="mdi mdi-account-star"></i> Top Customers <small class="text-muted fw-normal">({{ date('F') }})</small></div>
                <div class="card-body">
                    @forelse($topCustomers ?? [] as $i => $c)
                        <div class="lb-row">
                            <span class="lb-name"><span class="rank-badge {{ $i==0?'rank-1':($i==1?'rank-2':($i==2?'rank-3':'rank-other')) }}">{{ $i+1 }}</span>{{ $c->customer_name ?? 'Unknown' }}</span>
                            <span class="lb-value">₹{{ number_format($c->total_amount ?? 0) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No invoices recorded this month</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4 reveal" style="--d:2">
            <div class="card h-100">
                <div class="card-header"><i class="mdi mdi-bell-outline"></i> Pending Quotations</div>
                <div class="card-body">
                    <div class="alert-box">
                        @if(($pendingQuotation ?? 0) > 0)
                            <i class="mdi mdi-alert-circle alert-icon" style="color:var(--color-warning);"></i>
                            <div><b data-countup="{{ (int) $pendingQuotation }}">0</b> quotation(s) awaiting conversion</div>
                            <a href="{{ route('admin.quotation.list',['status' => 'Y']) }}" class="mt-2 d-inline-block">View pending &rarr;</a>
                        @else
                            <i class="mdi mdi-check-circle alert-icon" style="color:var(--color-success);"></i>
                            <div>All quotations converted</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('admin-v2/vendor/chartjs/chart.umd.min.js') }}"></script>
    <script>
        window.__dashboardTrend = @json($monthlyTrend ?? []);
        @php
            $breakdownData = [
                'labels' => ['Quotations', 'Sales Orders', 'Purchase Orders', 'Delivery Challans', 'Invoices'],
                'values' => [$monthQuotation ?? 0, $monthSales ?? 0, $monthPurchase ?? 0, $monthDelivery ?? 0, $monthInvoice ?? 0],
                'colors' => ['#6f42c1', '#e74a3b', '#4e73df', '#f6a623', '#1cc88a'],
            ];
        @endphp
        window.__dashboardBreakdown = @json($breakdownData);
    </script>
    <script src="{{ asset('admin-v2/js/dashboard.js') }}"></script>
@endpush
