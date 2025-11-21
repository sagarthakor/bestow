@extends('admin.layout.master')

@section('title', 'View | DeliveryChallan')

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
                            <h4 class="page-title">DeliveryChallan </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li>
                                    <a href="{{url('deliverychallan/list')}}">DeliveryChallan List </a>
                                </li>
                                <li>
                                    Delivery Challan View
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">
                            <ul class="nav nav-tabs" style="border-bottom:none">
                                <li class="active"><a data-toggle="tab" href="#home">Details</a></li>

                                <ul class="nav navbar-nav navbar-right">
                                    @can('delivery_challan_update')
                                        <li><a href="{{url('deliverychallan/edit/'.$data->id)}}">Edit</a></li>
                                    @endcan

                                </ul>
                            </ul>
                            {{Form::model($data,['method'=>'post','route'=>'post.quot_update'])}}
                            {{Form::hidden('id',null)}}
                            {{Form::hidden('quot_no',$data->quot_no)}}

                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="panel">

                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Organization Details</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Customer Name</label>
                                                        <div class="col-sm-6">
                                                            {{$data->customer_name}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Quote No</label>
                                                        <div class="col-sm-6">
                                                            {{$data->quotation_no}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Quote Date</label>
                                                        <div class="col-sm-6">
                                                            @if($data->quot_date)
                                                                {{date('d-m-Y',strtotime($data->quot_date))}}
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Contact Name</label>
                                                        <div class="col-sm-6">
                                                            {{$data->contactname}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">SO No.</label>
                                                        <div class="col-sm-6">
                                                            {{$data->salaesorder_no}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">SO Date</label>
                                                        <div class="col-sm-6">
                                                            @if($data->salaesorder_date)
                                                                {{date('d-m-Y',strtotime($data->salaesorder_date))}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Subject</label>
                                                        <div class="col-sm-6">
                                                            {{$data->subject}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">PO No.</label>
                                                        <div class="col-sm-6">
                                                            {{$data->purchase_order}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">PO Date.</label>
                                                        <div class="col-sm-6">
                                                            {{$data->purchase_order_date}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Status</label>
                                                        <div class="col-sm-6">
                                                            {{$data->status}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">DC No.</label>
                                                        <div class="col-sm-6">
                                                            {{$data->challan_number}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">DC Date</label>
                                                        <div class="col-sm-6">
                                                            {{date('d-m-Y',strtotime($data->invoice_date))}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">


                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Transporter Name</label>
                                                        <div class="col-sm-6">
                                                            {{$data->transport_name}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Receipt No.</label>
                                                        <div class="col-sm-6">
                                                            {{$data->receipt_no}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <?php
                                                        if(isset($data->receipt_date))
                                                        {
                                                            $receipt_date=date('d-m-Y',strtotime($data->receipt_date));
                                                        }else{
                                                            $receipt_date="";
                                                        }

                                                        ?>
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Receipt Date</label>
                                                        <div class="col-sm-6">
                                                            {{$receipt_date}}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Payment Terms</label>
                                                        <div class="col-sm-6">
                                                            Net {{$data->payment_terms}} Days
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-sm-6 col-form-label">Remark</label>
                                                        <div class="col-sm-6">
                                                            {{$data->remark}}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">


                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3>Address Details</h3>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Billing Address</label><br>{{$data->billing_address}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <br>{{$data->billing_country}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <br>
                                                                {{$data->billing_state}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>City</label>
                                                                <br>{{$data->billing_city}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Postal Code</label>
                                                                <br>
                                                                {{$data->billing_postalcode}}

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Shipping Address</label><br>{{$data->shipping_address}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <br>{{$data->shipping_country}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <br>
                                                                {{$data->shipping_state}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>City</label>
                                                                <br>{{$data->shipping_city}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Postal Code</label>
                                                                <br>
                                                                {{$data->shipping_postalcode}}

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="tabledata">
                                                <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                    <thead>
                                                    <tr>
                                                        <th>Sr</th>
                                                        <th style="text-align: center;">Product Name</th>
                                                        <th style="text-align: center;">Photo</th>
{{--                                                        <th style="text-align: center;">ID</th>--}}
{{--                                                        <th style="text-align: center;">OD</th>--}}
{{--                                                        <th style="text-align: center;">THK</th>--}}
                                                        <th style="text-align: center;">HSN</th>
                                                        <th style="text-align: center;">Qty</th>
                                                        <th style="text-align: center;">Price</th>
                                                        <th style="text-align: center;">Total</th>
                                                        <th style="text-align: center;">Disc %</th>
                                                        <th style="text-align: center;">Disc Amt</th>
                                                        <th style="text-align: center;">CGST %</th>
                                                        <th style="text-align: center;">CGST Amt</th>
                                                        <th style="text-align: center;">SGST %</th>
                                                        <th style="text-align: center;">SGST Amt</th>
                                                        <th style="text-align: center;">IGST %</th>
                                                        <th style="text-align: center;">IGST Amt</th>
                                                        <th style="text-align: center;">Total</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $srno = $total = $gsttotal = $grand = $discount_total = 0;
                                                    ?>
                                                    @foreach($item as $item)
                                                        <?php
                                                        $srno++;
                                                        ?>
                                                        <?php
                                                        $total = $total + $item->amount;
                                                        $gsttotal = $gsttotal + $item->gst_amount;
                                                        $grand = $grand + $item->grand_total;
                                                        $discount_total = $discount_total + $item->discount_amount;
                                                        ?>
                                                        <tr id="row{{$srno}}">
                                                            <td>{{$srno}}</td>
                                                            <td style="vertical-align: top !important;width: 20%">
                                                                {{$item->product_name}}

                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                    <a target="_blank" href="{{asset('public/product_image/'.$item->product_image)}}"><img src="{{asset('public/product_image/'.$item->product_image)}}" style="height: 55px" width="55px"></a>
                                                </td>
{{--                                                            <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                {{$item->inner_diamitter}}--}}
{{--                                                            </td>--}}
{{--                                                            <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                {{$item->outer_diamitter}}--}}
{{--                                                            </td>--}}
{{--                                                            <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                {{$item->thikness}}--}}
{{--                                                            </td>--}}
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->hsn}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->qty}}
                                                            </td>

                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->price}}
                                                            </td>


                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->total_amount}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->discount_per}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->discount_amount}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->cgst_per}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->cgst_amount}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->sgst_per}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->sgst_amount}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->igst_per}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->igst_amount}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->net_price}}
                                                            </td>


                                                        </tr>
                                                    @endforeach


                                                    </tbody>

                                                </table>
                                                <table class="table table-striped add-edit-table table-bordered"
                                                       style="margin-left: 62%;width: 38%;">
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Item Total (+)</td>
                                                        <td style="text-align: right;">{{$data->item_total ?? 0}}</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Discount Total (-)</td>
                                                        <td style="text-align: right;">{{$data->discount_total ?? 0}}</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">CGST Total (+)</td>
                                                        <td style="text-align: right;">{{$data->cgsttotal ?? 0}}</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">SGST Total (+)</td>
                                                        <td style="text-align: right;">{{$data->sgsttotal ?? 0}}</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">IGST Total (+)</td>
                                                        <td style="text-align: right;">{{$data->igsttotal ?? 0}}</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Adjustment (+)</td>
                                                        <td style="text-align: right;">{{$data->adjustment ?? 0}}</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Grand Total (+)</td>
                                                        <td style="text-align: right;">{{$data->grand_total ?? 0}}</td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="col-sm-12">

                                                <h3>Terms & Conditions</h3>

                                            </div>


                                            <div class="col-md-12" id="terms">
                                                <div class="form-group">
                                                    <label>Terms & Conditions</label>
                                                    <?php
                                                    echo $data->term_condition;
                                                    ?>

                                                </div>
                                            </div>


                                        </div>
                                        <!-- end: page -->

                                    </div> <!-- end Panel -->

                                    {{Form::close()}}
                                </div> <!-- container -->

                            </div> <!-- content -->


                        </div>


                        <!-- MODAL -->

                        <!-- end Modal -->

                        <div class="modal" id="myModal" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <a type="button" class="close" onclick="model_close()">&times;</a>
                                        <h4 class="modal-title">Quick Create Organization</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Organization Name</label>
                                                    <input type="text" class="form-control" name="organization_name"
                                                           id="organization_name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Website</label>
                                                    <input type="text" class="form-control" name="website" id="website">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Primary Phone</label>
                                                    <input type="text" class="form-control" name="primary_phone" id="primary_phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a type="button" onclick="customer_form()" class="btn btn-default">Go to full form</a>
                                        <a type="button" onclick="customer_save()" class="btn btn-primary">Save</a>
                                        <a type="button" class="btn btn-default" onclick="model_close()">Close</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal" id="product_model" role="dialog" style="width: 100% !important">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" onclick="model_close()">&times;</button>
                                        <h4 class="modal-title">Products</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="card-box table-responsive">
                                                    <input type="hidden" name="srid" id="srid">
                                                    <table style="width: 100% !important" id="datatable-buttons"
                                                           class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>

                                                            <th>Product Name</th>

                                                            <th>UOM</th>
                                                            <th>Price</th>
                                                            <th>GST</th>

                                                        </tr>
                                                        </thead>


                                                        <tbody>
                                                        {{--                                            @foreach($product as $serarchprod)--}}
                                                        {{--                                                <tr value="{{$serarchprod->id}}">--}}
                                                        {{--                                                    <td style="width: 10%">{{$serarchprod->product_name}}</td>--}}
                                                        {{--                                                    <td>{{$serarchprod->uom_name}}</td>--}}
                                                        {{--                                                    <td>{{$serarchprod->price}}</td>--}}
                                                        {{--                                                    <td>{{$serarchprod->gst_per}}</td>--}}
                                                        {{--                                                </tr>--}}
                                                        {{--                                            @endforeach--}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal" id="service_model" role="dialog" style="width: 100% !important">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" onclick="model_close()">&times;</button>
                                        <h4 class="modal-title">Services</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="card-box table-responsive">
                                                    <input type="hidden" name="servicesrid" id="servicesrid">
                                                    <table style="width: 100% !important" id="service_datatable-buttons"
                                                           class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>

                                                            <th>Service Name</th>

                                                            <th>UOM</th>
                                                            <th>Price</th>
                                                            <th>GST</th>

                                                        </tr>
                                                        </thead>


                                                        <tbody>
                                                        {{--                                            @foreach($service as $serarchservice)--}}
                                                        {{--                                                <tr value="{{$serarchservice->id}}">--}}
                                                        {{--                                                    <td style="width: 10%">{{$serarchservice->product_name}}</td>--}}
                                                        {{--                                                    <td>{{$serarchservice->uom_name}}</td>--}}
                                                        {{--                                                    <td>{{$serarchservice->price}}</td>--}}
                                                        {{--                                                    <td>{{$serarchservice->gst_per}}</td>--}}
                                                        {{--                                                </tr>--}}
                                                        {{--                                            @endforeach--}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ============================================================== -->
                        <!-- End Right content here -->
                        <!-- ============================================================== -->

                        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


                        <script type="text/javascript">

                $(".btnproductsearch").click(function (e) {
                    e.preventDefault();
                });
                $("#module").change(function () {
                    var modules = $("#module").val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/admin/get_terms',
                        data: {module: modules},
                        method: 'get',
                        success: function (data) {
                            $("#terms").html(data);
                            ClassicEditor
                                .create(document.querySelector('#term_condition'))
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    })
                });

                ClassicEditor
                    .create(document.querySelector('#term_condition'))
                    .catch(error => {
                        console.error(error);
                    });

                function getcustomer(customer) {
                    var appurl = "{{url('/')}}";

                    $.ajax({
                        url: appurl + '/admin/get_customer',
                        data: {customer: customer},
                        method: 'get',
                        dataType: 'json',
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {
                                $("#billing_address").text(data[0]['billing_address']);
                                $("#shipping_address").text(data[0]['shipping_address']);
                                $("#billing_pobox").val(data[0]['billing_pobox']);
                                $("#shipping_pobox").val(data[0]['shipping_pobox']);
                                $("#billing_city").val(data[0]['billing_city']);
                                $("#shipping_city").val(data[0]['shipping_city']);
                                $("#billing_state").val(data[0]['billing_state']);
                                $("#shipping_state").val(data[0]['shipping_state']);
                                $("#billing_postalcode").val(data[0]['billing_postalcode']);
                                $("#shipping_postalcode").val(data[0]['shipping_postalcode']);
                                $("#billing_country").val(data[0]['billing_country']);
                                $("#shipping_country").val(data[0]['shipping_country']);
                            }
                        }
                    });
                }

                function get_product(product, srno) {
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/admin/get_product',
                        data: {product: product},
                        method: 'get',
                        dataType: 'json',
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {
                                $("#qty" + srno).val("");
                                $("#price" + srno).val("");
                                $("#total_amount" + srno).val("");
                                $("#discount_per" + srno).val("");
                                $("#discount_amount" + srno).val("");
                                $("#gst_per" + srno).val("");
                                $("#gst_amount" + srno).val("");

                                $("#description" + srno).val(data[0]['description']);
                                $("#price" + srno).val(data[0]['price']);
                                $("#gst_per" + srno).val(data[0]['gst']);
                                $("#inner_diamitter" + srno).val(data[0]['inner_diameter']);
                                $("#outer_diamitter" + srno).val(data[0]['outer_diameter']);
                                $("#thikness" + srno).val(data[0]['thikness']);
                                $("#hsn" + srno).val(data[0]['hsn']);
                                //$("#make"+srno).val(data[0]['make']);
                            }
                        }
                    });
                }

                function get_service(product, srno) {
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/admin/get_product',
                        data: {product: product},
                        method: 'get',
                        dataType: 'json',
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {
                                $("#description" + srno).val(data[0]['description']);
                                $("#price" + srno).val(data[0]['price']);
                                $("#gst_per" + srno).val(data[0]['gst']);

                                //$("#make"+srno).val(data[0]['make']);
                            }
                        }
                    });
                }

                function cal(ele) {
                    var rate = $(ele).closest('tr').find('.price').val();
                    var qty = $(ele).closest('tr').find('.qty').val();
                    var total = Number(rate) * Number(qty);

                    $(ele).closest('tr').find('.total').val(Math.round(total));

                    var discount_per = $(ele).closest('tr').find('.discount_per').val();

                    var gst_per = $(ele).closest('tr').find('.gst_per').val();

                    var discount_amount = Number(total) * Number(discount_per) / 100;

                    var afterdisc = Number(total) - Number(discount_amount);

                    $(ele).closest('tr').find('.discount_amount').val(Math.round(discount_amount));

                    // alert(gst_per);
                    var gst_amount = Number(afterdisc) * Number(gst_per) / 100;
                    //alert(gst_amount);
                    $(ele).closest('tr').find('.gst_amount').val(Math.round(gst_amount));

                    var netprice = Number(afterdisc) + Number(gst_amount);

                    $(ele).closest('tr').find('.netprice').val(Math.round(netprice));

                    var totalss = $(".total");
                    var item_total = 0;
                    for (var i = 0; i < totalss.length; i++) {
                        item_total = Number(item_total) + Number($(totalss[i]).val());
                    }

                    var discount_amount = $(".discount_amount");
                    var discount_total = 0;
                    for (var i = 0; i < discount_amount.length; i++) {
                        discount_total = Number(discount_total) + Number($(discount_amount[i]).val());
                    }

                    var gst_amounts = $(".gst_amount");
                    var gst_total = 0;
                    for (var i = 0; i < gst_amounts.length; i++) {
                        gst_total = Number(gst_total) + Number($(gst_amounts[i]).val());
                    }

                    var netprices = $(".netprice");
                    var netpricestotal = 0;
                    for (var i = 0; i < netprices.length; i++) {
                        netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                    }


                    $("#item_total").val(item_total);
                    $("#discount_total").val(discount_total);
                    $("#gsttotal").val(gst_total);
                    $("#grand_total").val(netpricestotal);

                }


                function model_close() {
                    $("#product_model").hide();
                    $("#service_model").hide();
                    $("#myModal").hide();
                }

                function customer_save() {
                    var organization_name = $("#organization_name").val();
                    var website = $("#website").val();
                    var primary_phone = $("#primary_phone").val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/client/ajax_customer_save',
                        data: {organization_name: organization_name, website: website, primary_phone: primary_phone},
                        method: 'get',
                        dataType: 'json',
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {
                                $("#primary_phone").val(data[0]['primary_phone']);
                                $("#website").val(data[0]['website']);
                                $("#customer").html(data[0]['str']);

                            }
                            $("#myModal").hide();
                        }
                    });
                }

                function customer_form() {
                    window.location = "{{url('customer_add')}}";
                }

                function product_search(srno) {
                    $("#product_model").show();
                    $("#srid").val(srno);
                }

                function service_search(srno) {
                    $("#service_model").show();
                    $("#servicesrid").val(srno);
                }
            </script>

                        <script>


                $("#datatable-buttons").on('click', 'tr', function (e) {
                    e.preventDefault();
                    var id = $(this).attr('value');
                    var srno = $("#srid").val();
                    get_product(id, srno)
                    $("#product_model").hide();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/client/ajax_getproduct',
                        data: {product: id},
                        method: 'get',
                        success: function (data) {
                            $("#product" + srno).html(data);
                        }
                    });
                });


                $("#service_datatable-buttons").on('click', 'tr', function (e) {
                    e.preventDefault();
                    var id = $(this).attr('value');
                    var srno = $("#servicesrid").val();
                    get_service(id, srno)
                    $("#service_model").hide();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/client/ajax_getservice',
                        data: {product: id},
                        method: 'get',
                        success: function (data) {
                            $("#product" + srno).html(data);
                        }
                    });
                });

                {{--$("#add_product").click(function (e) {--}}
                            {{--    e.preventDefault();--}}
                            {{--    var i = $("#totrow").val();--}}
                            {{--    i++;--}}

                            {{--    var data = "<tr id='row" + i + "'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_product(this.value," + i + ")' name='product[]' id='product" + i + "'> <option>select</option>@foreach($product as $prod)<option value='{{$prod->id}}'>{{$prod->product_name}}</option>@endforeach</select><div class='input-group-btn'><a class='btn btn-default product_btn'  onclick='product_search(" + i + ")'><img src='<?=asset('public/product_icon.png');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='form-control'></textarea></div></td>";--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control" id="inner_diamitter' + i + '"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control" id="outer_diamitter' + i + '"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control" id="thikness' + i + '"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="form-control" id="hsn' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty' + i + '"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price' + i + '" oninput="cal(this)"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" class="discount_per form-control" oninput="cal(this)" id="discount_per' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per' + i + '" oninput="cal(this)"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price' + i + '"></td>';--}}
                            {{--    data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';--}}

                            {{--    $("#caltable").append(data);--}}

                            {{--    $("#totrow").val(i);--}}
                            {{--});--}}


                            {{--$("#add_service").click(function (e) {--}}
                            {{--    e.preventDefault();--}}
                            {{--    var i = $("#totrow").val();--}}
                            {{--    i++;--}}

                            {{--    var data = "<tr id='row" + i + "'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_service(this.value," + i + ")' name='product[]' id='product" + i + "'> <option>select</option>@foreach($service as $prod)<option value='{{$prod->id}}'>{{$prod->product_name}}</option>@endforeach</select><div class='input-group-btn'><a class='service_btn btn btn-default' onclick='service_search(" + i + ")'><img src='<?=asset('public/service_icon.jpg');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='form-control'></textarea></div></td>";--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty' + i + '"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price' + i + '"></td>';--}}
                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount' + i + '"></td>';--}}

                            {{--    data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price' + i + '"></td>';--}}
                            {{--    data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="cursor:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';--}}

                            {{--    $("#caltable").append(data);--}}

                            {{--    $("#totrow").val(i);--}}
                            {{--});--}}

                            $(".service_btn").click(function (e) {
                                e.preventDefault();
                            });
                            $(".product_btn").click(function (e) {
                                e.preventDefault();
                            });
                            $("#product_btn1").click(function (e) {
                                e.preventDefault();
                            });

                            function add_customer() {
                                $("#myModal").show();
                            }

                            function remove_row(ele) {
                                if (confirm("Are you sure you want to delete this?")) {
                                    $(ele).closest('tr').remove();

                                    var totalss = $(".total");
                                    var item_total = 0;
                                    for (var i = 0; i < totalss.length; i++) {
                                        item_total = Number(item_total) + Number($(totalss[i]).val());
                                    }

                                    var discount_amount = $(".discount_amount");
                                    var discount_total = 0;
                                    for (var i = 0; i < discount_amount.length; i++) {
                                        discount_total = Number(discount_total) + Number($(discount_amount[i]).val());
                                    }

                                    var gst_amounts = $(".gst_amount");
                                    var gst_total = 0;
                                    for (var i = 0; i < gst_amounts.length; i++) {
                                        gst_total = Number(gst_total) + Number($(gst_amounts[i]).val());
                                    }

                                    var netprices = $(".netprice");
                                    var netpricestotal = 0;
                                    for (var i = 0; i < netprices.length; i++) {
                                        netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                                    }


                                    $("#item_total").val(item_total);
                                    $("#discount_total").val(discount_total);
                                    $("#gsttotal").val(gst_total);
                                    $("#grand_total").val(netpricestotal);
                                } else {
                                    return false;
                                }

                            }

                            $(".rowremove").click(function () {
                                // alert("sdd");
                                $(this).parent().parent().remove();
                            });
</script>

                        <script type="text/javascript">
                $.noConflict();
                jQuery(document).ready(function ($) {
                    $("#quot_date").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'dd-mm-yy'
                    });
                    $("#valid_until").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'dd-mm-yy'
                    });
                });
            </script>
@endsection
