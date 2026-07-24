@extends('admin.layout.master_material')

@section('title', 'Sales Order View')

@section('sidebar')
    @parent
@endsection

@section('content')

    <style type="text/css">
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            border-top:0px !important;
        }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Sales Order </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li class="active">Sales Order View</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="panel">

                    <div class="panel-body">
                        <div class="row">
                            <table class="table table-borderless" style="border:0px !important">
                                <tr>
                                    <td style="width: 20%">
                                        Customer Name
                                    </td>
                                    <td style="color: #222;"> {{$customer->customer_name}}</td>

                                    <td style="width: 20%">
                                        Subject
                                    </td>
                                    <td style="color: #222;">
                                        {{$data->subject}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">Quotation No</td>
                                    <td style="color: #222;">{{$data->quotation_no}}</td>
                                    <td style="width: 20%">Quotation Date</td>
                                    <td style="color: #222;"> {{date('d-m-Y',strtotime($data->quot_date))}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">So No.</td>
                                    <td style="color: #222;">{{$data->salaesorder_no}}</td>
                                    <td style="width: 20%">PO No.</td>
                                    <td style="color: #222;">{{$data->purchase_order}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">Sales Order Date</td>
                                    <td style="color: #222;">{{date('d-m-Y',strtotime($data->salaesorder_date))}}</td>
                                    <td style="width: 20%">Due Date </td>
                                    <td style="color: #222;">{{date('d-m-Y',strtotime($data->due_date))}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">Status</td>
                                    <td style="color: #222;">{{$data->status}}</td>
                                    <td style="width: 20%">Remark</td>
                                    <td style="color: #222;">{{$data->remark}}</td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="color: #222;font-weight: 600">
                                        Address Details
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">Billing Address </td>
                                    <td style="color: #222;"> {{$data->billing_address}}</td>
                                    <td style="width: 20%">Country</td>
                                    <td style="color: #222;"> {{$data->billing_country}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">State </td>
                                    <td style="color: #222;"> {{$data->billing_state}}</td>
                                    <td style="width: 20%">City</td>
                                    <td style="color: #222;"> {{$data->billing_city}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">Postal Code </td>
                                    <td style="color: #222;"> {{$data->billing_postalcode}}</td>
                                </tr>

                                <tr>
                                    <td style="width: 20%">Shipping Address </td>
                                    <td style="color: #222;"> {{$data->shipping_address}}</td>
                                    <td style="width: 20%">Country</td>
                                    <td style="color: #222;"> {{$data->shipping_country}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">State </td>
                                    <td style="color: #222;"> {{$data->shipping_state}}</td>
                                    <td style="width: 20%">City</td>
                                    <td style="color: #222;"> {{$data->shipping_city}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 20%">Postal Code </td>
                                    <td style="color: #222;"> {{$data->shipping_postalcode}}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="tabledata">
                            <table class="table table-striped table-bordered" id="caltable">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Product Name</th>
                                        <th style="text-align: center;">Qty</th>
                                        <th style="text-align: center;">Price</th>
                                        <th style="text-align: center;">Total</th>
                                        <th style="text-align: center;">Disc %</th>
                                        <th style="text-align: center;">Disc Amt</th>
                                        <th style="text-align: center;">GST %</th>
                                        <th style="text-align: center;">GST Amt</th>
                                        <th style="text-align: center;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotitem as $item)
                                    <tr>
                                        @php
                                            $itemVariantLabel = trim(($item->value1 ?? '') . ((($item->value1 ?? '') !== '' && ($item->value2 ?? '') !== '') ? ' / ' : '') . ($item->value2 ?? ''));
                                        @endphp
                                        <td style="vertical-align: top !important;width: 20%">{{$item->product_name}}{{ $itemVariantLabel !== '' ? ' ('.$itemVariantLabel.')' : '' }}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->qty}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->price}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->total}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->discount_per}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->discount_amount}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->gst_per}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->gst_amount}}</td>
                                        <td style="vertical-align: top !important;text-align: center;">{{$item->grand_total}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" style="text-align: right;">Item Total</td>
                                        <td style="text-align: right;">{{$data->net_amount}}</td><td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" style="text-align: right;">GST Total</td>
                                        <td style="text-align: right;">{{$data->gst_amount}}</td><td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" style="text-align: right;">Grand Total</td>
                                        <td style="text-align: right;">{{$data->grand_total}}</td><td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-sm-12">
                            <h3>Terms & Conditions</h3>
                        </div>

                        <div class="col-md-12" id="terms">
                            {!! $data->term_condition !!}
                        </div>
                    </div>
                    <!-- end: page -->
                </div> <!-- end Panel -->
            </div> <!-- container -->
        </div> <!-- content -->
    </div>

@endsection
