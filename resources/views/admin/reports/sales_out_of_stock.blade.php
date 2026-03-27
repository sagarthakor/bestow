@extends('admin.layout.master')

@section('title', 'Out Of Stock Report')

@section('content')

    <style>
        .filter-card {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .summary-box {
            padding: 15px;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 15px;
        }

        .bg-danger-soft { background: #e74c3c; }
        .bg-info-soft { background: #3498db; }

        .table th {
            background: #f8f9fa;
            text-align: center;
        }

        .table td {
            vertical-align: middle !important;
        }

        .negative {
            color: #e74c3c;
            font-weight: bold;
        }

        .positive {
            color: #27ae60;
            font-weight: bold;
        }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <!-- Page Title -->
                <div class="page-title-box">
                    <h4>📦 Out Of Stock Report</h4>
                </div>

                {{ Form::model(request(), ['method' => 'get']) }}

                <!-- 🔥 FILTER CARD -->
                <div class="filter-card">
                    <div class="row">

                        <div class="col-md-2">
                            <label>SO No</label>
                            <input type="text" name="order_no" value="{{ request('order_no') }}" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Customer</label>
                            <input type="text" name="customer" value="{{ request('customer') }}" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Product</label>
                            <input type="text" name="product" value="{{ request('product') }}" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>To Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                        </div>

                        <div class="col-md-2" style="margin-top:25px;">
                            <button class="btn btn-success btn-block">Search</button>
                        </div>

                        <div class="col-md-2" style="margin-top:10px;">
                            <a href="{{ url()->current() }}" class="btn btn-danger btn-block">
                                Reset
                            </a>
                        </div>

                    </div>

                    <div class="row mt-2" style="margin-top: 10px">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary" name="export_excel" value="export_excel">
                                ⬇ Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 🔥 SUMMARY -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="summary-box bg-info-soft">
                            <h5>Total Records</h5>
                            <h3>{{ $list->total() }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="summary-box bg-danger-soft">
                            <h5>Out Of Stock</h5>
                            <h3>{{ $list->count() }}</h3>
                        </div>
                    </div>
                </div>

                <!-- 🔥 TABLE -->
                <div class="card-box table-responsive">

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>SO No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Sold</th>
                            <th>Stock</th>
                            <th>Balance</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($list as $data)
                            <tr>
                                <td>
                                    {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                </td>

                                <td><b>{{ $data->order_no }}</b></td>

                                <td>
                                    {{ \Carbon\Carbon::parse($data->order_date)->format('d M Y') }}
                                </td>

                                <td>{{ $data->customer }}</td>

                                <td>{{ $data->product }}</td>

                                <td>{{ number_format($data->sold_qty, 2) }}</td>

                                <td>{{ number_format($data->stock_qty, 2) }}</td>

                                <td class="{{ $data->balance <= 0 ? 'negative' : 'positive' }}">
                                    {{ number_format($data->balance, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">
                                    🚫 No Out Of Stock Items Found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    {{ $list->appends(request()->input())->links() }}

                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

@endsection
