@extends('admin.layout.master_material')

@section('title', 'Report | Socks Products Without Formula')

@section('sidebar')
    @parent
@endsection

@section('content')

    <style type="text/css">
        nav { float: right; }
        .rpt-panel-heading {
            background: #188ae2;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 16px;
            border-radius: 3px 3px 0 0;
        }
        .rpt-panel-body { padding: 18px 16px 6px; }
        .rpt-panel-body label { font-weight: 600; color: #555; font-size: 12.5px; margin-bottom: 4px; }
        .rpt-actions { padding: 0 16px 16px; text-align: right; border-top: 1px solid #eceff5; margin-top: 12px; padding-top: 14px; }
        table.rpt-table thead th { background: #f4f6fa; font-weight: 600; color: #444; border-bottom: 2px solid #e3e6ee; vertical-align: middle; }
        table.rpt-table { border: 1px solid #e3e6ee; border-collapse: collapse; box-shadow: 0 1px 3px rgba(20,30,60,.04); }
        table.rpt-table th, table.rpt-table td { border: 1px solid #eceff5; padding: 10px 12px; vertical-align: middle; }
        table.rpt-table tbody tr:hover { background-color: #f5f8fc; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Socks Products Without Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Socks Products Without Formula</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                {{ Form::model(request(), ['method' => 'get']) }}

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="rpt-panel-heading">Filter</div>
                            <div class="rpt-panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Product</label>
                                        <input type="text" name="product" value="{{ request('product') }}" class="form-control" placeholder="Product name">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Subcategory</label>
                                        <select name="subcategory" class="form-control">
                                            <option value="">All Subcategories</option>
                                            @foreach($subcategories as $sc)
                                                <option value="{{ $sc->id }}" {{ request('subcategory') == $sc->id ? 'selected' : '' }}>{{ $sc->subcategory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="rpt-actions">
                                <a href="{{ url()->current() }}" class="btn btn-default">Reset</a>
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="submit" name="export_excel" value="export_excel" class="btn btn-success">Export Excel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box" style="padding:0;">

                            @include('admin.reports.partials.stat_cards', ['stats' => [
                                ['label' => 'Products Without Formula', 'value' => number_format($totalMissing), 'icon' => 'mdi-alert-circle', 'color' => 'red'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Item Code</th>
                                        <th>UOM</th>
                                        <th>Subcategory</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $data->product_name }}</td>
                                            <td>{{ $data->item_code ?? '-' }}</td>
                                            <td>{{ $data->uom ?? '-' }}</td>
                                            <td>{{ $data->subcategory_name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center" style="padding:30px;color:#999;">Every Socks product has a formula</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div style="padding: 10px 16px;">
                                {{ $list->appends(request()->input())->links() }}
                            </div>

                        </div>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

@endsection
