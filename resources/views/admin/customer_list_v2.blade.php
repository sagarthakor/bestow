@extends('admin.layout.table_master_v2')

@section('title', 'Customer List')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Customers',
        'items' => [
            ['label' => 'Organizations', 'url' => null],
            ['label' => 'Customer', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <x-v2.table-card title="All Customers" :add-url="route('admin.v2.customer.add')">
        <x-slot name="toolbar">
            <form method="get" class="row g-3 w-100">
                <div class="col-md-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" value="{{ request('customer_name') }}" class="form-control" placeholder="Search name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Office Phone</label>
                    <input type="text" name="primary_phone" value="{{ request('primary_phone') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Office Email</label>
                    <input type="text" name="primary_email" value="{{ request('primary_email') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ request('owner_name') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Owner Mobile</label>
                    <input type="text" name="owner_mobile" value="{{ request('owner_mobile') }}" class="form-control">
                </div>
                <div class="col-md-9 d-flex align-items-end justify-content-end gap-2">
                    <a href="{{ route('admin.v2.customer.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="mdi mdi-magnify"></i> Search</button>
                </div>
            </form>
        </x-slot>

        <table data-v2-datatable data-paging="false" class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>City</th>
                    <th>Office Phone</th>
                    <th>Office Email</th>
                    <th>Owner Name</th>
                    <th>Owner Mobile</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cdata as $data)
                    <tr>
                        <td>{{ ($cdata->currentPage() - 1) * $cdata->perPage() + $loop->iteration }}</td>
                        <td><a href="{{ route('admin.customer.preview', ['id' => $data->id]) }}">{{ $data->customer_name }}</a></td>
                        <td>{{ $data->city_name ?? '-' }}</td>
                        <td>{{ $data->primary_phone }}</td>
                        <td><a href="mailto:{{ $data->primary_email }}">{{ $data->primary_email }}</a></td>
                        <td>{{ $data->owner_name }}</td>
                        <td>{{ $data->owner_mobile }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="table-empty"><span>📭</span>No customers found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </x-v2.table-card>

    <div class="mt-3">
        {{ $cdata->appends(request()->input())->links() }}
    </div>

@endsection
