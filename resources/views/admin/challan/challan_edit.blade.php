<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App title -->
    <title>{{Session::get('software_title')}} - Delivery Challan Update</title>
    <style>

    </style>
    <!-- Plugin Css-->
    @extends('admin.form_header')
    <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>

</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
@include('admin/side_bar')
<!-- Left Sidebar End -->



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
                            <h4 class="page-title">Quotation </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">Zircos</a>
                                </li>
                                <li>
                                    <a href="{{url('quotation-list')}}">Quotation List </a>
                                </li>
                                <li class="active">
                                    Quotation Add
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                {{Form::model($data,['method'=>'post','route'=>'post.challan_update','role'=>'form','data-parsley-validate novalidate'])}}
                {{Form::hidden('id',null)}}
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


                            </div>
                        </div> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Challan No.</label>
                                        {{Form::text('challan_number',null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Customer Name <span style="color: red">*</span></label>

                                        {{Form::select('customer',$customer,null,['required','class'=>'form-control js-example-basic-single'. $errors->first('customer', ' error'),'id'=>'customer','onchange'=>'getcustomer(this.value)'])}}

                                    </div>
                                    @if ($errors->has('customer'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="control-label">Challan Date <span style="color: red">*</span></label>
                                        {{Form::text('challan_date',date('d-m-Y'),['required','class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact Name <span style="color: red">*</span></label>
                                        {{Form::select('contact_name',$contact_name,null,['required','class'=>'form-control js-example-basic-single','id'=>"contact_name"])}}

                                    </div>
                                </div>





                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Valid Until <span style="color: red">*</span></label>
                                        {{Form::text('valid_date',Date('d-m-Y', strtotime('+14 days'))  ,['required','class'=>'form-control input-daterange-datepicker','id'=>"valid_until",'autocomplete'=>'off'])}}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Subject <span style="color: red">*</span></label>
                                        {{Form::text('subject',null,['required','class'=>'form-control','id'=>"subject"])}}

                                    </div>
                                </div>




                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $stage=array('Created'=>'Created','Sent'=>'Sent','Reviewing'=>'Reviewing','QuoteRivision'=>'QuoteRivision','Accepted'=>'Accepted','Invoiced'=>'Invoiced','Canceled'=>'Canceled');
                                        ?>
                                        <label class="control-label">Challan Stage <span style="color: red">*</span> </label>
                                        {{Form::select('challan_stage',$stage,null,['required','class'=>'form-control js-example-basic-single','id'=>'quot_stage'])}}


                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sales Order</label>

                                        {{Form::select('sales_order',$sales_order,null,['class'=>'form-control js-example-basic-single','id'=>'sales_order','onchange'=>'get_so_item(this.value)'])}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quotation</label>
                                        {{Form::select('quotation',$quotation,null,['class'=>'form-control js-example-basic-single','id'=>'quotation','onchange'=>'get_quot_item(this.value)'])}}
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        {{Form::text('remark',null,['class'=>'form-control'])}}

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">

                                <h3>Address Details</h3>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Billing Address <span style="color: red">*</span></label>
                                        {{Form::textarea('billing_address',null,['required','class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'billing_address'])}}

                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Country <span style="color: red">*</span></label>

                                        {{Form::text('billing_country',null,['required','class'=>'form-control','id'=>"billing_country"])}}

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>State <span style="color: red">*</span></label>

                                        {{Form::text('billing_state',null,['required','class'=>'form-control','id'=>"billing_state"])}}

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>City  <span style="color: red">*</span></label>

                                        {{Form::text('billing_city',null,['required','class'=>'form-control','id'=>"billing_city"])}}

                                    </div>
                                </div>



                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Postal Code <span style="color: red">*</span></label>

                                        {{Form::text('billing_postalcode',null,['required','class'=>'form-control','id'=>"billing_postalcode"])}}

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping Address <span style="color: red">*</span></label>
                                        {{Form::textarea('shipping_address',null,['required','class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'shipping_address'])}}

                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Country <span style="color: red">*</span></label>
                                        {{Form::text('shipping_country',null,['required','class'=>'form-control','id'=>'shipping_country'])}}

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>State <span style="color: red">*</span></label>
                                        {{Form::text('shipping_state',null,['required','class'=>'form-control','id'=>'shipping_state'])}}

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>City <span style="color: red">*</span></label>
                                        {{Form::text('shipping_city',null,['required','class'=>'form-control','id'=>'shipping_city'])}}

                                    </div>
                                </div>





                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Postal Code <span style="color: red">*</span></label>
                                        {{Form::text('shipping_postalcode',null,['required','class'=>'form-control','id'=>'shipping_postalcode'])}}

                                    </div>
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
                                <div class="table-responsive">
                                    <?php
                                    echo $str;
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">

                            <h3>Terms & Conditions</h3>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Terms</label>
                                {{Form::select('module',$terms,null,['class'=>'form-control','id'=>'module','required'])}}
                            </div>
                        </div>
                        <div class="col-md-12" id="terms">
                            <div class="form-group">
                                <label>Terms & Conditions</label>
                                {{Form::textarea('term_condition',null,['class'=>'form-control','cols'=>'1','rows'=>'1','id'=>'term_condition','required'])}}

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

        <footer class="footer text-right">
            2020 © Demo.
        </footer>

    </div>


    <!-- MODAL -->

    <!-- end Modal -->

    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->

    <!-- /Right-bar -->

</div>
<!-- END wrapper -->



@extends("admin.form_fotter")
<script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script><script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{asset('public/adminpanel/plugins/parsleyjs/parsley.min.js')}}"></script>


<script type="text/javascript">
    $(document).ready(function() {

        ClassicEditor
            .create( document.querySelector( '#term_condition' ) )
            .catch( error => {
                console.error( error );
            } );
        $('form').parsley();
        // /getdate("Sd");
    });
    $(function () {
        $('#demo-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.alert-info').toggleClass('hidden', !ok);
            $('.alert-warning').toggleClass('hidden', ok);
        })
            .on('form:submit', function () {
                return false; // Don't submit form for this demo
            });
    });

    $( "#quot_date" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'mm/dd/yy',
        onClose: function(){
            getdate($(this).val());
        }
    });
    $( "#valid_until" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy'
    });


    var cust=$("#customer").val();
    getcustomer(cust);


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

        //getcontact(customer);
        //getso(customer);
        //getquot(customer);
        //get_so_item(0);
        //get_quot_item(0);
    }
    function getcontact(customer)
    {
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/admin/get_contact',
            data:{customer:customer},
            method:'get',
            success:function(data)
            {
                $("#contact_name").html(data);
            }
        });
    }

    function getso(customer)
    {
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/get_so',
            data:{customer:customer},
            method:'get',
            success:function(data)
            {
                $("#sales_order").html(data);
            }
        });
    }
    function getquot(customer)
    {
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/get_quot',
            data:{customer:customer},
            method:'get',
            success:function(data)
            {
                $("#quotation").html(data);
            }
        });
    }

    function get_so_item(sono)
    {
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/get_sales_item',
            data:{sono:sono},
            method:'get',
            success:function(data)
            {
                $("#itemrow").html(data);
            }
        });
    }

    function get_quot_item(quot)
    {
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/get_quot_item',
            data:{quot:quot},
            method:'get',
            success:function(data)
            {
                $("#itemrow").html(data);
            }
        });
    }

    function get_product(product,srno)
    {
        var customer=$("#customer").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/admin/get_product',
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
                    $("#discount_per"+srno).val(data[0]['discper']);
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
                    $("#discount_per"+srno).val(data[0]['discper']);

                    //$("#make"+srno).val(data[0]['make']);
                }
            }
        });
    }

    function get_bom(product,srno)
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
                    $("#description"+srno).html(data[0]['description']);


                    $("#price"+srno).val(data[0]['price']);
                    $("#gst_per"+srno).val(data[0]['gst']);
                    $("#inner_diamitter"+srno).val(data[0]['inner_diameter']);
                    $("#outer_diamitter"+srno).val(data[0]['outer_diameter']);
                    $("#thikness"+srno).val(data[0]['thikness']);
                    $("#hsn"+srno).val(data[0]['hsn']);
                    $("#discount_per"+srno).val(data[0]['discper']);

                    var htmlString=data[0]['description'];

                    var stripedHtml = $("#description"+srno).html(htmlString).text();

                }
            }
        });
    }

    function cal(ele)
    {
        var outward_qty = $(ele).closest('tr').find('.outward_qty').val();
        var qty = $(ele).closest('tr').find('.qty').val();
        var remain=Number(qty)-Number(outward_qty);

        if(qty < outward_qty)
        {
            var outward_qty = $(ele).closest('tr').find('.outward_qty').val(0);
            $(ele).closest('tr').find('.remaining_qty').val(Math.round(0));
            $(ele).closest('tr').find('.outward_qty').effect( "shake" );
            $(ele).closest('tr').find('.msg').show();

        }else{
            $(ele).closest('tr').find('.remaining_qty').val(Math.round(remain));
            $(ele).closest('tr').find('.msg').hide();
        }



    }


    function model_close()
    {
        $("#product_model").hide();
        $("#service_model").hide();
        $("#bom_model").hide();
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
    function bom_search(srno)
    {
        $("#bom_model").show();
        $("#bomsrid").val(srno);
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

    $("#bom_datatable-buttons").on('click','tr',function(e){
        e.preventDefault();
        var id = $(this).attr('value');
        var srno=$("#bomsrid").val();
        get_product(id,srno)
        $("#bom_model").hide();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/ajax_getbom',
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
    function getdate(qdate) {
        //alert(qdate);
        var tt = document.getElementById('quot_date').value;
        var date =new Date(tt);
        var newdate = new Date(date);
        newdate.setDate(newdate.getDate() + 14);

        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();

        var someFormattedDate = dd + '-' + mm + '-' + y;
        document.getElementById('valid_until').value = someFormattedDate;

        var ndate=tt.split('/');
        var nmm=ndate[0];
        var ndd=ndate[1];
        var nyy=ndate[2];

        var newquotdate=ndd + '-' + nmm + '-' + nyy;

        $("#quot_date").val(newquotdate);
    }

    $.noConflict();
    jQuery(document).ready(function ($) {
        $( "#quot_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm/dd/yy',
            onClose: function(){
                getdate($(this).val());
            }
        });
        $( "#valid_until" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });
    });



</script>
</body>
</html>
