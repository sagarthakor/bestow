@extends('admin.layout.master_material')

@section('title', 'Add Inward')

@section('sidebar')
    @parent

@endsection

@section('content')
    <style>
        table th {
            text-align: left !important;
        }
    </style>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Received</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin/index') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    Purchase List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                {{Form::model($data,['method'=>'post','route'=>'post.received_purchase'])}}

                <div class="panel">

                    <div class="panel-body">
                        <div class="row">
                            @if ($errors->any())
                                <div class="col-xs-12">
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12" id="msg" style="display:none;">
                                    <div class="alert alert-danger">
                                        <strong>Quantity is more than remaining qty </strong>
                                    </div>
                                </div>
                                <div class="col-md-6"  id="qno">
                                    <div class="form-group">
                                        <label class="control-label">Purchase No</label>
                                        {{Form::text('purchase_no',null,['class'=>'form-control','id'=>"quot_no"])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Received Date <span style="color: red">*</span></label>
                                        {{Form::text('received_date',date('d-m-Y'),['class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Item & Description</th>
                                            <th>Ordered</th>
                                            <th>Total Received</th>
                                            <th>Remaining Qty</th>
                                            <th>Quantity to Receive</th>
                                        </tr>

                                        @foreach($poitem as $item)
                                            @if($item->status != "service")
                                                <tr>
                                                    <td style="width: 50%;">
                                                        <input type="hidden" name="item[]" value="{{$item->product}}">
                                                        {{$item->product_name}}</td>
                                                    <td><input type="text" readonly name="order[]" class="form-control order" value="{{$item->qty}}">{{$item->uom_name}}</td>
                                                    <td><input type="text" name="received[]" class="form-control received" readonly value="{{$item->received_qty ?? 0}}">{{$item->uom_name}}</td>
                                                    <td><input type="text" class="form-control remain_qty" readonly id="remain_qty" name="remain_qty[]" value="{{$item->remain_qty ?? $item->qty}}">{{$item->uom_name}}</td>
                                                    <td style="text-align: right"><input type="text" oninput="cal(this)" class="form-control qty_received"  name="qty_received[]" value="0" >{{$item->uom_name}}<br><span class="error_msg" style="color:red"></span></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>

                                </div>

                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes (For Internal Use)</label>
                                    {{Form::text('note',null,['class'=>'form-control'])}}

                                </div>
                            </div>

                            <!--   <div class="col-sm-6">
                                  <div class="m-b-30">
                                      <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></button>
                                  </div>
                              </div> -->
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12" style="text-align: center;">
                            <button style="text-align: center;"  class="btn btn-primary btnsave">Submit</button>
                        </div>
                    </div>
                    <!-- end: page -->

                </div> <!-- end Panel -->


                {{Form::close()}}
                <div class="panel">

                    <div class="panel-body">

                        <div class="col-md-12 ">
                            <div class="alert alert-info">
                                <strong>Purchase Received <Record></Record></strong>
                            </div>
                            <table class="table table-sm table-dark">
                                <!--<tr style='background-color: rgba(0,0,0,.075);'>-->
                                <!--    <th>Purchase No.</th>-->
                                <!--    <th>Received Date</th>-->
                                <!--    <th>Remark</th>-->

                                <!--</tr>-->
                                <?php
                                echo $received_item;
                                ?>
                                {{--                        @foreach($received_item as $ritem)--}}
                                {{--                            <tr>--}}
                                {{--                                <td>{{date('d-m-Y',strtotime($ritem->receive_date))}}</td>--}}
                                {{--                                <td style="width: 60%;">{{$ritem->product_name}}</td>--}}
                                {{--                                <td>{{$ritem->order_qty}}</td>--}}
                                {{--                                <td>{{$ritem->qty_received}}</td>--}}
                                {{--                            </tr>--}}
                                {{--                        @endforeach--}}
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->


        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


        <script type="text/javascript">
            $(document).ready(function(){
                var totalss = $(".qty_received");

                var item_total = 0;
                for (var i = 0; i < totalss.length; i++) {
                item_total = Number(item_total) + Number($(totalss[i]).val());
            }
               // alert(item_total);
            var totalremain_qty = $(".remain_qty");
            var item_total1 = 0;
            for (var i = 0; i < totalremain_qty.length; i++) {
                item_total1 = Number(item_total1) + Number($(totalremain_qty[i]).val());
            }

            if (item_total > item_total1) {
                $("#msg").show();

                // $(ele).closest('tr').find('.qty_received').val(0);
                $(".btnsave").hide();
            }else{
                $("#msg").hide();
                $(".btnsave").show();
            }
            if(item_total==0)
            {
                $(".btnsave").hide();
            }else{
                $(".btnsave").show();
            }
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
            })

            function getpo(vendor) {

                var appurl = "{{url('/')}}";

                $.ajax({
                    url: appurl + '/ajax/get_po',
                    data: {vendor: vendor},
                    method: 'get',
                    success: function (data) {
                        $("#purchase").html(data);
                    }
                });
            }

            function get_item(purchase_no) {
                var purchase = $("#purchase").val();
                //alert(purchase);
                if (purchase == "") {
                    $("#poitem1").show();
                    $("#poitem").hide();
                } else {
                    var appurl = "{{url('/')}}";

                    $.ajax({
                        url: appurl + '/ajax/get_po_item',
                        data: {purchase_no: purchase_no},
                        method: 'get',
                        success: function (data) {
                            $("#poitem").html(data);
                            $("#poitem1").hide();
                            $("#poitem").show();
                        }
                    });
                }

            }

            function get_product(product, srno) {
                var customer = $("#customer").val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_po_product',
                    data: {product: product, customer: customer},
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

                var qty_received = $(ele).closest('tr').find('.qty_received').val();
                var remain_qty = $(ele).closest('tr').find('.remain_qty').val();
                var error_msg = $(ele).closest('tr').find('.error_msg').text();
                if (Number(qty_received) > Number(remain_qty)) {
                    $("#msg").show();
                    $(ele).closest('tr').find('.error_msg').text("big qty");
                    // $(ele).closest('tr').find('.qty_received').val(0);
                    $(".btnsave").hide();
                }else{
                    $(".btnsave").show();
                    $("#msg").hide();
                    $(ele).closest('tr').find('.error_msg').text("");
                }
                var totalss = $(".qty_received");
                var totalremain_qty = $(".remain_qty");
                var item_total = 0;
                var item_total1 = 0;
                var receivedCount=0;
                var error=0;
                for (var i = 0; i < totalss.length; i++) {
                    item_total = Number($(totalss[i]).val());
                    receivedCount =Number(receivedCount)+Number($(totalss[i]).val());
                    item_total1 = Number($(totalremain_qty[i]).val());
                    if (Number(item_total) > Number(item_total1)){
                        error++;
                    }
                }

                if(Number(error) > 0){
                    $("#msg").show();
                    $(".btnsave").hide();
                }else{
                    $("#msg").hide();
                    $(".btnsave").show();
                }


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
                get_product(id, srno)
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
