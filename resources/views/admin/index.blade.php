@extends('admin.layout.table_master_material')

@section('title', 'Dashboard')

@section('sidebar')
    @parent
@endsection

@section('content')

    <style>
        .dash-card-hover {
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .dash-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(35, 45, 65, 0.12);
        }
        .dash-trend {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
            padding: 2px 9px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.9);
        }
        .dash-trend.up { color: #1b8a3f; }
        .dash-trend.down { color: #d3283f; }
        .dash-trend.flat { color: #6c7a89; }
        .dash-trend .mdi { font-size: 14px; }

        /* Solid-color stat cards: readable white text instead of dark text on a
           semi-transparent pastel background (the theme's default widget-two-*). */
        .dash-stat {
            border: none !important;
            color: #fff;
        }
        .dash-stat .wigdet-two-content p.dash-stat-label {
            color: rgba(255, 255, 255, 0.85);
            letter-spacing: .03em;
        }
        .dash-stat .wigdet-two-content h2 {
            color: #fff;
        }
        .dash-stat .wigdet-two-content p.dash-stat-sub {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 12px;
            max-width: 62%;
        }
        .dash-stat .widget-two-icon {
            color: rgba(255, 255, 255, 0.95) !important;
            border-color: rgba(255, 255, 255, 0.55) !important;
        }
        .dash-stat-blue   { background-color: #3b7ddd; }
        .dash-stat-green  { background-color: #17b06c; }
        .dash-stat-cyan   { background-color: #1a9cb0; }
        .dash-stat-orange { background-color: #f0762b; }
        .dash-stat-purple { background-color: #7148d6; }
        .dash-stat-red    { background-color: #e6486a; }
        .dash-stat-teal   { background-color: #17a08f; }

        /* Header action buttons: right-aligned, spaced, solid readable colors. */
        .dash-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .dash-actions .btn {
            border: none !important;
            border-radius: 4px;
            font-weight: 600;
        }
        .dash-btn-quotation { background-color: #3b7ddd !important; color: #fff !important; }
        .dash-btn-quotation:hover { background-color: #2f68bd !important; color: #fff !important; }
        .dash-btn-sales { background-color: #17b06c !important; color: #fff !important; }
        .dash-btn-sales:hover { background-color: #13985d !important; color: #fff !important; }
        .dash-btn-purchase { background-color: #f0762b !important; color: #fff !important; }
        .dash-btn-purchase:hover { background-color: #d9631d !important; color: #fff !important; }
        @media (max-width: 767px) {
            .dash-actions { justify-content: flex-start; }
        }
        .dash-glance-row {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3fa;
        }
        .dash-glance-row:last-child { border-bottom: none; }
        .dash-glance-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .dash-glance-label {
            flex: 1;
            color: #6c7a89;
            font-size: 13px;
        }
        .dash-glance-value {
            font-weight: 700;
            font-size: 15px;
        }
        #salesTrendChart {
            width: 100% !important;
        }

        .dash-alert {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 14px rgba(35, 45, 65, 0.1);
        }
        .dash-alert-warning {
            background-color: #f0762b;
            color: #fff;
        }
        .dash-alert-icon {
            font-size: 26px;
            flex-shrink: 0;
        }
        .dash-alert-body {
            flex: 1;
            font-size: 14px;
        }
        .dash-alert-action {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #fff;
            font-weight: 700;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
            transition: background .15s ease;
        }
        .dash-alert-action:hover {
            background: rgba(255, 255, 255, 0.32);
            color: #fff;
            text-decoration: none;
        }

        /* Chart legend for the monthly-breakdown donut. */
        .dash-legend {
            list-style: none;
            margin: 30px 0 0;
            padding: 0;
        }
        .dash-legend li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6c7a89;
            padding: 6px 0;
        }
        .dash-legend li b {
            margin-left: auto;
            color: #313a46;
        }
        .dash-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* "View all" link under a colored panel's table. */
        .dash-view-all {
            display: block;
            text-align: right;
            font-weight: 600;
            font-size: 13px;
            margin-top: 10px;
        }

        /* Avatar-style people list (Top Customers), matching the reference's
           Messages panel: colored circle initial + name + subtext. */
        .dash-people-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .dash-people-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3fa;
        }
        .dash-people-item:last-child { border-bottom: none; }
        .dash-people-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }
        .dash-people-name {
            margin: 0;
            font-weight: 600;
            font-size: 13.5px;
            color: #313a46;
        }
        .dash-people-sub {
            margin: 0;
            font-size: 12px;
            color: #98a6ad;
        }
    </style>

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Dashboard</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    Dashboard
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                @php
                    $hour = (int) date('G');
                    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                @endphp

                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">
                            <h3 class="m-t-0 m-b-0">{{ $greeting }}, {{ Auth::user()->name ?? 'Admin' }}</h3>
                            <p class="text-muted m-b-0">{{ date('l, d F Y') }}</p>
                            <div class="dash-actions">
                                <a href="{{ route('admin.quotation.add') }}" class="btn dash-btn-quotation waves-effect waves-light"><i class="mdi mdi-plus"></i> New Quotation</a>
                                <a href="{{ route('admin.sales.create') }}" class="btn dash-btn-sales waves-effect waves-light"><i class="mdi mdi-plus"></i> New Sales Order</a>
                                <a href="{{ route('admin.purchase.add') }}" class="btn dash-btn-purchase waves-effect waves-light"><i class="mdi mdi-plus"></i> New Purchase</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                @if(($pendingQuotation ?? 0) > 0)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="dash-alert dash-alert-warning">
                                <i class="mdi mdi-alert-circle-outline dash-alert-icon"></i>
                                <div class="dash-alert-body">
                                    <strong>{{ $pendingQuotation }}</strong> quotation(s) awaiting conversion.
                                </div>
                                <a href="{{ route('admin.quotation.list',['status' => 'Y']) }}" class="dash-alert-action">View pending <i class="mdi mdi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                @endif

                <h4 class="m-t-20 m-b-20">Today's Performance</h4>
                <div class="row">

                    @php
                        $perfCards = [
                            ['route' => 'admin.sales.list', 'icon' => 'mdi-cart-outline', 'class' => 'dash-stat-blue', 'label' => 'Sales Orders', 'today' => $todaySales ?? 0, 'yesterday' => $yesterdaySales ?? 0, 'amount' => $todaySalesAmount ?? 0],
                            ['route' => 'admin.invoice.list', 'icon' => 'mdi-receipt', 'class' => 'dash-stat-green', 'label' => 'Invoices', 'today' => $todayInvoice ?? 0, 'yesterday' => $yesterdayInvoice ?? 0, 'amount' => $todayInvoiceAmount ?? 0],
                            ['route' => 'admin.purchase.list', 'icon' => 'mdi-truck', 'class' => 'dash-stat-cyan', 'label' => 'Purchase Orders', 'today' => $todayPurchase ?? 0, 'yesterday' => $yesterdayPurchase ?? 0, 'amount' => $todayPurchaseAmount ?? 0],
                            ['route' => 'admin.challan.list', 'icon' => 'mdi-truck-delivery', 'class' => 'dash-stat-orange', 'label' => 'Deliveries', 'today' => $todayDelivery ?? 0, 'yesterday' => $yesterdayDelivery ?? 0, 'amount' => $todayDeliveryAmount ?? 0],
                            ['route' => 'admin.quotation.list', 'icon' => 'mdi-file-document-box', 'class' => 'dash-stat-purple', 'label' => 'Quotations', 'today' => $todayQuotation ?? 0, 'yesterday' => $yesterdayQuotation ?? 0, 'amount' => $todayQuotationAmount ?? 0],
                        ];
                    @endphp

                    @foreach($perfCards as $card)
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route($card['route']) }}" class="card-box widget-box-two dash-card-hover dash-stat {{ $card['class'] }}" style="display:block;">
                                <i class="mdi {{ $card['icon'] }} widget-two-icon"></i>
                                <div class="wigdet-two-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow dash-stat-label" title="{{ $card['label'] }}">{{ $card['label'] }}</p>
                                    @php $diff = $card['today'] - $card['yesterday']; @endphp
                                    <h2>{{ $card['today'] }}
                                        <small>
                                            @if($diff == 0)
                                                <i class="mdi mdi-minus dash-stat-sub"></i>
                                            @else
                                                <i class="mdi mdi-arrow-{{ $diff > 0 ? 'up' : 'down' }} text-{{ $diff > 0 ? 'success' : 'danger' }}"></i>
                                            @endif
                                        </small>
                                    </h2>
                                    <p class="m-0 dash-stat-sub">Last: {{ $card['yesterday'] }}</p>
                                    <p class="m-0 dash-stat-sub">₹{{ number_format($card['amount']) }} today</p>
                                </div>
                            </a>
                        </div><!-- end col -->
                    @endforeach

                </div>
                <!-- end row -->

                <h4 class="m-t-20 m-b-20">{{ date('F') }} Snapshot</h4>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-box-two dash-card-hover dash-stat dash-stat-blue">
                            <i class="mdi mdi-cart-outline widget-two-icon"></i>
                            <div class="wigdet-two-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow dash-stat-label" title="Monthly Sales">Monthly Sales</p>
                                <h2>₹{{ number_format($monthSalesAmount ?? 0) }}</h2>
                                <p class="m-0 dash-stat-sub"><b>Orders:</b> {{ $monthSales ?? 0 }}</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-box-two dash-card-hover dash-stat dash-stat-red">
                            <i class="mdi mdi-receipt widget-two-icon"></i>
                            <div class="wigdet-two-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow dash-stat-label" title="Monthly Invoices">Monthly Invoices</p>
                                <h2>₹{{ number_format($monthInvoiceAmount ?? 0) }}</h2>
                                <p class="m-0 dash-stat-sub"><b>Invoices:</b> {{ $monthInvoice ?? 0 }}</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card-box widget-box-two dash-card-hover dash-stat dash-stat-teal">
                            <i class="mdi mdi-account-multiple widget-two-icon"></i>
                            <div class="wigdet-two-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow dash-stat-label" title="Total Customers">Total Customers</p>
                                <h2>{{ $totcustomer ?? 0 }}</h2>
                                <p class="m-0 dash-stat-sub"><b>Purchase Orders:</b> {{ $monthPurchase ?? 0 }} this month</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-20">Sales Trend <small class="text-muted">(Last 14 Days)</small></h4>
                            <canvas id="salesTrendChart" height="120"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-20">{{ date('F') }} Breakdown <small class="text-muted">(This Month)</small></h4>
                            @php
                                $glance = [
                                    ['color' => '#7266ba', 'label' => 'Quotations', 'value' => $monthQuotation ?? 0],
                                    ['color' => '#188ae2', 'label' => 'Sales Orders', 'value' => $monthSales ?? 0],
                                    ['color' => '#00acc1', 'label' => 'Purchase Orders', 'value' => $monthPurchase ?? 0],
                                    ['color' => '#f0762b', 'label' => 'Delivery Challans', 'value' => $monthDelivery ?? 0],
                                    ['color' => '#17b06c', 'label' => 'Invoices', 'value' => $monthInvoice ?? 0],
                                ];
                            @endphp
                            <div class="row">
                                <div class="col-sm-7">
                                    <canvas id="monthDonutChart" height="220"></canvas>
                                </div>
                                <div class="col-sm-5">
                                    <ul class="dash-legend">
                                        @foreach($glance as $g)
                                            <li>
                                                <span class="dash-legend-dot" style="background-color: {{ $g['color'] }};"></span>
                                                {{ $g['label'] }} <b>{{ $g['value'] }}</b>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-color panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Recent Sales Orders</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover m-0">
                                        <thead>
                                            <tr>
                                                <th>SO No</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($recentSalesOrders ?? [] as $so)
                                            <tr>
                                                <td><a href="{{ route('admin.sales.view', $so->id) }}">{{ $so->salaesorder_no }}</a></td>
                                                <td>{{ $so->customer_name }}</td>
                                                <td>{{ $so->salaesorder_date ? date('d-m-Y', strtotime($so->salaesorder_date)) : '-' }}</td>
                                                <td>₹{{ number_format($so->grand_total ?? 0) }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = [
                                                            'Created' => 'badge-info', 'Approved' => 'badge-primary',
                                                            'Delivered' => 'badge-success', 'Cancelled' => 'badge-danger',
                                                        ][$so->status] ?? 'badge-default';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">{{ $so->status ?? '-' }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted">No sales orders yet</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('admin.sales.list') }}" class="dash-view-all">View all sales orders <i class="mdi mdi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel panel-color panel-purple">
                            <div class="panel-heading">
                                <h3 class="panel-title">Top Customers <span class="panel-sub-title">({{ date('F') }})</span></h3>
                            </div>
                            <div class="panel-body">
                                <div class="dash-people-list">
                                    @forelse($topCustomers ?? [] as $i => $c)
                                        <div class="dash-people-item">
                                            <span class="dash-people-avatar" style="background-color: {{ ['#7266ba', '#188ae2', '#00acc1', '#f0762b', '#17b06c'][$i % 5] }};">
                                                {{ strtoupper(substr($c->customer_name ?? '?', 0, 1)) }}
                                            </span>
                                            <div class="dash-people-body">
                                                <p class="dash-people-name">{{ $c->customer_name ?? 'Unknown' }}</p>
                                                <p class="dash-people-sub">₹{{ number_format($c->total_amount ?? 0) }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center m-0">No invoices recorded this month</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-color panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Top Products <span class="panel-sub-title">({{ date('F') }})</span></h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover m-0">
                                        <tbody>
                                        @forelse($topProducts ?? [] as $i => $p)
                                            <tr>
                                                <td>
                                                    @php
                                                        $rankClass = $i == 0 ? 'badge-danger' : ($i == 1 ? 'badge-default' : ($i == 2 ? 'badge-warning' : 'badge-info'));
                                                    @endphp
                                                    <span class="badge {{ $rankClass }}">{{ $i + 1 }}</span>
                                                    {{ $p->product_name ?? 'Unknown' }}
                                                </td>
                                                <td class="text-right text-muted">{{ number_format($p->total_qty, 0) }} sold</td>
                                            </tr>
                                        @empty
                                            <tr><td class="text-center text-muted">No sales recorded this month</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card-box">
                            <h4 class="header-title m-t-0">Inventory Overview</h4>
                            <div class="row text-center">
                                <div class="col-xs-4">
                                    <h2 class="m-b-0">{{ $totproduct ?? 0 }}</h2>
                                    <p class="text-muted m-0">Products</p>
                                </div>
                                <div class="col-xs-4">
                                    <h2 class="m-b-0">{{ $totcategory ?? 0 }}</h2>
                                    <p class="text-muted m-0">Categories</p>
                                </div>
                                <div class="col-xs-4">
                                    <h2 class="m-b-0">{{ $totbrand ?? 0 }}</h2>
                                    <p class="text-muted m-0">Brands</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <script src="/admin/plugins/chart.js/chart.min.js"></script>
        <script>
            (function () {
                var canvas = document.getElementById('salesTrendChart');
                if (!canvas || typeof Chart === 'undefined') return;

                var labels = @json($salesTrendLabels ?? []);
                var data = @json($salesTrendData ?? []);

                var ctx = canvas.getContext('2d');
                var gradient = ctx.createLinearGradient(0, 0, 0, 260);
                gradient.addColorStop(0, 'rgba(24, 138, 226, 0.28)');
                gradient.addColorStop(1, 'rgba(24, 138, 226, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales',
                            data: data,
                            borderColor: '#188ae2',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHitRadius: 12,
                            pointHoverBackgroundColor: '#188ae2',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                            lineTension: 0.35,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#fff',
                            titleFontColor: '#313a46',
                            bodyFontColor: '#313a46',
                            borderColor: '#e3eaef',
                            borderWidth: 1,
                            displayColors: false,
                            callbacks: {
                                label: function (tooltipItem) {
                                    return '₹' + Number(tooltipItem.yLabel).toLocaleString('en-IN');
                                }
                            }
                        },
                        scales: {
                            xAxes: [{
                                gridLines: { display: false },
                                ticks: { fontColor: '#98a6ad', maxRotation: 0, autoSkip: true, maxTicksLimit: 7 }
                            }],
                            yAxes: [{
                                gridLines: { color: '#f1f3fa', drawBorder: false },
                                ticks: {
                                    fontColor: '#98a6ad',
                                    beginAtZero: true,
                                    callback: function (v) {
                                        return v >= 1000 ? (v / 1000) + 'k' : v;
                                    }
                                }
                            }]
                        }
                    }
                });
            })();

            (function () {
                var canvas = document.getElementById('monthDonutChart');
                if (!canvas || typeof Chart === 'undefined') return;

                var glanceLabels = @json(collect($glance ?? [])->pluck('label'));
                var glanceValues = @json(collect($glance ?? [])->pluck('value'));
                var glanceColors = @json(collect($glance ?? [])->pluck('color'));

                new Chart(canvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: glanceLabels,
                        datasets: [{
                            data: glanceValues,
                            backgroundColor: glanceColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        cutoutPercentage: 68,
                        tooltips: {
                            backgroundColor: '#fff',
                            titleFontColor: '#313a46',
                            bodyFontColor: '#313a46',
                            borderColor: '#e3eaef',
                            borderWidth: 1,
                            displayColors: true
                        }
                    }
                });
            })();
        </script>

@endsection
