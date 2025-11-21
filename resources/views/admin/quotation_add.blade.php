@extends('admin.layout.master')

@section('title', 'Add New | Quotation')

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
                            <h4 class="page-title">Quotation </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('quotation-list')}}">Quotation List </a>
                                </li>
                                <li class="active">
                                    Quotation Create
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                {{Form::open(['method'=>'post','route'=>'admin.quotation.save'])}}
                {{ Form::hidden("master_serach_product",$master_serach_product ?? "") }}
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
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="control-label">Customer Name <span style="color: red">*</span></label>
                                    <div class="input-group">

                                        {{Form::select('customer',$customer,null,['required','class'=>'form-control js-example-basic-single'. $errors->first('customer', ' error'),'id'=>'customer','onchange'=>'getcustomer(this.value)'])}}
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-plus"
                                                                           onclick="add_customer()"></i></span>
                                    </div>
                                    @if ($errors->has('customer'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="control-label">Quotation Date <span
                                                    style="color: red">*</span></label>
                                        {{Form::text('quot_date',date('d-m-Y'),['required',"id"=>"quot_date",'class'=>'form-control input-daterange-datepicker','autocomplete'=>'off'])}}

                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Payment Terms <span style="color: red">*</span></label>
                                        <select onchange="getduedate(this.value)" class="form-control payment_terms" name="payment_terms">
                                            <?php
                                            echo $payment_terms ?? "";
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Valid Until <span
                                                    style="color: red">*</span></label>
                                        {{Form::text('valid_until',Date('d-m-Y', strtotime('+14 days'))  ,['required','class'=>'form-control input-daterange-datepicker','id'=>"valid_until",'autocomplete'=>'off'])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact Name <span
                                                    style="color: red">*</span></label>
                                        {{Form::select('contact_name',[''=>'select contact'],null,['required','class'=>'form-control','id'=>"contact_name"])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Subject <span style="color: red">*</span></label>
                                        {{Form::text('subject',null,['required','class'=>'form-control','id'=>"subject"])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');
                                        ?>
                                        <label class="control-label">Quote Stage <span style="color: red">*</span>
                                        </label>
                                        {{Form::select('quot_stage',$stage,null,['required','class'=>'form-control js-example-basic-single','id'=>'quot_stage'])}}


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sales Person</label>
                                        {{Form::select('salesman_id',$salesMan,null,['class'=>'form-control js-example-basic-single','id'=>'salesman'])}}

                                    </div>
                                </div>

                                <div class="col-md-12">
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
                                        <label>City <span style="color: red">*</span></label>

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
                        <div id='loader' class="loader" style='display: none;'>

                        </div>
                        <div class="tabledata">
                            <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                <thead>
                                <tr>
                                    <th style="text-align: center;">Bar Code</th>
                                    <th style="text-align: center;">Product Name</th>
                                    {{--                                    <th style="text-align: center;">ID</th>--}}
                                    {{--                                    <th style="text-align: center;">OD</th>--}}
                                    {{--                                    <th style="text-align: center;">THK</th>--}}
                                    <th style="text-align: center;">Photo</th>
                                    <th style="text-align: center;">HSN</th>
                                    <th colspan="2" style="text-align: center;">Qty</th>

                                    <th style="text-align: center;">Unit Price</th>
                                    <th style="text-align: center;">Total</th>


                                    <th style="text-align: center;">Net Price</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(empty($str))
                                {
                                    ?>

                                <?php
                                }else{
                                ?>
                                <input type="hidden" id="totrow" value="<?=$totproduct ?? ''?>">

                                <?php
                                echo $str;

                                }
                                ?>


                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="15">
                                        <div class="col-md-2">
                                            <a class="btn btn-default" id="add_product">+ Add Product</a>
                                        </div>

                                        <div class="col-md-2">
                                            <a class="btn btn-default" id="add_service">+ Add Service</a>
                                        </div>

                                        <div class="col-md-2">
                                            <a class="btn btn-default" id="add_bom">+ Add BOM</a>
                                        </div>


                                    </td>
                                </tr>

                                </tfoot>
                            </table>
                            <table class="table table-striped add-edit-table table-bordered"
                                   style="margin-left: 62%;width: 38%;">
                                <tr>
                                    <td colspan="12" style="text-align: right;">Item Total (+)</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control item_total"
                                                                                      name="item_total" id="item_total"
                                                                                      value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="12" style="text-align: right;">Discount Total (-)</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control discount_total"
                                                                                      name="discount_total"
                                                                                      id="discount_total" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="12" style="text-align: right;">CGST Total (+)</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control cgsttotal"
                                                                                      name="cgsttotal" id="cgsttotal"
                                                                                      value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="12" style="text-align: right;">SGST Total (+)</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control sgsttotal"
                                                                                      name="sgsttotal" id="sgsttotal"
                                                                                      value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="12" style="text-align: right;">IGST Total (+)</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control igsttotal"
                                                                                      name="igsttotal" id="igsttotal"
                                                                                      value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="12" style="text-align: right;">Adjustment</td>
                                    <td style="text-align: right;" colspan="2"><input type="text" class="form-control adjustment"
                                                                                      name="adjustment" id="adjustment"
                                                                                      value="0"></td>

                                </tr>
                                <tr>
                                    <td colspan="12" style="text-align: right;">Grand Total (+)</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control grand_total"
                                                                                      name="grand_total"
                                                                                      id="grand_total" value="0"></td>
                                </tr>
                            </table>

                        </div>

                        <div class="col-sm-12">

                            <h3>Terms & Conditions</h3>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Payment Terms <span style="color: red">*</span></label>
                                {{Form::select('module',$module,null,['class'=>'form-control','id'=>'module','required'])}}
                            </div>
                        </div>
                        <div class="col-md-12" id="terms">


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
                                    <input type="text" class="form-control" name="organization_name" id="organization_name">
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

                                <div class="card-box table-responsive" style="height: 800px !important;overflow: scroll;">
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
                                        @foreach($product as $serarchprod)
                                            <tr value="{{$serarchprod->id}}">
                                                <td value="{{$serarchprod->id}}"
                                                    style="width: 10%">{{$serarchprod->product_name}}</td>
                                                <td value="{{$serarchprod->id}}">{{$serarchprod->uom_name}}</td>
                                                <td value="{{$serarchprod->id}}">{{$serarchprod->price}}</td>
                                                <td value="{{$serarchprod->id}}">{{$serarchprod->gst_per}}</td>
                                            </tr>
                                        @endforeach
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

                                <div class="card-box table-responsive" style="height: 800px !important;overflow: scroll;">
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
                                        @foreach($service as $serarchservice)
                                            <tr value="{{$serarchservice->id}}">
                                                <td style="width: 10%">{{$serarchservice->product_name}}</td>
                                                <td>{{$serarchservice->uom_name}}</td>
                                                <td>{{$serarchservice->price}}</td>
                                                <td>{{$serarchservice->gst_per}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="modal" id="bom_model" role="dialog" style="width: 100% !important">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="model_close()">&times;</button>
                        <h4 class="modal-title">BOM</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card-box table-responsive" style="height: 800px !important;overflow: scroll;">
                                    <input type="hidden" name="bomsrid" id="bomsrid">
                                    <table style="width: 100% !important" id="bom_datatable-buttons"
                                           class="table table-striped table-bordered">
                                        <thead>
                                        <tr>

                                            <th>BOM Name</th>

                                            <th>UOM</th>
                                            <th>Price</th>
                                            <th>GST</th>

                                        </tr>
                                        </thead>


                                        <tbody>
                                        @foreach($bom as $serarchservice)
                                            <tr value="{{$serarchservice->id}}">
                                                <td style="width: 10%">{{$serarchservice->product_name}}</td>
                                                <td>{{$serarchservice->uom_name}}</td>
                                                <td>{{$serarchservice->price}}</td>
                                                <td>{{$serarchservice->gst_per}}</td>
                                            </tr>
                                        @endforeach
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


        <script src="/admin/assets/js/jquery.min.js"></script>
        <script type="text/javascript">

        function search_product(ele){
                    $(ele).closest('tr').find('.product option').remove();
                    var itemname= $(ele).closest('tr').find('.itemname').val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url:appurl+'/api/get_product',
                        data:{itemname:itemname},
                        method:'get',
                        success:function(response){

                            $(ele).closest('tr').find('.product').html(response);

                            get_product(ele);


                        }
                    });
                }

            function getduedate(days)
            {
                var salaesorder_date=$("#quot_date").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/getinvoiceduedate',
                    data:{salaesorder_date:salaesorder_date,days:days},
                    method:'get',
                    success:function (res)
                    {
                        $("#valid_until").val(res);
                    }
                });
            }

            $(document).ready(function () {



                $(".adjustment").on("input", function(){
                    var item_total = $("#item_total").val();
                    var discount_total = $('#discount_total').val();
                    var cgsttotal = $('#cgsttotal').val();
                    var sgsttotal = $('#sgsttotal').val();
                    var gsttotal = $('#igsttotal').val();
                    var adjustment = $('#adjustment').val();
                    var grand_total1=Number(item_total)-Number(discount_total)+Number(cgsttotal)+Number(sgsttotal)+Number(gsttotal);
                    var grand_total=Number(item_total)-Number(discount_total)+Number(cgsttotal)+Number(sgsttotal)+Number(gsttotal)+Number(adjustment);

                    if(adjustment==".")
                    {
                        $('#grand_total').val(grand_total1.toFixed(2));
                    }else{
                        $('#grand_total').val(grand_total.toFixed(2));
                    }

                    // if(grand_total == "NaN")
                    // {
                    //     $('#grand_total').val(grand_total1.toFixed(2));
                    // }else{
                    //     $('#grand_total').val(grand_total.toFixed(2));
                    // }

                });
                        {{-- $('form').parsley();
                        // /getdate("Sd");

                    $(function () {
                    $('#demo-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.alert-info').toggleClass('hidden', !ok);
                    $('.alert-warning').toggleClass('hidden', ok);
                    })
                    .on('form:submit', function () {
                                return false; // Don't submit form for this demo
                            });
                    }); --}}

                var cust = $("#customer").val();
                getcustomer(cust);


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

                            $(".payment_terms").html(data[0]['pterms']);
                            $("#valid_until").val(data[0]['duedate']);
                        }
                    }
                });

                getcontact(customer);
            }

            function getcontact(customer) {
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_contact',
                    data: {customer: customer},
                    method: 'get',
                    success: function (data) {
                        $("#contact_name").html(data);
                    }
                });
            }

            function get_product(ele) {
                var customer = $("#customer").val();
                if(customer==""){
                    alert("please select customer first");
                }else{

                    $(ele).closest('tr').find('.description').val("");

                    $(ele).closest('tr').find('.price').val(0);

                    $(ele).closest('tr').find('.gst').val(0);

                    $(ele).closest('tr').find('.cgst_per').val(0);
                    $(ele).closest('tr').find('.cgst_amount').val(0);
                    $(ele).closest('tr').find('.gst_amount').val(0);
                    $(ele).closest('tr').find('.sgst_per').val(0);
                    $(ele).closest('tr').find('.sgst_amount').val(0);
                    $(ele).closest('tr').find('.gst_per').val(0);
                    $(ele).closest('tr').find('.gst_amount').val(0);

                    $(ele).closest('tr').find('.inner_diameter').val("");

                    $(ele).closest('tr').find('.outer_diameter').val("");

                    $(ele).closest('tr').find('.thikness').val("");

                    $(ele).closest('tr').find('.hsn').val("");
                    $(ele).closest('tr').find('.hsnSpan').text("");

                    $(ele).closest('tr').find('.uom').val("");
                    $(ele).closest('tr').find('.uomSpan').text("");

                    $(ele).closest('tr').find('.discount_per').val(0);
                    $(ele).closest('tr').find('.qty').val(0);
                    $(ele).closest('tr').find('.photo').attr("src","");
                    var product = $(ele).closest('tr').find('.product').val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/admin/get_product',
                        data: {product: product, customer: customer},
                        method: 'get',
                        dataType: 'json',
                        beforeSend: function(){
                            $("#loader").show();
                        },
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {

                                $(ele).closest('tr').find('.description').val(data[0]['description']);

                                $(ele).closest('tr').find('.price').val(data[0]['price']);

                                $(ele).closest('tr').find('.gst').val(data[0]['gst']);
                                $(ele).closest('tr').find('.igst_per').val(data[0]['gst']);
                                $(ele).closest('tr').find('.gst_per').val(data[0]['gst']);

                                $(ele).closest('tr').find('.cgst_per').val(data[0]['cgst']);

                                $(ele).closest('tr').find('.sgst_per').val(data[0]['sgst']);

                                $(ele).closest('tr').find('.inner_diameter').val(data[0]['inner_diameter']);

                                $(ele).closest('tr').find('.outer_diameter').val(data[0]['outer_diameter']);

                                $(ele).closest('tr').find('.thikness').val(data[0]['thikness']);

                                $(ele).closest('tr').find('.hsn').val(data[0]['hsn']);
                                $(ele).closest('tr').find('.hsnSpan').text(data[0]['hsn']);
                                $(ele).closest('tr').find('.stockQty').text("Stock : "+data[0]['stockqty']);

                                $(ele).closest('tr').find('.uom').val(data[0]['uom']);
                                $(ele).closest('tr').find('.uomSpan').text(data[0]['uom']);

                                $(ele).closest('tr').find('.discount_per').val(data[0]['discper']);
                                $(ele).closest('tr').find('.photo').attr("src",data[0]['product_image']);
                            }
                        },
                        complete:function(data){
                            // Hide image container
                            $("#loader").hide();
                        }
                    });
                }

            }

            function get_service(ele) {
                var customer = $("#customer").val();
                if(customer==""){
                    alert("please select customer first");
                }else{

                    $(ele).closest('tr').find('.description').val("");

                    $(ele).closest('tr').find('.price').val(0);

                    $(ele).closest('tr').find('.gst').val(0);

                    $(ele).closest('tr').find('.cgst_per').val(0);
                    $(ele).closest('tr').find('.cgst_amount').val(0);
                    $(ele).closest('tr').find('.gst_amount').val(0);
                    $(ele).closest('tr').find('.sgst_per').val(0);
                    $(ele).closest('tr').find('.sgst_amount').val(0);
                    $(ele).closest('tr').find('.gst_per').val(0);
                    $(ele).closest('tr').find('.gst_amount').val(0);

                    $(ele).closest('tr').find('.inner_diameter').val("");

                    $(ele).closest('tr').find('.outer_diameter').val("");

                    $(ele).closest('tr').find('.thikness').val("");

                    $(ele).closest('tr').find('.hsn').val("");
                    $(ele).closest('tr').find('.hsnSpan').text("");

                    $(ele).closest('tr').find('.uom').val("");
                    $(ele).closest('tr').find('.uomSpan').text("");

                    $(ele).closest('tr').find('.discount_per').val(0);
                    $(ele).closest('tr').find('.qty').val(0);
                    $(ele).closest('tr').find('.photo').attr("src","");
                    var product = $(ele).closest('tr').find('.product').val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/admin/get_product',
                        data: {product: product, customer: customer},
                        method: 'get',
                        dataType: 'json',
                        beforeSend: function(){
                            $("#loader").show();
                        },
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {

                                $(ele).closest('tr').find('.description').val(data[0]['description']);

                                $(ele).closest('tr').find('.price').val(data[0]['price']);

                                $(ele).closest('tr').find('.gst').val(data[0]['gst']);
                                $(ele).closest('tr').find('.igst_per').val(data[0]['gst']);
                                $(ele).closest('tr').find('.gst_per').val(data[0]['gst']);

                                $(ele).closest('tr').find('.cgst_per').val(data[0]['cgst']);

                                $(ele).closest('tr').find('.sgst_per').val(data[0]['sgst']);

                                $(ele).closest('tr').find('.inner_diameter').val(data[0]['inner_diameter']);

                                $(ele).closest('tr').find('.outer_diameter').val(data[0]['outer_diameter']);

                                $(ele).closest('tr').find('.thikness').val(data[0]['thikness']);

                                $(ele).closest('tr').find('.hsn').val(data[0]['hsn']);
                                $(ele).closest('tr').find('.hsnSpan').text(data[0]['hsn']);
                                $(ele).closest('tr').find('.stockQty').text("Stock : "+data[0]['stockqty']);

                                $(ele).closest('tr').find('.uom').val(data[0]['uom']);
                                $(ele).closest('tr').find('.uomSpan').text(data[0]['uom']);

                                $(ele).closest('tr').find('.discount_per').val(data[0]['discper']);
                                $(ele).closest('tr').find('.photo').attr("src",data[0]['product_image']);

                            }
                        },
                        complete:function(data){
                            // Hide image container
                            $("#loader").hide();
                        }
                    });
                }
            }

            function get_bom(ele) {
                var customer = $("#customer").val();
                if(customer==""){
                    alert("please select customer first");
                }else{

                    $(ele).closest('tr').find('.description').val("");

                    $(ele).closest('tr').find('.price').val(0);

                    $(ele).closest('tr').find('.gst').val(0);

                    $(ele).closest('tr').find('.cgst_per').val(0);
                    $(ele).closest('tr').find('.cgst_amount').val(0);
                    $(ele).closest('tr').find('.gst_amount').val(0);
                    $(ele).closest('tr').find('.sgst_per').val(0);
                    $(ele).closest('tr').find('.sgst_amount').val(0);
                    $(ele).closest('tr').find('.gst_per').val(0);
                    $(ele).closest('tr').find('.gst_amount').val(0);

                    $(ele).closest('tr').find('.inner_diameter').val("");

                    $(ele).closest('tr').find('.outer_diameter').val("");

                    $(ele).closest('tr').find('.thikness').val("");

                    $(ele).closest('tr').find('.hsn').val("");
                    $(ele).closest('tr').find('.hsnSpan').text("");

                    $(ele).closest('tr').find('.uom').val("");
                    $(ele).closest('tr').find('.uomSpan').text("");

                    $(ele).closest('tr').find('.discount_per').val(0);
                    $(ele).closest('tr').find('.qty').val(0);
                    $(ele).closest('tr').find('.photo').attr("src","");
                    var product = $(ele).closest('tr').find('.product').val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url: appurl + '/admin/get_product',
                        data: {product: product, customer: customer},
                        method: 'get',
                        dataType: 'json',
                        beforeSend: function(){
                            $("#loader").show();
                        },
                        success: function (data) {
                            var len = data.length;
                            if (len > 0) {

                                $(ele).closest('tr').find('.description').val(data[0]['description']);

                                $(ele).closest('tr').find('.price').val(data[0]['price']);

                                $(ele).closest('tr').find('.gst').val(data[0]['gst']);
                                $(ele).closest('tr').find('.igst_per').val(data[0]['gst']);
                                $(ele).closest('tr').find('.gst_per').val(data[0]['gst']);

                                $(ele).closest('tr').find('.cgst_per').val(data[0]['cgst']);

                                $(ele).closest('tr').find('.sgst_per').val(data[0]['sgst']);

                                $(ele).closest('tr').find('.inner_diameter').val(data[0]['inner_diameter']);

                                $(ele).closest('tr').find('.outer_diameter').val(data[0]['outer_diameter']);

                                $(ele).closest('tr').find('.thikness').val(data[0]['thikness']);

                                $(ele).closest('tr').find('.hsn').val(data[0]['hsn']);
                                $(ele).closest('tr').find('.hsnSpan').text(data[0]['hsn']);
                                $(ele).closest('tr').find('.stockQty').text("Stock : "+data[0]['stockqty']);

                                $(ele).closest('tr').find('.uom').val(data[0]['uom']);
                                $(ele).closest('tr').find('.uomSpan').text(data[0]['uom']);

                                $(ele).closest('tr').find('.discount_per').val(data[0]['discper']);
                                $(ele).closest('tr').find('.photo').attr("src",data[0]['product_image']);

                            }
                        },
                        complete:function(data){
                            // Hide image container
                            $("#loader").hide();
                        }
                    });
                }
            }

            function discmatcal(ele)
            {
                var rate = $(ele).closest('tr').find('.price').val();
                var qty = $(ele).closest('tr').find('.qty').val();
                var total = Number(rate) * Number(qty);
                //$(ele).closest('tr').find('.productTotal').text(total.toFixed(2));
                var discount_amount=$(ele).closest('tr').find(".discount_amount").val();
                var afterdisc = Number(total) - Number(discount_amount);
                $(ele).closest('tr').find('.discountTotal').text(discount_amount);
                $(ele).closest('tr').find('.totalAfterDiscount').text(afterdisc);
            }
            function taxDiv(ele)
            {
                $(ele).closest('tr').find('.taxdiv').toggle(500);
            }
            function disDiv(ele)
            {
                $(ele).closest('tr').find('.discountDiv').toggle(500);
            }
            function cal(ele) {
                $(ele).closest('tr').find('.discountTotal').text(0);
                var rate = $(ele).closest('tr').find('.price').val();
                var qty = $(ele).closest('tr').find('.qty').val();
                var total = Number(rate) * Number(qty);
                $(ele).closest('tr').find('.productTotal').text(total.toFixed(2));

                $(ele).closest('tr').find('.total').val(total.toFixed(2));

                var discount_per = $(ele).closest('tr').find('.discount_per').val();

                var gst_per = $(ele).closest('tr').find('.gst_per').val();
                var cgst_per = $(ele).closest('tr').find('.cgst_per').val();
                var sgst_per = $(ele).closest('tr').find('.sgst_per').val();

                var discount_amount = Number(total) * Number(discount_per) / 100;
                var afterdisc = Number(total) - Number(discount_amount);
                $(ele).closest('tr').find('.totalAfterDiscount').text(afterdisc.toFixed(2));

                $(ele).closest('tr').find('.discount_amount').val(discount_amount.toFixed(2));
                $(ele).closest('tr').find('.discountTotal').text(discount_amount.toFixed(2));
                $(ele).closest('tr').find('.discountPerc').text(discount_per);
                // alert(gst_per);
                var gst_amount = Number(afterdisc) * Number(gst_per) / 100;
                var cgst_amount = Number(afterdisc) * Number(cgst_per) / 100;
                var sgst_amount = Number(afterdisc) * Number(sgst_per) / 100;
                //alert(gst_amount);
                $(ele).closest('tr').find('.gst_amount').val(gst_amount.toFixed(2));
                $(ele).closest('tr').find('.cgst_amount').val(cgst_amount.toFixed(2));
                $(ele).closest('tr').find('.sgst_amount').val(sgst_amount.toFixed(2));
                var productTaxTotal = Number(gst_amount) + Number(cgst_amount) + Number(sgst_amount);
                $(ele).closest('tr').find('.productTaxTotal').text(productTaxTotal.toFixed(2));
                var netprice = Number(afterdisc) + Number(gst_amount) + Number(cgst_amount) + Number(sgst_amount);

                $(ele).closest('tr').find('.taxTotal').text(Number(gst_per)+Number(sgst_per)+Number(cgst_per));

                $(ele).closest('tr').find('.netprice').val(netprice.toFixed(2));

                var totalss = $(".productTotal");
                var item_total = 0;
                for (var i = 0; i < totalss.length; i++) {
                    item_total = Number(item_total) + Number($(totalss[i]).text());
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

                var cgst_amounts = $(".cgst_amount");
                var cgst_total = 0;
                for (var i = 0; i < cgst_amounts.length; i++) {
                    cgst_total = Number(cgst_total) + Number($(cgst_amounts[i]).val());
                }

                var sgst_amounts = $(".sgst_amount");
                var sgst_total = 0;
                for (var i = 0; i < sgst_amounts.length; i++) {
                    sgst_total = Number(sgst_total) + Number($(sgst_amounts[i]).val());
                }

                var netprices = $(".netprice");
                var netpricestotal = 0;
                for (var i = 0; i < netprices.length; i++) {
                    netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                }


                $("#item_total").val(item_total.toFixed(2));
                $("#discount_total").val(discount_total.toFixed(2));
                $("#igsttotal").val(gst_total.toFixed(2));
                $("#cgsttotal").val(cgst_total.toFixed(2));
                $("#sgsttotal").val(sgst_total.toFixed(2));

                var round = Math.round(netpricestotal);
                var diff = Number(round) - Number(netpricestotal);
                var gtot = Number(netpricestotal) + Number(diff);
                $("#adjustment").val(diff.toFixed(2));

                $("#grand_total").val(round.toFixed(2));
                // $("#grand_total").val(netpricestotal.toFixed(2));

            }


            function model_close() {
                $("#product_model").hide();
                $("#service_model").hide();
                $("#bom_model").hide();
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

            function bom_search(srno) {
                $("#bom_model").show();
                $("#bomsrid").val(srno);
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

            $("#bom_datatable-buttons").on('click', 'tr', function (e) {
                e.preventDefault();
                var id = $(this).attr('value');
                var srno = $("#bomsrid").val();
                get_product(id, srno)
                $("#bom_model").hide();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/client/ajax_getbom',
                    data: {product: id},
                    method: 'get',
                    success: function (data) {
                        $("#product" + srno).html(data);
                    }
                });
            });

            $("#add_product").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr id='row" + i + "'><td style='vertical-align: top !important;width: 10%'><input type='text' onfocusout='search_product(this)' class='itemname form-control'></td><td style='width:15%'><div class='form-group'><select class='form-control js-example-basic-single product' onchange='get_product(this)' name='product[]' id='product" + i + "'> <option>select</option>@foreach($product as $prod)<option value='{{$prod->id}}'>{{$prod->item_code}} - {{$prod->product_name}}</option>@endforeach</select></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='description'></textarea></div></td>";
                {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="inner_diameter smallInputBox inputElement" id="inner_diamitter' + i + '"></td>';--}}
                        {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="outer_diameter smallInputBox inputElement" id="outer_diamitter' + i + '"></td>';--}}
                        {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="thikness smallInputBox inputElement" id="thikness' + i + '"></td>';--}}

                    data +='<td style="vertical-align: top !important;text-align:center"><img style="height: 55px;width: 55px;" src="" class="photo img-responsive"> </td>';
                data +='<td style="vertical-align: top !important;text-align: center;">\n' +
                    '                                            <span class="hsnSpan"></span><input type="hidden" class="hsn smallInputBox inputElement" name="hsn[]" value=""\n' +
                    '                                                   id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;width: 10%">\n' +
                    '                                            <input type="text" name="qty[]" onkeyup="cal(this)" value="0"\n' +
                    '                                                   class="qty smallInputBox inputElement" id="">\n' +
                    '                                            <label class="stockQty">Stock : </label>\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;">\n' +
                    '                                            <span class="uomSpan"></span><input type="hidden" class="uom smallInputBox inputElement" name="uom[]" value=""\n' +
                    '                                                   id="">\n' +
                    '                                        </td>\n' +
                    '                                        \n' +
                    '                                        <td style="vertical-align: top !important;">\n' +
                    '                                            <div>\n' +
                    '                                                <input oninput="cal(this)"  name="price[]" value="0"\n' +
                    '                                                       type="text"\n' +
                    '                                                       data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                       class="price listPrice smallInputBox inputElement"\n' +
                    '                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                       data-base-currency-id="" aria-required="true" autocomplete="off"\n' +
                    '                                                       aria-invalid="false">&nbsp;<span\n' +
                    '                                                    class="priceBookPopup cursorPointer" data-popup="Popup"\n' +
                    '                                                    title="Price Books" data-module-name="PriceBooks"\n' +
                    '                                                    style="float:left">\n' +
                    '                                                    <i class="vicon-pricebooks" title="Price Books"></i>\n' +
                    '                                                </span>\n' +
                    '                                            </div>\n' +
                    '                                            <div style="clear:both"></div>\n' +
                    '                                            <div>\n' +
                    '                                                <span>(-)&nbsp;<strong>\n' +
                    '                                                         <a style="cursor: pointer" onclick="disDiv(this)">Discount</a>\n' +
                    '                                                         (<span class="discountPerc">0</span>%)\n' +
                    '                                                        </a> :\n' +
                    '                                                        <div class="discountDiv" style="display: none">\n' +
                    '                                                            <div class="form-group" style="width: 50%;display: inline;float: left;">\n' +
                    '                                                                <label>Disc %</label>\n' +
                    '                                                                <input oninput="cal(this)"\n' +
                    '                                                                       name="discount_per[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       data-rule-required="true"\n' +
                    '                                                                       data-rule-positive="true"\n' +
                    '                                                                       class="discount_per listPrice smallInputBox inputElement"\n' +
                    '                                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                                       data-base-currency-id="" aria-required="true"\n' +
                    '                                                                       autocomplete="off" aria-invalid="false" style="width: 80%;">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '                                                            <div class="form-group" style="width: 50%;float: left;">\n' +
                    '                                                                <label>Disc Amt</label>\n' +
                    '                                                                <input oninput="discmatcal(this)"\n' +
                    '                                                                       name="discount_amount[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       class="discount_amount inputElement" style="width: 80%;">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                    </strong>\n' +
                    '                                                </span>\n' +
                    '                                            </div>\n' +
                    '\n' +
                    '                                            <div style="width:150px;">\n' +
                    '                                                <strong>Total After Discount :</strong>\n' +
                    '                                            </div>\n' +
                    '                                            <div class="individualTaxContainer">(+)&nbsp;\n' +
                    '                                                <strong>\n' +
                    '                                                    <a style="cursor: pointer" onclick="taxDiv(this)">Tax </a> (<span class="taxTotal"></span>%):\n' +
                    '                                                    <div style="display:none;" class="taxdiv">\n' +
                    '                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">\n' +
                    '                                                            <label>CGST %</label>\n' +
                    '                                                            <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                   name="cgst_per1[]"\n' +
                    '                                                                   value="" type="text"\n' +
                    '                                                                   data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                   class="cgst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                   data-is-price-changed="false" list-info=""\n' +
                    '                                                                   data-base-currency-id="" aria-required="true"\n' +
                    '                                                                   autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">\n' +
                    '                                                            <label>SGST %</label>\n' +
                    '                                                            <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                   name="sgst_per1[]"\n' +
                    '                                                                   value="" type="text"\n' +
                    '                                                                   data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                   class="sgst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                   data-is-price-changed="false" list-info=""\n' +
                    '                                                                   data-base-currency-id="" aria-required="true"\n' +
                    '                                                                   autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="form-group" style="width: 32%;float: left;">\n' +
                    '                                                            <div class="form-group" >\n' +
                    '                                                                <label>IGST %</label>\n' +
                    '                                                                <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                       name="igst_per1[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                       class="gst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                                       data-base-currency-id="" aria-required="true"\n' +
                    '                                                                       autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                </strong>\n' +
                    '                                            </div>\n' +
                    '                                            <span class="taxDivContainer">\n' +
                    '                                                <div class="taxUI hide" id="tax_div1">\n' +
                    '                                                    <p class="popover_title hide">Set Tax for : <span\n' +
                    '                                                            class="variable"></span>\n' +
                    '                                                    </p>\n' +
                    '                                                </div>\n' +
                    '                                            </span>\n' +
                    '\n' +
                    '                                        </td>\n' +
                    '\n' +
                    '                                        <td style="vertical-align: top !important;">\n' +
                    '                                            <div  align="right" class="productTotal">0.00</div>\n' +
                    '                                            <div  align="right" class="discountTotal">\n' +
                    '                                                0.00\n' +
                    '                                            </div>\n' +
                    '                                            <div  align="right" class="totalAfterDiscount">\n' +
                    '                                                0.00\n' +
                    '                                            </div>\n' +
                    '                                            <div id="taxTotal1" align="right" class="productTaxTotal">0.00</div>\n' +
                    '                                        </td>\n' +
                    '\n' +
                    '\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="cgst_per[]" value=""\n' +
                    '                                                   class="cgst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="cgst_amount[]" value=""\n' +
                    '                                                   class="cgst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="sgst_per[]" value=""\n' +
                    '                                                   class="sgst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="sgst_amount[]" value=""\n' +
                    '                                                   class="sgst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="gst_per[]" value=""\n' +
                    '                                                   class="gst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="gst_amount[]" value=""\n' +
                    '                                                   class="gst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;">\n' +
                    '\n' +
                    '                                           <input type="hidden" class="total" name="total_amount[]"><input type="text" name="net_price[]" value=""\n' +
                    '                                                   class="netprice smallInputBox inputElement" id="">\n' +
                    '                                        </td>';
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);
                $(document).ready(function () {
                    $('.js-example-basic-single').select2();
                });
                $("#totrow").val(i);
            });


            $("#add_service").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr id='row" + i + "'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td style='width:15%'><div class='form-group'><select class='form-control js-example-basic-single product' onchange='get_service(this)' name='product[]' id='product" + i + "'> <option>select</option>@foreach($service as $prod)<option value='{{$prod->id}}'>{{$prod->product_name}}</option>@endforeach</select></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='description'></textarea></div></td>";
                {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="inner_diameter smallInputBox inputElement" id="inner_diamitter' + i + '"></td>';--}}
                        {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="outer_diameter smallInputBox inputElement" id="outer_diamitter' + i + '"></td>';--}}
                        {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="thikness smallInputBox inputElement" id="thikness' + i + '"></td>';--}}

                    data +='<td style="vertical-align: top !important;text-align:center"><img style="height: 55px;width: 55px;" src="" class="photo img-responsive"> </td>';
                data +='<td style="vertical-align: top !important;text-align: center;">\n' +
                    '                                            <span class="hsnSpan"></span><input type="hidden" class="hsn smallInputBox inputElement" name="hsn[]" value=""\n' +
                    '                                                   id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;width: 10%">\n' +
                    '                                            <input type="text" name="qty[]" onkeyup="cal(this)" value="0"\n' +
                    '                                                   class="qty smallInputBox inputElement" id="">\n' +
                    '                                            <label class="stockQty">Stock : </label>\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;">\n' +
                    '                                            <span class="uomSpan"></span><input type="hidden" class="uom smallInputBox inputElement" name="uom[]" value=""\n' +
                    '                                                   id="">\n' +
                    '                                        </td>\n' +
                    '                                        \n' +
                    '                                        <td style="vertical-align: top !important;">\n' +
                    '                                            <div>\n' +
                    '                                                <input oninput="cal(this)"  name="price[]" value="0"\n' +
                    '                                                       type="text"\n' +
                    '                                                       data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                       class="price listPrice smallInputBox inputElement"\n' +
                    '                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                       data-base-currency-id="" aria-required="true" autocomplete="off"\n' +
                    '                                                       aria-invalid="false">&nbsp;<span\n' +
                    '                                                    class="priceBookPopup cursorPointer" data-popup="Popup"\n' +
                    '                                                    title="Price Books" data-module-name="PriceBooks"\n' +
                    '                                                    style="float:left">\n' +
                    '                                                    <i class="vicon-pricebooks" title="Price Books"></i>\n' +
                    '                                                </span>\n' +
                    '                                            </div>\n' +
                    '                                            <div style="clear:both"></div>\n' +
                    '                                            <div>\n' +
                    '                                                <span>(-)&nbsp;<strong>\n' +
                    '                                                         <a style="cursor: pointer" onclick="disDiv(this)">Discount</a>\n' +
                    '                                                         (<span class="discountPerc">0</span>%)\n' +
                    '                                                        </a> :\n' +
                    '                                                        <div class="discountDiv" style="display: none">\n' +
                    '                                                            <div class="form-group" style="width: 50%;display: inline;float: left;">\n' +
                    '                                                                <label>Disc %</label>\n' +
                    '                                                                <input oninput="cal(this)"\n' +
                    '                                                                       name="discount_per[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       data-rule-required="true"\n' +
                    '                                                                       data-rule-positive="true"\n' +
                    '                                                                       class="discount_per listPrice smallInputBox inputElement"\n' +
                    '                                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                                       data-base-currency-id="" aria-required="true"\n' +
                    '                                                                       autocomplete="off" aria-invalid="false" style="width: 80%;">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '                                                            <div class="form-group" style="width: 50%;float: left;">\n' +
                    '                                                                <label>Disc Amt</label>\n' +
                    '                                                                <input oninput="discmatcal(this)"\n' +
                    '                                                                       name="discount_amount[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       class="discount_amount inputElement" style="width: 80%;">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                    </strong>\n' +
                    '                                                </span>\n' +
                    '                                            </div>\n' +
                    '\n' +
                    '                                            <div style="width:150px;">\n' +
                    '                                                <strong>Total After Discount :</strong>\n' +
                    '                                            </div>\n' +
                    '                                            <div class="individualTaxContainer">(+)&nbsp;\n' +
                    '                                                <strong>\n' +
                    '                                                    <a style="cursor: pointer" onclick="taxDiv(this)">Tax </a> (<span class="taxTotal"></span>%):\n' +
                    '                                                    <div style="display:none;" class="taxdiv">\n' +
                    '                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">\n' +
                    '                                                            <label>CGST %</label>\n' +
                    '                                                            <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                   name="cgst_per1[]"\n' +
                    '                                                                   value="" type="text"\n' +
                    '                                                                   data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                   class="cgst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                   data-is-price-changed="false" list-info=""\n' +
                    '                                                                   data-base-currency-id="" aria-required="true"\n' +
                    '                                                                   autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">\n' +
                    '                                                            <label>SGST %</label>\n' +
                    '                                                            <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                   name="sgst_per1[]"\n' +
                    '                                                                   value="" type="text"\n' +
                    '                                                                   data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                   class="sgst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                   data-is-price-changed="false" list-info=""\n' +
                    '                                                                   data-base-currency-id="" aria-required="true"\n' +
                    '                                                                   autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="form-group" style="width: 32%;float: left;">\n' +
                    '                                                            <div class="form-group" >\n' +
                    '                                                                <label>IGST %</label>\n' +
                    '                                                                <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                       name="igst_per1[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                       class="igst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                                       data-base-currency-id="" aria-required="true"\n' +
                    '                                                                       autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                </strong>\n' +
                    '                                            </div>\n' +
                    '                                            <span class="taxDivContainer">\n' +
                    '                                                <div class="taxUI hide" id="tax_div1">\n' +
                    '                                                    <p class="popover_title hide">Set Tax for : <span\n' +
                    '                                                            class="variable"></span>\n' +
                    '                                                    </p>\n' +
                    '                                                </div>\n' +
                    '                                            </span>\n' +
                    '\n' +
                    '                                        </td>\n' +
                    '\n' +
                    '                                        <td style="vertical-align: top !important;">\n' +
                    '                                            <div  align="right" class="productTotal">0.00</div>\n' +
                    '                                            <div  align="right" class="discountTotal">\n' +
                    '                                                0.00\n' +
                    '                                            </div>\n' +
                    '                                            <div  align="right" class="totalAfterDiscount">\n' +
                    '                                                0.00\n' +
                    '                                            </div>\n' +
                    '                                            <div id="taxTotal1" align="right" class="productTaxTotal">0.00</div>\n' +
                    '                                        </td>\n' +
                    '\n' +
                    '\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="cgst_per[]" value=""\n' +
                    '                                                   class="cgst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="cgst_amount[]" value=""\n' +
                    '                                                   class="cgst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="sgst_per[]" value=""\n' +
                    '                                                   class="sgst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="sgst_amount[]" value=""\n' +
                    '                                                   class="sgst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="gst_per[]" value=""\n' +
                    '                                                   class="gst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="gst_amount[]" value=""\n' +
                    '                                                   class="gst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;">\n' +
                    '\n' +
                    '                                           <input type="hidden" class="total" name="total_amount[]"><input type="text" name="net_price[]" value=""\n' +
                    '                                                   class="netprice smallInputBox inputElement" id="">\n' +
                    '                                        </td>';
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);
                $(document).ready(function () {
                    $('.js-example-basic-single').select2();
                });
                $("#totrow").val(i);
            });

            $("#add_bom").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr id='row" + i + "'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td style='width:15%'><div class='form-group'><select class='form-control js-example-basic-single product' onchange='get_bom(this)' name='product[]' id='product" + i + "'> <option>select</option>@foreach($bom as $prod)<option value='{{$prod->id}}'>{{$prod->product_name}}</option>@endforeach</select></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='description'></textarea></div></td>";
                {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="inner_diameter smallInputBox inputElement" id="inner_diamitter' + i + '"></td>';--}}
                        {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="outer_diameter smallInputBox inputElement" id="outer_diamitter' + i + '"></td>';--}}
                        {{--            data += '<td style="width:6%;vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="thikness smallInputBox inputElement" id="thikness' + i + '"></td>';--}}

                    data +='<td style="vertical-align: top !important;text-align:center"><img style="height: 55px;width: 55px;" src="" class="photo img-responsive"> </td>';
                data +='<td style="vertical-align: top !important;text-align: center;">\n' +
                    '                                            <span class="hsnSpan"></span><input type="hidden" class="hsn smallInputBox inputElement" name="hsn[]" value=""\n' +
                    '                                                   id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;width: 10%">\n' +
                    '                                            <input type="text" name="qty[]" onkeyup="cal(this)" value="0"\n' +
                    '                                                   class="qty smallInputBox inputElement" id="">\n' +
                    '                                            <label class="stockQty">Stock : </label>\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;">\n' +
                    '                                            <span class="uomSpan"></span><input type="hidden" class="uom smallInputBox inputElement" name="uom[]" value=""\n' +
                    '                                                   id="">\n' +
                    '                                        </td>\n' +
                    '                                        \n' +
                    '                                        <td style="vertical-align: top !important;">\n' +
                    '                                            <div>\n' +
                    '                                                <input oninput="cal(this)"  name="price[]" value="0"\n' +
                    '                                                       type="text"\n' +
                    '                                                       data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                       class="price listPrice smallInputBox inputElement"\n' +
                    '                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                       data-base-currency-id="" aria-required="true" autocomplete="off"\n' +
                    '                                                       aria-invalid="false">&nbsp;<span\n' +
                    '                                                    class="priceBookPopup cursorPointer" data-popup="Popup"\n' +
                    '                                                    title="Price Books" data-module-name="PriceBooks"\n' +
                    '                                                    style="float:left">\n' +
                    '                                                    <i class="vicon-pricebooks" title="Price Books"></i>\n' +
                    '                                                </span>\n' +
                    '                                            </div>\n' +
                    '                                            <div style="clear:both"></div>\n' +
                    '                                            <div>\n' +
                    '                                                <span>(-)&nbsp;<strong>\n' +
                    '                                                         <a style="cursor: pointer" onclick="disDiv(this)">Discount</a>\n' +
                    '                                                         (<span class="discountPerc">0</span>%)\n' +
                    '                                                        </a> :\n' +
                    '                                                        <div class="discountDiv" style="display: none">\n' +
                    '                                                            <div class="form-group" style="width: 50%;display: inline;float: left;">\n' +
                    '                                                                <label>Disc %</label>\n' +
                    '                                                                <input oninput="cal(this)"\n' +
                    '                                                                       name="discount_per[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       data-rule-required="true"\n' +
                    '                                                                       data-rule-positive="true"\n' +
                    '                                                                       class="discount_per listPrice smallInputBox inputElement"\n' +
                    '                                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                                       data-base-currency-id="" aria-required="true"\n' +
                    '                                                                       autocomplete="off" aria-invalid="false" style="width: 80%;">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '                                                            <div class="form-group" style="width: 50%;float: left;">\n' +
                    '                                                                <label>Disc Amt</label>\n' +
                    '                                                                <input oninput="discmatcal(this)"\n' +
                    '                                                                       name="discount_amount[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       class="discount_amount inputElement" style="width: 80%;">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                    </strong>\n' +
                    '                                                </span>\n' +
                    '                                            </div>\n' +
                    '\n' +
                    '                                            <div style="width:150px;">\n' +
                    '                                                <strong>Total After Discount :</strong>\n' +
                    '                                            </div>\n' +
                    '                                            <div class="individualTaxContainer">(+)&nbsp;\n' +
                    '                                                <strong>\n' +
                    '                                                    <a style="cursor: pointer" onclick="taxDiv(this)">Tax </a> (<span class="taxTotal"></span>%):\n' +
                    '                                                    <div style="display:none;" class="taxdiv">\n' +
                    '                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">\n' +
                    '                                                            <label>CGST %</label>\n' +
                    '                                                            <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                   name="cgst_per1[]"\n' +
                    '                                                                   value="" type="text"\n' +
                    '                                                                   data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                   class="cgst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                   data-is-price-changed="false" list-info=""\n' +
                    '                                                                   data-base-currency-id="" aria-required="true"\n' +
                    '                                                                   autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">\n' +
                    '                                                            <label>SGST %</label>\n' +
                    '                                                            <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                   name="sgst_per1[]"\n' +
                    '                                                                   value="" type="text"\n' +
                    '                                                                   data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                   class="sgst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                   data-is-price-changed="false" list-info=""\n' +
                    '                                                                   data-base-currency-id="" aria-required="true"\n' +
                    '                                                                   autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                        <div class="form-group" style="width: 32%;float: left;">\n' +
                    '                                                            <div class="form-group" >\n' +
                    '                                                                <label>IGST %</label>\n' +
                    '                                                                <input style="width: 35px;" oninput="cal(this)"\n' +
                    '                                                                       name="igst_per1[]"\n' +
                    '                                                                       value="" type="text"\n' +
                    '                                                                       data-rule-required="true" data-rule-positive="true"\n' +
                    '                                                                       class="igst_per listPrice smallInputBox inputElement"\n' +
                    '                                                                       data-is-price-changed="false" list-info=""\n' +
                    '                                                                       data-base-currency-id="" aria-required="true"\n' +
                    '                                                                       autocomplete="off" aria-invalid="false">\n' +
                    '\n' +
                    '                                                            </div>\n' +
                    '\n' +
                    '                                                        </div>\n' +
                    '                                                </strong>\n' +
                    '                                            </div>\n' +
                    '                                            <span class="taxDivContainer">\n' +
                    '                                                <div class="taxUI hide" id="tax_div1">\n' +
                    '                                                    <p class="popover_title hide">Set Tax for : <span\n' +
                    '                                                            class="variable"></span>\n' +
                    '                                                    </p>\n' +
                    '                                                </div>\n' +
                    '                                            </span>\n' +
                    '\n' +
                    '                                        </td>\n' +
                    '\n' +
                    '                                        <td style="vertical-align: top !important;">\n' +
                    '                                            <div  align="right" class="productTotal">0.00</div>\n' +
                    '                                            <div  align="right" class="discountTotal">\n' +
                    '                                                0.00\n' +
                    '                                            </div>\n' +
                    '                                            <div  align="right" class="totalAfterDiscount">\n' +
                    '                                                0.00\n' +
                    '                                            </div>\n' +
                    '                                            <div id="taxTotal1" align="right" class="productTaxTotal">0.00</div>\n' +
                    '                                        </td>\n' +
                    '\n' +
                    '\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="cgst_per[]" value=""\n' +
                    '                                                   class="cgst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="cgst_amount[]" value=""\n' +
                    '                                                   class="cgst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="sgst_per[]" value=""\n' +
                    '                                                   class="sgst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="sgst_amount[]" value=""\n' +
                    '                                                   class="sgst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="gst_per[]" value=""\n' +
                    '                                                   class="gst_per form-control" id=""\n' +
                    '                                                   oninput="cal(this)">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="display:none;vertical-align: top !important;text-align: center;">\n' +
                    '                                            <input type="text" name="gst_amount[]" value=""\n' +
                    '                                                   class="gst_amount form-control" id="">\n' +
                    '                                        </td>\n' +
                    '                                        <td style="vertical-align: top !important;text-align: center;">\n' +
                    '\n' +
                    '                                           <input type="hidden" class="total" name="total_amount[]"><input type="text" name="net_price[]" value=""\n' +
                    '                                                   class="netprice smallInputBox inputElement" id="">\n' +
                    '                                        </td>';
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);
                $(document).ready(function () {
                    $('.js-example-basic-single').select2();
                });


                $("#totrow").val(i);
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

                    var cgst_amounts = $(".cgst_amount");
                    var cgst_total = 0;
                    for (var i = 0; i < cgst_amounts.length; i++) {
                        cgst_total = Number(cgst_total) + Number($(cgst_amounts[i]).val());
                    }

                    var sgst_amounts = $(".sgst_amount");
                    var sgst_total = 0;
                    for (var i = 0; i < sgst_amounts.length; i++) {
                        sgst_total = Number(sgst_total) + Number($(sgst_amounts[i]).val());
                    }


                    var netprices = $(".netprice");
                    var netpricestotal = 0;
                    for (var i = 0; i < netprices.length; i++) {
                        netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                    }


                    $("#item_total").val(item_total);
                    $("#discount_total").val(discount_total);
                    $("#gsttotal").val(gst_total);
                    $("#cgsttotal").val(cgst_total);
                    $("#sgsttotal").val(sgst_total);
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
            function getdate(qdate) {
                //alert(qdate);
                var tt = document.getElementById('quot_date').value;
                var date = new Date(tt);
                var newdate = new Date(date);
                newdate.setDate(newdate.getDate() + 14);

                var dd = newdate.getDate();
                var mm = newdate.getMonth() + 1;
                var y = newdate.getFullYear();

                var someFormattedDate = dd + '-' + mm + '-' + y;
                document.getElementById('valid_until').value = someFormattedDate;

                var ndate = tt.split('/');
                var nmm = ndate[0];
                var ndd = ndate[1];
                var nyy = ndate[2];

                var newquotdate = ndd + '-' + nmm + '-' + nyy;

                $("#quot_date").val(newquotdate);
            }

            $.noConflict();
            jQuery(document).ready(function ($) {
                $("#quot_date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'mm/dd/yy',
                    onClose: function () {
                        getdate($(this).val());
                    }
                });
                $("#valid_until").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            });


        </script>
@endsection
