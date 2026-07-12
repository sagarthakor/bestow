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
        <title>{{Session::get('software_title')}} - Quotaion Edit</title>

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
                                            Quotation Edit
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->


                        {{Form::model($data,['method'=>'post','route'=>'post.quot_normal_update'])}}
                        {{Form::hidden('id',null)}}
                        {{Form::hidden('quot_no',$data->quot_no)}}

                        <div class="panel">

                            <div class="panel-body">
                                   <div class="row">

                                                         <div class="col-md-6">
                                                             <label class="control-label">Customer Name <span style="color: red">*</span></label>
                                                             <div class="input-group">

                                                              {{Form::select('customer',$customer,null,['required'=>'required','class'=>'form-control js-example-basic-single','id'=>'customer','onchange'=>'getcustomer(this.value)'])}}
                                                              <span class="input-group-addon"><i class="glyphicon glyphicon-plus" onclick="add_customer()"></i></span>
                                                          </div>

                                                      </div>
                                                      <div class="col-md-6">
                                                          <div class="form-group">

                                                           <label class="control-label">Quotation No. <span style="color: red">*</span></label>
                                                           {{Form::text('quotation_no',null,['required'=>'required','class'=>'form-control input-daterange-datepicker','autocomplete'=>'off','readonly'])}}

                                                       </div>
                                                   </div>
                                               </div>


                                               <div class="row">
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label class="control-label">Subject <span style="color: red">*</span></label>
                                                        {{Form::text('subject',null,['required'=>'required','class'=>'form-control','id'=>"subject"])}}

                                                    </div>
                                                </div>

                                                 <div class="col-md-6">
                                                          <div class="form-group">

                                                           <label class="control-label">Quotation Date <span style="color: red">*</span></label>
                                                           {{Form::text('quot_date',date('d-m-Y'),['required'=>'required','class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}

                                                       </div>
                                                   </div>

                                        </div>
                                        <div class="row">
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                <label class="control-label">Contact Name <span style="color: red">*</span></label>
                                                {{Form::select('contact_name',$contact_name,null,['required'=>'required','class'=>'form-control','id'=>"contact_name"])}}

                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label class="control-label">Valid Until <span style="color: red">*</span></label>
                                                    {{Form::text('valid_until',Date('d-m-Y', strtotime('+14 days'))  ,['required'=>'required','class'=>'form-control input-daterange-datepicker','id'=>"valid_until",'autocomplete'=>'off'])}}

                                                </div>
                                            </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <?php
                                            $stage=array('Created'=>'Created','Sent'=>'Sent','Reviewing'=>'Reviewing','QuoteRivision'=>'QuoteRivision','Accepted'=>'Accepted','Invoiced'=>'Invoiced','Canceled'=>'Canceled');
                                            ?>
                                            <label class="control-label">Quote Stage <span style="color: red">*</span> </label>
                                            {{Form::select('quot_stage',$stage,null,['required'=>'required','class'=>'form-control js-example-basic-single','id'=>'quot_stage'])}}


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
                                                    {{Form::textarea('billing_address',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'billing_address','required'])}}

                                                </div>

                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Country <span style="color: red">*</span></label>

                                                    {{Form::text('billing_country',null,['class'=>'form-control','id'=>"billing_country",'required'])}}

                                                </div>
                                            </div>

                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>State <span style="color: red">*</span></label>

                                                    {{Form::text('billing_state',null,['class'=>'form-control','id'=>"billing_state",'required'])}}

                                                </div>
                                            </div>

                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>City  <span style="color: red">*</span></label>

                                                    {{Form::text('billing_city',null,['class'=>'form-control','id'=>"billing_city",'required'])}}

                                                </div>
                                            </div>



                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Postal Code <span style="color: red">*</span></label>

                                                    {{Form::text('billing_postalcode',null,['class'=>'form-control','id'=>"billing_postalcode",'required'])}}

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label>Address <span style="color: red">*</span></label>
                                                    {{Form::textarea('shipping_address',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'shipping_address','required'])}}

                                                </div>
                                            </div>


                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Country <span style="color: red">*</span></label>
                                                    {{Form::text('shipping_country',null,['class'=>'form-control','id'=>'shipping_country','required'])}}

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>City <span style="color: red">*</span></label>
                                                    {{Form::text('shipping_city',null,['class'=>'form-control','id'=>'shipping_city','required'])}}

                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>State <span style="color: red">*</span></label>
                                                    {{Form::text('shipping_state',null,['class'=>'form-control','id'=>'shipping_state','required'])}}

                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Postal Code <span style="color: red">*</span></label>
                                                    {{Form::text('shipping_postalcode',null,['class'=>'form-control','id'=>'shipping_postalcode','required'])}}

                                                </div>
                                            </div>
                                        </div>

                                  <!--   <div class="col-sm-6">
                                        <div class="m-b-30">
                                            <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></button>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="tabledata">
                                     <table class="table table-striped add-edit-table table-bordered" id="caltable">
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

                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $srno=$total=$gsttotal=$grand=$discount_total=0;
                                            ?>
                                            @foreach($quotitem as $item)
                                            <?php
                                            $srno++;
                                            ?>
                                            <?php
                $total=$total+$item->amount;
                $gsttotal=$gsttotal+$item->gst_amount;
                $grand=$grand+$item->grand_total;
                $discount_total=$discount_total+$item->discount_amount;
                ?>
                                            <tr id="row{{$srno}}">
                                                <td style="vertical-align: top !important;width: 20%">
                                                      <div class="input-group">
     <select class="form-control js-example-basic-single" onchange="get_product(this.value,{{$srno}})" name="product[]" id="product{{$srno}}" required>
                                                        <option value="{{$item->product}}">{{$item->product_name}}</option>
                                                        @foreach($product as $prod)
                                                        <option value="{{$prod->id}}">{{$prod->product_name}}</option>
                                                        @endforeach
                                                    </select>
    <div class="input-group-btn">
      <button class="btn btn-default btnproductsearch" onclick="product_search({{$srno}})">
        <img src="{{asset('public/product_icon.png')}}" style="height:20px ">
      </button>
    </div>
  </div>

                                                    <div class="form-group">
                                                    <label>  </label>
                                                        <textarea id="description{{$srno}}" name="description[]" class="form-control">{{$item->description}}</textarea>

                                                </div>
                                                </td>

                                                <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="qty[]" onkeyup="cal(this)" value="{{$item->qty}}" class="qty form-control" id="qty{{$srno}}">
                                                </td>

                                                  <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="price[]" class="price form-control" value="{{$item->price}}" id="price{{$srno}}" oninput="cal(this)">
                                                </td>


                                                <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="total_amount[]" value="{{$item->amount}}" class="total form-control" id="total_amount{{$srno}}">
                                                </td>
                                                <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="discount_per[]" value="{{$item->discount_per}}" class="discount_per form-control" id="discount_per{{$srno}}" oninput="cal(this)">
                                                </td>
                                                <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="discount_amount[]" value="{{$item->discount_amount}}" class="discount_amount form-control" id="discount_amount{{$srno}}" oninput="cal(this)">
                                                </td>
                                                <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="gst_per[]" value="{{$item->gst_per}}" class="gst_per form-control" id="gst_per{{$srno}}" oninput="cal(this)">
                                                </td>
                                                <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="gst_amount[]" value="{{$item->gst_amount}}" class="gst_amount form-control" id="gst_amount{{$srno}}">
                                                </td>
                                                 <td style="vertical-align: top !important;text-align: center;">
                                                    <input type="text" name="net_price[]" value="{{$item->grand_total}}" class="netprice form-control" id="net_price{{$srno}}">
                                                </td>
                                                <td class="actions" style="vertical-align: top !important;text-align: center;">
                                                    <a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>

                                            </tr>
                                            @endforeach


                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="12">
                                                  <div class="col-md-2">
                                                         <a class="btn btn-default" id="add_product">+ Add Product</a>
                                                        </div>

                                                    <div class="col-md-2" style="display: none;">
                                                        <a class="btn btn-default" id="add_service">+ Add Service</a>
                                                     </div>
                                                         <input type="hidden" id="totrow" value="{{$srno}}">
                                                </td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                   <table class="table table-striped add-edit-table table-bordered" style="margin-left: 62%;width: 38%;">
                                        <tr>
                                                <td colspan="10" style="text-align: right;">Item Total (+)</td><td style="text-align: right;"><input type="text" class="form-control item_total" name="item_total" id="item_total" value="{{$total}}"></td><td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="10" style="text-align: right;">Discount Total (-)</td><td style="text-align: right;"><input type="text" class="form-control discount_total" name="discount_total" id="discount_total" value="{{$discount_total}}"></td><td></td>
                                            </tr>

                                             <tr>
                                                <td colspan="10" style="text-align: right;">GST Total (+)</td><td style="text-align: right;"><input type="text" class="form-control gsttotal" name="gsttotal" id="gsttotal" value="{{$gsttotal}}"></td><td></td>
                                            </tr>
                                             <tr>
                                                <td colspan="10" style="text-align: right;">Grand Total (+)</td><td style="text-align: right;"><input type="text" class="form-control grand_total" name="grand_total" id="grand_total" value="{{$grand}}"></td><td></td>
                                            </tr>
                                   </table>
                                </div>

                                            <div class="col-sm-12">

                                                <h3>Terms & Conditions</h3>

                                            </div>
                                             <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Module</label>
                                                                    {{Form::select('module',$module,null,['class'=>'form-control','id'=>'module'])}}
                                                         </div>
                                                     </div>

                                             <div class="col-md-12" id="terms">
                                                <div class="form-group">
                                                    <label>Terms & Conditions</label>
                                                    {{Form::textarea('term_condition',null,['class'=>'form-control','cols'=>'1','rows'=>'1','id'=>'term_condition'])}}

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

               @include('admin/footer')

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

                                <div class="card-box table-responsive">
                                    <input type="hidden" name="srid" id="srid">
                                    <table style="width: 100% !important" id="datatable-buttons" class="table table-striped table-bordered">
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
                                                <td style="width: 10%">{{$serarchprod->product_name}}</td>
                                                <td>{{$serarchprod->uom_name}}</td>
                                                <td>{{$serarchprod->price}}</td>
                                                <td>{{$serarchprod->gst_per}}</td>
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

                                <div class="card-box table-responsive">
                                    <input type="hidden" name="servicesrid" id="servicesrid">
                                    <table style="width: 100% !important" id="service_datatable-buttons" class="table table-striped table-bordered">
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
        </script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{asset('public/adminpanel/plugins/parsleyjs/parsley.min.js')}}"></script>

            <script type="text/javascript">
                $(document).ready(function() {
                    $('form').parsley();
                    getdate("Sd");
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
                    var customer=$("#customer").val();
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

$("#add_product").click(function(e){
    e.preventDefault();
     var i=$("#totrow").val();
      i++;

    var data="<tr id='row"+i+"'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_product(this.value,"+i+")' name='product[]' id='product"+i+"'> <option>select</option>@foreach($product as $prod)<option value='{{$prod->id}}'>{{$prod->product_name}}</option>@endforeach</select><div class='input-group-btn'><a class='btn btn-default product_btn'  onclick='product_search("+i+")'><img src='<?=asset('public/product_icon.png');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";


    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price'+i+'" oninput="cal(this)"></td>';
    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" class="discount_per form-control" oninput="cal(this)" id="discount_per'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'" oninput="cal(this)"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
    data +='<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

    $("#caltable").append(data);
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    $("#totrow").val(i);
});



$("#add_service").click(function(e){
e.preventDefault();
     var i=$("#totrow").val();
      i++;

    var data="<tr id='row"+i+"'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_service(this.value,"+i+")' name='product[]' id='product"+i+"'> <option>select</option>@foreach($service as $prod)<option value='{{$prod->id}}'>{{$prod->product_name}}</option>@endforeach</select><div class='input-group-btn'><a class='service_btn btn btn-default' onclick='service_search("+i+")'><img src='<?=asset('public/service_icon.jpg');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";
    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price'+i+'"></td>';
    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" class="discount_per form-control" oninput="cal(this)" id="discount_per'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
    data +='<td class="actions" style="vertical-align: top !important;text-align:center"><a style="cursor:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

    $("#caltable").append(data);
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    $("#totrow").val(i);
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
      dateFormat: 'dd-mm-yy'
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
