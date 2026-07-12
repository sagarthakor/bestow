@extends('admin.layout.table_master')

@section('title', 'List | Salesorder')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Sales Order List</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a>
                                </li>
                                <li>Sales</li>
                                <li class="active">List</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"></div>

                    @can('sales_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{ route('admin.sales.create') }}">Add New</a>
                        </div>
                    @endcan
                </div>

                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{ session()->get('message') }}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">
                        <div class="card-box mb-3">
                            {{ Form::open(['method' => 'get', 'class' => 'row g-2']) }}

                            <div class="col-md-2">
                                <input type="text" name="salaesorder_no" class="form-control" placeholder="SO No." value="{{ request('salaesorder_no') }}">
                            </div>

                            <div class="col-md-3">
                                <input type="text" name="client_name" class="form-control" placeholder="Client Name" value="{{ request('client_name') }}">
                            </div>

                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                <input type="date" name="to_date" class="form-control mt-1" value="{{ request('to_date') }}">
                            </div>

                            <div class="col-md-2">
                                <input type="text" name="subject" class="form-control" placeholder="Subject" value="{{ request('subject') }}">
                            </div>

                            <div class="col-md-2">
                                <input type="text" name="amount" class="form-control" placeholder="Amount" value="{{ request('amount') }}">
                            </div>

                            <div class="col-md-2">
                                @php
                                    $statuses = ['all' => 'All', 'dc_pending' => 'DC Pending', 'out_of_stock' => 'Out of Stock'];
                                @endphp
                                <select class="form-control" name="dc_status">
                                    <option value="" disabled {{ request('dc_status') ? '' : 'selected' }}>Select DC Status</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ request('dc_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-brown w-100"><i class="mdi mdi-file-find"></i> Search</button>
                                <a href="{{ route('admin.sales.list') }}" class="btn btn-brown w-100"><i class="mdi mdi-backup-restore"></i> Reset</a>
                            </div>

                            {{ Form::close() }}
                        </div>

                        <div class="card-box table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SO No.</th>
                                    <th>SO Date</th>
                                    <th>Client Name</th>
                                    <th>Subject</th>
                                    <th>Amount</th>
                                    <th>Delivery Challan</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $srno = 0; @endphp
                                @foreach($list as $row)
                                    <tr>
                                        <td>{{ ++$srno }}</td>
                                        <td>{{ $row->salaesorder_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->salaesorder_date)->format('d-m-Y') }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->subject }}</td>
                                        <td>{{ $row->grand_total }}</td>
                                        <td>
                                            @if($row->computed_status === 'dc_pending')
                                                <a href="{{ route('admin.sales.delivery.add', $row->id) }}" class="btn btn-sm btn-primary">Add</a>
                                            @elseif($row->computed_status === 'out_of_stock')
                                                <span class="badge bg-danger">Out of Stock</span>
                                                <a href="{{ route('admin.sales.delivery.add', $row->id) }}" class="btn btn-sm btn-primary">Add</a>
                                            @else
                                                <span class="badge bg-success">Done</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('sales_update')
                                                        <li><a href="{{ route('admin.sales.edit', $row->id) }}">Edit</a></li>
                                                    @endcan
                                                    @can('sales_print')
                                                        <li><a href="{{ route('admin.sales.print', $row->id) }}">Print</a></li>
                                                    @endcan
                                                    @can('sales_delete')
                                                        <li>
                                                            <a onclick="return confirm('Are you sure you want to delete this item?')" href="{{ route('admin.sales.delete', $row->id) }}">Delete</a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $list->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
