@extends('admin.layout.table_master_v2')

@section('title', 'v2 Layout Preview')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Sales Orders',
        'items' => [
            ['label' => 'Sales | Purchase', 'url' => null],
            ['label' => 'Sales Orders', 'url' => null],
        ],
    ])
    @section('breadcrumb-actions')
        <a href="#" class="btn btn-secondary btn-sm"><i class="mdi mdi-export"></i> Export</a>
        <a href="#" class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i> New Sales Order</a>
    @endsection
@endsection

@section('content')

    <div class="alert alert-info mb-4">
        <span class="alert-icon"><i class="mdi mdi-information-outline"></i></span>
        <div>
            <div class="alert-title">This is a layout preview, not a real page</div>
            This route (<code>/admin/v2-preview</code>) only exists to review the new sidebar, topbar, breadcrumb, footer, search and notification panel end-to-end with your real session/permissions. The content below is sample data — no module pages have been migrated yet.
        </div>
    </div>

    <div class="row g-3 mb-2">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <span class="stat-icon"><i class="mdi mdi-cart-outline"></i></span>
                <span><span class="stat-value d-block">1,042</span><span class="stat-label">Sales Orders</span></span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <span class="stat-icon" style="background: var(--color-success);"><i class="mdi mdi-receipt"></i></span>
                <span><span class="stat-value d-block">959</span><span class="stat-label">Invoices</span></span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <span class="stat-icon" style="background: var(--color-warning);"><i class="mdi mdi-clock-alert"></i></span>
                <span><span class="stat-value d-block">47</span><span class="stat-label">Pending Quotations</span></span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <span class="stat-icon" style="background: var(--color-danger);"><i class="mdi mdi-truck-delivery"></i></span>
                <span><span class="stat-value d-block">961</span><span class="stat-label">Delivery Challans</span></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Recent Sales Orders</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>SO No</th><th>Customer</th><th>Date</th><th class="text-end">Amount</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="#">SO-262700275</a></td><td>Dapz Fabrics LLP</td><td>29-05-2026</td>
                        <td class="text-end">₹2,835</td><td><span class="badge badge-soft-primary">Created</span></td>
                        <td><div class="table-actions"><button class="btn btn-ghost btn-icon btn-sm"><i class="mdi mdi-pencil"></i></button><button class="btn btn-ghost btn-icon btn-sm"><i class="mdi mdi-delete-outline"></i></button></div></td>
                    </tr>
                    <tr>
                        <td><a href="#">SO-262700274</a></td><td>Shree Sadgurukrupa Dresses</td><td>29-05-2026</td>
                        <td class="text-end">₹7,128</td><td><span class="badge badge-soft-success">Approved</span></td>
                        <td><div class="table-actions"><button class="btn btn-ghost btn-icon btn-sm"><i class="mdi mdi-pencil"></i></button><button class="btn btn-ghost btn-icon btn-sm"><i class="mdi mdi-delete-outline"></i></button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
