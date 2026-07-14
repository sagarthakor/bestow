@extends('admin.layout.table_master')

@section('title', 'Dashboard')

@section('sidebar')
    @parent
@endsection

@section('content')

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

                <style type="text/css">
                    .dash-section-title { margin: 28px 0 14px; font-weight: 600; color: #8a92a5; text-transform: uppercase; font-size: 12.5px; letter-spacing: .8px; }
                    .dash-section-title:first-of-type { margin-top: 5px; }

                    /* Greeting header */
                    .greet-card {
                        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
                        background: #fff; border-radius: 12px; padding: 22px 26px; margin-bottom: 10px;
                        box-shadow: 0 2px 10px rgba(30,40,60,.06); border: 1px solid #eef0f5;
                    }
                    .greet-card h3 { margin: 0; font-weight: 700; color: #2b2f3a; }
                    .greet-card .greet-date { color: #8a92a5; font-size: 13px; margin-top: 3px; }
                    .greet-actions a { margin-left: 8px; }
                    .greet-actions a:first-child { margin-left: 0; }

                    /* KPI hero cards (Today's Performance) */
                    .kpi-card {
                        display: block; position: relative; overflow: hidden; border-radius: 12px; padding: 20px 22px; color: #fff;
                        margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,.12);
                        transition: transform .18s ease, box-shadow .18s ease;
                    }
                    .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 10px 22px rgba(0,0,0,.18); color: #fff; }
                    .kpi-card .kpi-icon { position: absolute; right: 14px; top: 12px; font-size: 46px; opacity: .25; }
                    .kpi-card .kpi-value { font-size: 26px; font-weight: 700; line-height: 1.1; }
                    .kpi-card .kpi-label { text-transform: uppercase; font-size: 12px; letter-spacing: .4px; opacity: .9; margin-top: 4px; }
                    .kpi-card .kpi-sub { font-size: 12px; opacity: .8; margin-top: 6px; }
                    .kpi-red    { background: linear-gradient(135deg,#ff7875,#e74a3b); }
                    .kpi-green  { background: linear-gradient(135deg,#3ddc97,#1cc88a); }
                    .kpi-blue   { background: linear-gradient(135deg,#6c8ef5,#4e73df); }
                    .kpi-orange { background: linear-gradient(135deg,#ffc266,#f6a623); }
                    .kpi-purple { background: linear-gradient(135deg,#9d7bea,#6f42c1); }

                    .row-fifth { display: flex; flex-wrap: wrap; margin-left: -15px; margin-right: -15px; }
                    .col-fifth { width: 20%; padding-left: 15px; padding-right: 15px; }
                    @media (max-width: 991px) { .col-fifth { width: 33.333%; } }
                    @media (max-width: 767px) { .col-fifth { width: 50%; } }

                    /* Snapshot cards */
                    .snap-card {
                        display: flex; align-items: center; background: #fff; border-radius: 12px; padding: 18px 20px;
                        margin-bottom: 24px; box-shadow: 0 2px 10px rgba(30,40,60,.06); border: 1px solid #eef0f5;
                    }
                    .snap-icon {
                        flex: 0 0 48px; width: 48px; height: 48px; border-radius: 10px; display: flex;
                        align-items: center; justify-content: center; font-size: 22px; color: #fff; margin-right: 14px;
                    }
                    .snap-value { font-size: 22px; font-weight: 700; color: #2b2f3a; }
                    .snap-label { font-size: 12px; color: #8a92a5; text-transform: uppercase; letter-spacing: .3px; }
                    .snap-sub { font-size: 12px; color: #b0b6c3; margin-top: 2px; }

                    /* Generic panel card */
                    .panel-card { background: #fff; border-radius: 12px; padding: 20px 22px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(30,40,60,.06); border: 1px solid #eef0f5; }
                    .panel-card h4 { margin: 0 0 16px; font-weight: 700; font-size: 15px; color: #2b2f3a; }
                    .panel-card h4 i { margin-right: 6px; }
                    .panel-card .view-all { float: right; font-size: 12.5px; font-weight: 600; }

                    .table-clean th { border-top: 0 !important; background: #f8f9fc; color: #8a92a5; font-size: 11.5px; text-transform: uppercase; letter-spacing: .3px; }
                    .table-clean td { vertical-align: middle !important; }

                    .status-pill { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
                    .status-created   { background: #eaf1ff; color: #4e73df; }
                    .status-approved  { background: #e6f9f1; color: #17a673; }
                    .status-delivered { background: #e6f9f1; color: #1cc88a; }
                    .status-cancelled { background: #fdeaea; color: #d63b40; }
                    .status-default   { background: #f1f2f6; color: #6b7280; }

                    .glance-row { display: flex; align-items: center; justify-content: space-between; padding: 9px 0; border-bottom: 1px dashed #eef0f5; }
                    .glance-row:last-child { border-bottom: 0; }
                    .glance-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 8px; }
                    .glance-label { color: #555; font-size: 13px; }
                    .glance-value { font-weight: 700; color: #2b2f3a; font-size: 13px; }

                    .alert-box { display: flex; align-items: center; text-align: center; flex-direction: column; padding: 18px 10px; }
                    .alert-box .alert-icon { font-size: 40px; margin-bottom: 10px; }

                    .rank-badge {
                        display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px;
                        border-radius: 6px; font-weight: 700; font-size: 12px; color: #fff; margin-right: 10px; flex: 0 0 26px;
                    }
                    .rank-1 { background: #e74a3b; }
                    .rank-2 { background: #6b7280; }
                    .rank-3 { background: #f6a623; }
                    .rank-other { background: #b0b6c3; }
                    .lb-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f4f5f8; }
                    .lb-row:last-child { border-bottom: 0; }
                    .lb-name { display: flex; align-items: center; font-size: 13.5px; color: #2b2f3a; font-weight: 600; }
                    .lb-sub { font-size: 11.5px; color: #b0b6c3; }
                    .lb-value { font-weight: 700; color: #e74a3b; font-size: 13.5px; }

                    .inv-grid { display: flex; flex-wrap: wrap; margin: -6px; }
                    .inv-cell { flex: 1 1 33%; margin: 6px; padding: 14px; border-radius: 10px; text-align: center; }
                    .inv-cell .inv-value { font-size: 22px; font-weight: 700; }
                    .inv-cell .inv-label { font-size: 11px; text-transform: uppercase; color: #8a92a5; letter-spacing: .3px; margin-top: 2px; }
                    .inv-purple { background: #f2edfc; } .inv-purple .inv-value { color: #6f42c1; }
                    .inv-blue   { background: #eaf1ff; } .inv-blue .inv-value { color: #4e73df; }
                    .inv-pink   { background: #fdeef5; } .inv-pink .inv-value { color: #e83e8c; }
                </style>

                @php
                    $hour = (int) date('G');
                    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                @endphp

                <div class="greet-card">
                    <div>
                        <h3>{{ $greeting }}, {{ Auth::user()->name ?? 'Admin' }} 👋</h3>
                        <div class="greet-date">{{ date('l, d F Y') }}</div>
                    </div>
                    <div class="greet-actions">
                        <a href="{{ route('admin.quotation.add') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i> New Quotation</a>
                        <a href="{{ route('admin.sales.create') }}" class="btn btn-success btn-sm"><i class="mdi mdi-plus"></i> New Sales Order</a>
                        <a href="{{ route('admin.purchase.add') }}" class="btn btn-warning btn-sm"><i class="mdi mdi-plus"></i> New Purchase</a>
                    </div>
                </div>

                <h5 class="dash-section-title">Today's Performance</h5>
                <div class="row-fifth">

                    <div class="col-fifth">
                        <a href="{{ route('admin.sales.list') }}" class="kpi-card kpi-red">
                            <i class="mdi mdi-cart-outline kpi-icon"></i>
                            <div class="kpi-value">{{ $todaySales ?? 0 }}</div>
                            <div class="kpi-label">Sales Orders</div>
                            <div class="kpi-sub">₹{{ number_format($todaySalesAmount ?? 0) }} value</div>
                        </a>
                    </div>
                    <div class="col-fifth">
                        <a href="{{ route('admin.invoice.list') }}" class="kpi-card kpi-green">
                            <i class="mdi mdi-receipt kpi-icon"></i>
                            <div class="kpi-value">{{ $todayInvoice ?? 0 }}</div>
                            <div class="kpi-label">Invoices</div>
                            <div class="kpi-sub">₹{{ number_format($todayInvoiceAmount ?? 0) }} value</div>
                        </a>
                    </div>
                    <div class="col-fifth">
                        <a href="{{ route('admin.purchase.list') }}" class="kpi-card kpi-blue">
                            <i class="mdi mdi-truck kpi-icon"></i>
                            <div class="kpi-value">{{ $todayPurchase ?? 0 }}</div>
                            <div class="kpi-label">Purchase Orders</div>
                            <div class="kpi-sub">₹{{ number_format($todayPurchaseAmount ?? 0) }} value</div>
                        </a>
                    </div>
                    <div class="col-fifth">
                        <a href="{{ route('admin.challan.list') }}" class="kpi-card kpi-orange">
                            <i class="mdi mdi-truck-delivery kpi-icon"></i>
                            <div class="kpi-value">{{ $todayDelivery ?? 0 }}</div>
                            <div class="kpi-label">Delivery Challans</div>
                            <div class="kpi-sub">₹{{ number_format($todayDeliveryAmount ?? 0) }} value</div>
                        </a>
                    </div>
                    <div class="col-fifth">
                        <a href="{{ route('admin.quotation.list') }}" class="kpi-card kpi-purple">
                            <i class="mdi mdi-file-document-box kpi-icon"></i>
                            <div class="kpi-value">{{ $todayQuotation ?? 0 }}</div>
                            <div class="kpi-label">Quotations</div>
                            <div class="kpi-sub">₹{{ number_format($todayQuotationAmount ?? 0) }} value</div>
                        </a>
                    </div>

                </div>

                <h5 class="dash-section-title">{{ date('F') }} Snapshot</h5>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="snap-card">
                            <span class="snap-icon" style="background:#4e73df;"><i class="mdi mdi-cart-outline"></i></span>
                            <span>
                                <div class="snap-value">₹{{ number_format($monthSalesAmount ?? 0) }}</div>
                                <div class="snap-label">Monthly Sales</div>
                                <div class="snap-sub">{{ $monthSales ?? 0 }} orders</div>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="snap-card">
                            <span class="snap-icon" style="background:#e74a3b;"><i class="mdi mdi-receipt"></i></span>
                            <span>
                                <div class="snap-value">₹{{ number_format($monthInvoiceAmount ?? 0) }}</div>
                                <div class="snap-label">Monthly Invoices</div>
                                <div class="snap-sub">{{ $monthInvoice ?? 0 }} invoices</div>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="snap-card">
                            <span class="snap-icon" style="background:#36b9cc;"><i class="mdi mdi-account-multiple"></i></span>
                            <span>
                                <div class="snap-value">{{ $totcustomer ?? 0 }}</div>
                                <div class="snap-label">Total Customers</div>
                                <div class="snap-sub">{{ $monthPurchase ?? 0 }} purchase orders this month</div>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel-card">
                            <h4><i class="mdi mdi-clock"></i> Recent Sales Orders <a href="{{ route('admin.sales.list') }}" class="view-all">View all &rarr;</a></h4>
                            <div class="table-responsive">
                                <table class="table table-clean">
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
                                                        'Created' => 'status-created', 'Approved' => 'status-approved',
                                                        'Delivered' => 'status-delivered', 'Cancelled' => 'status-cancelled',
                                                    ][$so->status] ?? 'status-default';
                                                @endphp
                                                <span class="status-pill {{ $statusClass }}">{{ $so->status ?? '-' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center" style="padding:24px;color:#999;">No sales orders yet</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-card">
                            <h4><i class="mdi mdi-view-list"></i> This Month at a Glance</h4>
                            <div class="glance-row">
                                <span class="glance-label"><span class="glance-dot" style="background:#6f42c1;"></span>Quotations</span>
                                <span class="glance-value">{{ $monthQuotation ?? 0 }}</span>
                            </div>
                            <div class="glance-row">
                                <span class="glance-label"><span class="glance-dot" style="background:#e74a3b;"></span>Sales Orders</span>
                                <span class="glance-value">{{ $monthSales ?? 0 }}</span>
                            </div>
                            <div class="glance-row">
                                <span class="glance-label"><span class="glance-dot" style="background:#4e73df;"></span>Purchase Orders</span>
                                <span class="glance-value">{{ $monthPurchase ?? 0 }}</span>
                            </div>
                            <div class="glance-row">
                                <span class="glance-label"><span class="glance-dot" style="background:#f6a623;"></span>Delivery Challans</span>
                                <span class="glance-value">{{ $monthDelivery ?? 0 }}</span>
                            </div>
                            <div class="glance-row">
                                <span class="glance-label"><span class="glance-dot" style="background:#1cc88a;"></span>Invoices</span>
                                <span class="glance-value">{{ $monthInvoice ?? 0 }}</span>
                            </div>
                        </div>

                        <div class="panel-card">
                            <h4><i class="mdi mdi-bell-outline"></i> Pending Quotations</h4>
                            <div class="alert-box">
                                @if(($pendingQuotation ?? 0) > 0)
                                    <i class="mdi mdi-alert-circle alert-icon" style="color:#f6a623;"></i>
                                    <div><b>{{ $pendingQuotation }}</b> quotation(s) awaiting conversion</div>
                                    <a href="{{ route('admin.quotation.list',['status' => 'Y']) }}" style="margin-top:8px;display:inline-block;">View pending &rarr;</a>
                                @else
                                    <i class="mdi mdi-check-circle alert-icon" style="color:#1cc88a;"></i>
                                    <div>All quotations converted</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel-card">
                            <h4><i class="mdi mdi-trophy"></i> Top Products <small style="color:#b0b6c3;font-weight:400;">({{ date('F') }})</small></h4>
                            @forelse($topProducts ?? [] as $i => $p)
                                <div class="lb-row">
                                    <span class="lb-name">
                                        <span class="rank-badge {{ $i == 0 ? 'rank-1' : ($i == 1 ? 'rank-2' : ($i == 2 ? 'rank-3' : 'rank-other')) }}">{{ $i + 1 }}</span>
                                        {{ $p->product_name ?? 'Unknown' }}
                                    </span>
                                    <span class="lb-sub">{{ number_format($p->total_qty, 0) }} sold</span>
                                </div>
                            @empty
                                <p style="color:#999;">No sales recorded this month</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-card">
                            <h4><i class="mdi mdi-account-star"></i> Top Customers <small style="color:#b0b6c3;font-weight:400;">({{ date('F') }})</small></h4>
                            @forelse($topCustomers ?? [] as $i => $c)
                                <div class="lb-row">
                                    <span class="lb-name">
                                        <span class="rank-badge {{ $i == 0 ? 'rank-1' : ($i == 1 ? 'rank-2' : ($i == 2 ? 'rank-3' : 'rank-other')) }}">{{ $i + 1 }}</span>
                                        {{ $c->customer_name ?? 'Unknown' }}
                                    </span>
                                    <span class="lb-value">₹{{ number_format($c->total_amount ?? 0) }}</span>
                                </div>
                            @empty
                                <p style="color:#999;">No invoices recorded this month</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-card">
                            <h4><i class="mdi mdi-cube-outline"></i> Inventory Overview</h4>
                            <div class="inv-grid">
                                <div class="inv-cell inv-purple">
                                    <div class="inv-value">{{ $totproduct ?? 0 }}</div>
                                    <div class="inv-label">Products</div>
                                </div>
                                <div class="inv-cell inv-blue">
                                    <div class="inv-value">{{ $totcategory ?? 0 }}</div>
                                    <div class="inv-label">Categories</div>
                                </div>
                                <div class="inv-cell inv-pink">
                                    <div class="inv-value">{{ $totbrand ?? 0 }}</div>
                                    <div class="inv-label">Brands</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container -->

        </div> <!-- content -->

@endsection
