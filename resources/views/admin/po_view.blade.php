@extends('admin.layout.master_material')

@section('title', 'Purchase View')

@section('sidebar')
    @parent

@endsection

@section('content')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Purchase Order </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('client/po/list')}}">Purchase List </a>
                                </li>
                                <li class="active">
                                    Purchase View
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
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">Details</a></li>
                                <li><a data-toggle="tab" href="#menu1">PO Receive</a></li>
                                <li><a data-toggle="tab" href="#menu2">PO Invoice</a></li>
                            </ul>

                            <div class="tab-content">

                                <div id="home" class="tab-pane fade in active">

                                    <ul class="nav navbar-nav navbar-right">
                                        @can('purchase_update')
                                            <li><a href="{{url('client/po/edit/'.$po->poid)}}">Edit</a></li>
                                        @endcan
                                    </ul>

                                    <div class="">

                                        <div class="panel-body">
                                            <div class="row">

                                                <table class="table table-borderless" style="border:0px !important">
                                                    <caption style="color: #222;font-weight: 600">Organization Details</caption>
                                                    <tr>
                                                        <td style="width: 20%">Vendor Name </td>
                                                        <td style="color: #222;width: 40%"> {{$po->vendor_name}}</td>
                                                        <td style="width: 20%">Purchase Date</td>
                                                        <td style="color: #222;width: 20%">{{date('d-m-Y',strtotime($po->po_date))}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 20%">Subject </td>
                                                        <td style="color: #222;"> {{$po->subject}}</td>
                                                        <td style="width: 20%">Valid Until</td>
                                                        <td style="color: #222;"> {{date('d-m-Y',strtotime($po->due_date))}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 20%">Remarks </td>
                                                        <td style="color: #222;"> {{$po->remark}}</td>
                                                        <td style="width: 20%">Stage</td>
                                                        <td style="color: #222;"> {{$po->status}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="color: #222;font-weight: 600">
                                                            Address Details
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td  style="width: 20%">Billing Address </td>
                                                        <td colspan="3" style="color: #222;"> {{$po->billing_address}}</td>

                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 20%">Country</td>
                                                        <td style="color: #222;"> {{$country->country_name ?? ""}}
                                                        <td style="width: 20%">State </td>
                                                        <td style="color: #222;"> {{$state->state_name ?? ""}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td style="width: 20%">City</td>
                                                        <td style="color: #222;"> {{$city->city_name ?? ""}}
                                                        </td>
                                                        <td style="width: 20%">Postal Code </td>
                                                        <td style="color: #222;"> {{$po->billing_postalcode}}</td>

                                                    </tr>


                                                    <tr>
                                                        <td style="width: 20%">Shipping Address </td>
                                                        <td style="color: #222;"> {{$po->shipping_address}}</td>
                                                        <td style="width: 20%">Country</td>
                                                        <td style="color: #222;"> {{$country->country_name ?? ""}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 20%">State </td>
                                                        <td style="color: #222;"> {{$state->state_name ?? ""}}</td>
                                                        <td style="width: 20%">City</td>
                                                        <td style="color: #222;"> {{$city->city_name ?? ""}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 20%">Postal Code </td>
                                                        <td style="color: #222;"> {{$po->shipping_postalcode}}</td>

                                                    </tr>


                                                </table>


                                            </div>

                                            <div class="tabledata">
                                                <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                    <thead>
                                                    <tr>
                                                        <th>Sr</th>
                                                        <th style="text-align: center;">Product Name</th>
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
                                                        <th style="text-align: center;">SST Amt</th>
                                                        <th style="text-align: center;">IGST %</th>
                                                        <th style="text-align: center;">IGST Amt</th>
                                                        <th style="text-align: center;">Total</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $srno=$total=$gsttotal=$grand=$discount_total=0;
                                                    ?>
                                                    @foreach($poitem as $item)
                                                        <?php
                                                        $srno++;
                                                        ?>
                                                        <?php
                                                        $total=$total+$item->total;
                                                        $gsttotal=$gsttotal+$item->gst_amount;
                                                        $grand=$grand+$item->grand_total;
                                                        $discount_total=$discount_total+$item->discount_amount;
                                                        ?>
                                                        <tr id="row{{$srno}}">
                                                            <td>{{$srno}}</td>
                                                            <td style="vertical-align: top !important;width: 20%">
                                                                @php
                                                                    $variantLabel = trim(($item->value1 ?? '') . ((($item->value1 ?? '') !== '' && ($item->value2 ?? '') !== '') ? ' / ' : '') . ($item->value2 ?? ''));
                                                                @endphp
                                                                {{$item->product_name}}{{ $variantLabel !== '' ? ' ('.$variantLabel.')' : '' }}

                                                            </td>
{{--                                                            <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                {{$item->inner_diameter}}--}}
{{--                                                            </td>--}}
{{--                                                            <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                {{$item->outer_diameter}}--}}
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
                                                                {{$item->total}}
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
                                                                {{$item->gst_per}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->gst_amount}}
                                                            </td>
                                                            <td style="vertical-align: top !important;text-align: center;">
                                                                {{$item->grand_total}}
                                                            </td>


                                                        </tr>
                                                    @endforeach


                                                    </tbody>

                                                </table>
                                                <table class="table table-striped add-edit-table table-bordered" style="margin-left: 62%;width: 38%;">
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Item Total (+)</td><td style="text-align: right;">{{$po->net_amount}}</td><td></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Discount Total (-)</td><td style="text-align: right;">{{$po->discount_total ?? 0}}</td><td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">CGST Total (+)</td><td style="text-align: right;">{{$po->cgsttotal ?? 0}}</td><td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">SGST Total (+)</td><td style="text-align: right;">{{$po->sgsttotal ?? 0}}</td><td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">IGST Total (+)</td><td style="text-align: right;">{{$po->igsttotal ?? 0}}</td><td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="10" style="text-align: right;">Grand Total (+)</td><td style="text-align: right;">{{$po->grand_total ?? 0}}</td><td></td>
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
                                                    echo $po->term_condition;
                                                    ?>

                                                </div>
                                            </div>



                                        </div>
                                        <!-- end: page -->

                                    </div> <!-- end Panel -->

                                    {{Form::close()}}
                                </div> <!-- container -->

                                <div id="menu1" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-sm-4">
                                        </div>
                                        <div class="col-sm-4">
                                        </div>
                                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                                            <a class="btn btn-primary" href="{{url('inward/add/'.$po->poid)}}">Add New</a>
                                        </div>
                                    </div>
                                    <iframe style="width: 100%;height: 1000px" src="{{url('po/receive/view/'.$po->poid)}}" frameborder="0"></iframe>
                                </div>

                                <div id="menu2" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-sm-4">
                                        </div>
                                        <div class="col-sm-4">
                                        </div>
                                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                                            <a class="btn btn-primary" href="{{url('po/invoice/'.$po->poid)}}">Add New</a>
                                        </div>
                                    </div>
                                    <iframe style="width: 100%;height: 1000px" src="{{url('po/invoice/view/'.$po->poid)}}" frameborder="0"></iframe>
                                </div>


                            </div> <!-- content -->


                            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


                            <script type="text/javascript">

            $(".btnproductsearch").click(function(e){
                e.preventDefault();
            });
            $("#module").change(function(){
                var modules=$("#module").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/get_terms',
                    data:{module:modules},
                    method:'get',
                    success:function(data)
                    {
                        $("#terms").html(data);
                        ClassicEditor
                            .create( document.querySelector( '#term_condition' ) )
                            .catch( error => {
                                console.error( error );
                            } );
                    }
                })
            });

            ClassicEditor
                .create( document.querySelector( '#term_condition' ) )
                .catch( error => {
                    console.error( error );
                } );
            function getcustomer(customer)
            {
                var appurl="{{url('/')}}";

                $.ajax({
                    url:appurl+'/admin/get_customer',
                    data:{customer:customer},
                    method:'get',
                    dataType:'json',
                    success:function(data)
                    {
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

            function get_product(product,srno)
            {
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/get_product',
                    data:{product:product},
                    method:'get',
                    dataType:'json',
                    success:function(data)
                    {
                        var len=data.length;
                        if(len>0)
                        {
                            $("#qty"+srno).val("");
                            $("#price"+srno).val("");
                            $("#total_amount"+srno).val("");
                            $("#discount_per"+srno).val("");
                            $("#discount_amount"+srno).val("");
                            $("#gst_per"+srno).val("");
                            $("#gst_amount"+srno).val("");

                            $("#description"+srno).val(data[0]['description']);
                            $("#price"+srno).val(data[0]['price']);
                            $("#gst_per"+srno).val(data[0]['gst']);
                            $("#inner_diamitter"+srno).val(data[0]['inner_diameter']);
                            $("#outer_diamitter"+srno).val(data[0]['outer_diameter']);
                            $("#thikness"+srno).val(data[0]['thikness']);
                            $("#hsn"+srno).val(data[0]['hsn']);
                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }

            function get_service(product,srno)
            {
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/get_product',
                    data:{product:product},
                    method:'get',
                    dataType:'json',
                    success:function(data)
                    {
                        var len=data.length;
                        if(len>0)
                        {
                            $("#description"+srno).val(data[0]['description']);
                            $("#price"+srno).val(data[0]['price']);
                            $("#gst_per"+srno).val(data[0]['gst']);

                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }

            function cal(ele)
            {
                var rate = $(ele).closest('tr').find('.price').val();
                var qty = $(ele).closest('tr').find('.qty').val();
                var total=Number(rate)*Number(qty);

                $(ele).closest('tr').find('.total').val(Math.round(total));

                var discount_per = $(ele).closest('tr').find('.discount_per').val();

                var gst_per = $(ele).closest('tr').find('.gst_per').val();

                var discount_amount=Number(total)*Number(discount_per)/100;

                var afterdisc=Number(total)-Number(discount_amount);

                $(ele).closest('tr').find('.discount_amount').val(Math.round(discount_amount));

                // alert(gst_per);
                var gst_amount=Number(afterdisc)*Number(gst_per)/100;
                //alert(gst_amount);
                $(ele).closest('tr').find('.gst_amount').val(Math.round(gst_amount));

                var netprice=Number(afterdisc)+Number(gst_amount);

                $(ele).closest('tr').find('.netprice').val(Math.round(netprice));

                var totalss = $(".total");
                var item_total=0;
                for(var i = 0; i < totalss.length; i++){
                    item_total=Number(item_total)+Number($(totalss[i]).val());
                }

                var discount_amount = $(".discount_amount");
                var discount_total=0;
                for(var i = 0; i < discount_amount.length; i++){
                    discount_total=Number(discount_total)+Number($(discount_amount[i]).val());
                }

                var gst_amounts = $(".gst_amount");
                var gst_total=0;
                for(var i = 0; i < gst_amounts.length; i++){
                    gst_total=Number(gst_total)+Number($(gst_amounts[i]).val());
                }

                var netprices = $(".netprice");
                var netpricestotal=0;
                for(var i = 0; i < netprices.length; i++){
                    netpricestotal=Number(netpricestotal)+Number($(netprices[i]).val());
                }


                $("#item_total").val(item_total);
                $("#discount_total").val(discount_total);
                $("#gsttotal").val(gst_total);
                $("#grand_total").val(netpricestotal);

            }


            function model_close()
            {
                $("#product_model").hide();
                $("#service_model").hide();
                $("#myModal").hide();
            }

            function customer_save()
            {
                var organization_name=$("#organization_name").val();
                var website=$("#website").val();
                var primary_phone=$("#primary_phone").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/ajax_customer_save',
                    data:{organization_name:organization_name,website:website,primary_phone:primary_phone},
                    method:'get',
                    dataType:'json',
                    success:function(data)
                    {
                        var len=data.length;
                        if(len>0)
                        {
                            $("#primary_phone").val(data[0]['primary_phone']);
                            $("#website").val(data[0]['website']);
                            $("#customer").html(data[0]['str']);

                        }
                        $("#myModal").hide();
                    }
                });
            }
            function customer_form()
            {
                window.location="{{url('customer_add')}}";
            }

            function product_search(srno)
            {
                $("#product_model").show();
                $("#srid").val(srno);
            }

            function service_search(srno)
            {
                $("#service_model").show();
                $("#servicesrid").val(srno);
            }
        </script>

                            <script>


            $("#datatable-buttons").on('click','tr',function(e){
                e.preventDefault();
                var id = $(this).attr('value');
                var srno=$("#srid").val();
                get_product(id,srno)
                $("#product_model").hide();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/ajax_getproduct',
                    data:{product:id},
                    method:'get',
                    success:function(data)
                    {
                        $("#product"+srno).html(data);
                    }
                });
            });


            $("#service_datatable-buttons").on('click','tr',function(e){
                e.preventDefault();
                var id = $(this).attr('value');
                var srno=$("#servicesrid").val();
                get_service(id,srno)
                $("#service_model").hide();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/ajax_getservice',
                    data:{product:id},
                    method:'get',
                    success:function(data)
                    {
                        $("#product"+srno).html(data);
                    }
                });
            });



            $(".service_btn").click(function(e){
                e.preventDefault();
            });
            $(".product_btn").click(function(e){
                e.preventDefault();
            });
            $("#product_btn1").click(function(e){
                e.preventDefault();
            });
            function add_customer()
            {
                $("#myModal").show();
            }
            function remove_row(ele)
            {
                if(confirm("Are you sure you want to delete this?")){
                    $(ele).closest('tr').remove();

                    var totalss = $(".total");
                    var item_total=0;
                    for(var i = 0; i < totalss.length; i++){
                        item_total=Number(item_total)+Number($(totalss[i]).val());
                    }

                    var discount_amount = $(".discount_amount");
                    var discount_total=0;
                    for(var i = 0; i < discount_amount.length; i++){
                        discount_total=Number(discount_total)+Number($(discount_amount[i]).val());
                    }

                    var gst_amounts = $(".gst_amount");
                    var gst_total=0;
                    for(var i = 0; i < gst_amounts.length; i++){
                        gst_total=Number(gst_total)+Number($(gst_amounts[i]).val());
                    }

                    var netprices = $(".netprice");
                    var netpricestotal=0;
                    for(var i = 0; i < netprices.length; i++){
                        netpricestotal=Number(netpricestotal)+Number($(netprices[i]).val());
                    }


                    $("#item_total").val(item_total);
                    $("#discount_total").val(discount_total);
                    $("#gsttotal").val(gst_total);
                    $("#grand_total").val(netpricestotal);
                }
                else{
                    return false;
                }

            }
            $(".rowremove").click(function(){
                // alert("sdd");
                $(this).parent().parent().remove();
            });
        </script>

                            <script type="text/javascript">
            $.noConflict();
            jQuery(document).ready(function ($) {
                $( "#quot_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
                $( "#valid_until" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            });
        </script>
@endsection
