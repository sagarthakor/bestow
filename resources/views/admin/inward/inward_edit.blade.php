@extends('admin.layout.master_material')

@section('title', 'Add Inward')

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
                            <h4 class="page-title">Inward</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
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


                {{Form::model($item,['method'=>'post','route'=>'post.stock_update'])}}
                {{Form::hidden("id",null)}}
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
                        <!-- <div class="col-md-4" style="display: none;" id="qno">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation No</label>
                                                                    {{Form::text('quot_no',null,['class'=>'form-control','id'=>"quot_no"])}}

                            </div>
                        </div> -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <?php
                                    $inward_from=[''=>'select an Option','Customer Material Inward\n(Customer Place)'=>'Customer Material Inward(Customer Place)','Buy Back Material Inward(Customer Place)'=>'Buy Back Material Inward(Customer Place)','3rd Party Material Inward(Third Party Vendor/Service Center)'=>'3rd Party Material Inward(Third Party Vendor/Service Center)','Demo Material Inward(Returnable Basis)'=>'Demo Material Inward(Returnable Basis)']
                                    ?>
                                    <label class="control-label">Inward From<span style="color: red">*</span></label>
                                {{Form::select('inward_from',$inward_from,null,['required','class'=>'form-control js-example-basic-single','id'=>'inward_from'])}}

                                <!--   <div class="input-group-btn" style="top: 10px">
     <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_customer()"></span>
    </div> -->
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label">Customer</label>
                                {{Form::select('customer',$customer,null,['class'=>'form-control js-example-basic-single','id'=>'customer'])}}

                                <!--   <div class="input-group-btn" style="top: 10px">
     <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_customer()"></span>
    </div> -->
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Inward Date <span style="color: red">*</span></label>
                                    {{Form::text('inward_date',null,['required','class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Type Of Inward</label>
                                    <?php
                                    $inwardtype=[$item->inward_type ?? ""=>$item->inward_type ?? "select inward type",'Returnable'=>'Returnable','Non Returnable'=>'Non Returnable','New Material Supplied'=>'New Material Supplied','New Material Purchased'=>'New Material Purchased'];
                                    ?>
                                    {{Form::select('inward_type',$inwardtype,null,['class'=>'form-control js-example-basic-single','id'=>"inward_type"])}}

                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes (For Internal Use)</label>
                                    {{Form::text('remark',null,['class'=>'form-control'])}}

                                </div>
                            </div>

                            <!--   <div class="col-sm-6">
                                  <div class="m-b-30">
                                      <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></button>
                                  </div>
                              </div> -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" id="poitem">
                                </div>
                                <div class="table-responsive" id="poitem1">
                                    <table class="table table-bordered-brown">
                                        <tr>
                                            <td>Product</td>

                                            <td>Received Qty</td>

                                        </tr>

                                        <tr>
                                            <td><input type="hidden" value="{{$item->product}}" name="product"/> <x-product-name :row="$item" /></td>

                                            <td style="vertical-align: top !important;text-align: center;">
                                                <input type="text" name="received"  value="{{$item->received_qty}}" class="received form-control" id="qty' . $srno . '">
                                            </td>

                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- end: page -->

                </div> <!-- end Panel -->
                <div class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <button style="text-align: center;" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                {{Form::close()}}
            </div> <!-- container -->

        </div> <!-- content -->


        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


        <script type="text/javascript">

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
            })
            function getpo(vendor)
            {

                var appurl="{{url('/')}}";

                $.ajax({
                    url:appurl+'/ajax/get_po',
                    data:{vendor:vendor},
                    method:'get',
                    success:function(data)
                    {
                        $("#purchase").html(data);
                    }
                });
            }

            function get_item(purchase_no)
            {
                var purchase=$("#purchase").val();
                //alert(purchase);
                if(purchase=="")
                {
                    $("#poitem1").show();
                    $("#poitem").hide();
                }else{
                    var appurl="{{url('/')}}";

                    $.ajax({
                        url:appurl+'/ajax/get_po_item',
                        data:{purchase_no:purchase_no},
                        method:'get',
                        success:function(data)
                        {
                            $("#poitem").html(data);
                            $("#poitem1").hide();
                            $("#poitem").show();
                        }
                    });
                }

            }
            function get_product(product,srno)
            {
                var customer=$("#customer").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/get_po_product',
                    data:{product:product,customer:customer},
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
                var qty = $(ele).closest('tr').find('.qty').val();
                var received = $(ele).closest('tr').find('.received').val();

                var remain=Number(qty)-Number(received);
                if (remain >= 0) {
                    $(ele).closest('tr').find('.remaining').val(remain);
                }else{
                    alert("do not enter more qty");
                    $(ele).closest('tr').find('.received').val(0);
                    $(ele).closest('tr').find('.remaining').val(qty);
                }

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
                get_product(id,srno)
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
                }else{
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
