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
                                            Quotation Add
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->


                        {{Form::model($data,['method'=>'post','route'=>'admin.quotation.update'])}}
                        {{Form::hidden('id',null)}}
                        {{Form::hidden('quot_no',$data->quot_no)}}
                        <div class="panel">

                            <div class="panel-body">
                                <div class="row">
                                    <!-- <div class="col-md-4" style="display: none;" id="qno">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation No</label>
                                                                    {{Form::text('quot_no',null,['class'=>'form-control','id'=>"quot_no"])}}
                                                                   
                                                            </div>
                                                        </div> -->
                                     <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation Date</label>
                                                                    {{Form::text('quot_date',null,['class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}
                                                                   
                                                            </div>
                                                        </div>

                                                          <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Subject</label>
                                                                    {{Form::text('subject',null,['class'=>'form-control','id'=>"subject"])}}
                                                                   
                                                            </div>
                                                        </div>

                                    <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Organization Name</label>
                                                                    {{Form::select('customer',$customer,null,['class'=>'form-control js-example-basic-single','id'=>'customer','onchange'=>'getcustomer(this.value)'])}}
                                                                    <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_customer()"> Add New</span></span>
                                                                   
                                                            </div>
                                                        </div>

                                                         <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <?php
                                                                    $stage=array('' =>'select quote stage','Created'=>'Created','Delivered'=>'Delivered','Reviewed'=>'Reviewed','Accepted'=>'Accepted','Rejected'=>'Rejected','Quote Revision'=>'Quote Revision');
                                                                    ?>
                                                                    <label class="control-label">Quote Stage</label>
                                                                    {{Form::select('quot_stage',$stage,null,['class'=>'form-control js-example-basic-single','id'=>'quot_stage'])}}
                                                                    
                                                                   
                                                            </div>
                                                        </div>

                                                         <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Valid Until</label>
                                                                    {{Form::text('valid_until',null,['class'=>'form-control input-daterange-datepicker','id'=>"valid_until",'autocomplete'=>'off'])}}
                                                                   
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                
                                                <h3>Address Details</h3>

                                            </div>

                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Billing Address </label>
                                                    {{Form::textarea('billing_address',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'billing_address'])}}

                                                </div>
                                                
                                            </div>

                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Shipping Address </label>
                                                    {{Form::textarea('shipping_address',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'shipping_address'])}}

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Billing PO Box </label>

                                                    {{Form::text('billing_pobox',null,['class'=>'form-control','id'=>"billing_pobox"])}}

                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Shipping PO Box </label>

                                                    {{Form::text('shipping_pobox',null,['class'=>'form-control','id'=>"shipping_pobox"])}}

                                                </div>      
                                            </div>

                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Billing City  </label>

                                                    {{Form::text('billing_city',null,['class'=>'form-control','id'=>"billing_city"])}}

                                                </div>      
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shipping City</label>
                                                    {{Form::text('shipping_city',null,['class'=>'form-control','id'=>'shipping_city'])}}

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Billing State</label>

                                                    {{Form::text('billing_state',null,['class'=>'form-control','id'=>"billing_state"])}}

                                                </div>      
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shipping State</label>
                                                    {{Form::text('shipping_state',null,['class'=>'form-control','id'=>'shipping_state'])}}

                                                </div>
                                            </div>

                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Billing Postal Code </label>

                                                    {{Form::text('billing_postalcode',null,['class'=>'form-control','id'=>"billing_postalcode"])}}

                                                </div>      
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shipping Postal Code </label>
                                                    {{Form::text('shipping_postalcode',null,['class'=>'form-control','id'=>'shipping_postalcode'])}}

                                                </div>
                                            </div>


                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Billing Country</label>

                                                    {{Form::text('billing_country',null,['class'=>'form-control','id'=>"billing_country"])}}

                                                </div>      
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shipping Country</label>
                                                    {{Form::text('shipping_country',null,['class'=>'form-control','id'=>'shipping_country'])}}

                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                
                                                <h3>Terms & Conditions</h3>

                                            </div>
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Terms & Conditions</label>
                                                    {{Form::textarea('term_condition',$terms->description ?? '',['class'=>'form-control','cols'=>'1','rows'=>'1','id'=>'term_condition'])}}

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
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Selling Price</th>
                                                <th>Total</th>
                                                <th>GST %</th>
                                                <th>GST Amount</th>
                                                <th>Net Price</th>
                                              
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $srno=$total=$gsttotal=$grand=0;
                                            ?>
                                            @foreach($quotitem as $item)
                                            <?php
                                            $srno++;
                                            ?>
                                            <?php
                $total=$total+$item->amount;
                $gsttotal=$gsttotal+$item->gst_amount;
                $grand=$grand+$item->grand_total;
                ?>
                                            <tr id="row{{$srno}}">
                                                <td style="vertical-align: top !important;">
                                                      <div class="input-group">
     <select class="form-control" onchange="get_product(this.value,1)" name="product[]" id="product1">
                                                        <option value="{{$item->product}}">{{$item->product_name}}</option>
                                                        @foreach($product as $prod)
                                                        <option value="{{$prod->id}}">{{$prod->product_name}}</option>
                                                        @endforeach
                                                    </select>
    <div class="input-group-btn">
      <button class="btn btn-default" id="product_btn1" onclick="product_search(1)">
        <img src="{{asset('public/product_icon.png')}}" style="height:20px ">
      </button>
    </div>
  </div>

                                                    <div class="form-group">
                                                    <label>  </label>
                                                        <textarea id="description1" name="description[]" class="form-control">{{$item->description}}</textarea>
                                                  
                                                </div>
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="qty[]" onkeyup="cal(this)" value="{{$item->qty}}" class="qty form-control" id="qty{{$srno}}">
                                                </td>
                                              
                                                  <td style="vertical-align: top !important;">
                                                    <input type="text" name="price[]" class="price form-control" value="{{$item->price}}" id="price{{$srno}}">
                                                </td>


                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="total_amount[]" value="{{$item->amount}}" class="total form-control" id="total_amount{{$srno}}">
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="gst_per[]" value="{{$item->gst_per}}" class="gst_per form-control" id="gst_per{{$srno}}">
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="gst_amount[]" value="{{$item->gst_amount}}" class="gst_amount form-control" id="gst_amount{{$srno}}">
                                                </td>
                                                 <td style="vertical-align: top !important;">
                                                    <input type="text" name="net_price[]" value="{{$item->grand_total}}" class="netprice form-control" id="net_price{{$srno}}">
                                                </td>
                                                <td class="actions" style="vertical-align: top !important;">
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
                                                <td colspan="8"> 
                                                  <div class="col-md-2">
                                                         <button class="btn btn-default" id="add_product">+ Add Product</button>
                                                        </div>
                                                
                                                    <div class="col-md-2">
                                                        <button class="btn btn-default" id="add_service">+ Add Service</button>
                                                     </div>
                                                         <input type="hidden" id="totrow" value="{{$srno}}">
                                                </td>
                                            </tr>
                                           <tr>
                                                <td colspan="6" style="text-align: right;">Item Total</td><td style="text-align: right;"><input type="text" class="form-control item_total" name="item_total" id="item_total" value="{{$total}}"></td><td></td>
                                            </tr>
                                             <tr>
                                                <td colspan="6" style="text-align: right;">Tax Total</td><td style="text-align: right;"><input type="text" class="form-control gsttotal" name="gsttotal" id="gsttotal" value="{{$gsttotal}}"></td><td></td>
                                            </tr>
                                             <tr>
                                                <td colspan="6" style="text-align: right;">Grand Total</td><td style="text-align: right;"><input type="text" class="form-control grand_total" name="grand_total" id="grand_total" value="{{$grand}}"></td><td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                 
                                     
                                  
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
                    2016 © Zircos.
                </footer>

            </div>


            <!-- MODAL -->
        
            <!-- end Modal -->

<div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" onclick="model_close()">&times;</button>
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
            <button type="button" onclick="customer_form()" class="btn btn-default">Go to full form</button>
             <button type="button" onclick="customer_save()" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" onclick="model_close()">Close</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal" id="product_model" role="dialog" style="width: 100% !important">
    <div class="modal-dialog modal-lg" style="width: 80% !important">
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
                                                <td style="width: 10%">{{$serarchservice->service_name}}</td>
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
 
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
               <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
            </script>
            <script type="text/javascript">
               

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
                                $("#description"+srno).val(data[0]['description']);
                                $("#price"+srno).val(data[0]['price']);
                                $("#gst_per"+srno).val(data[0]['gst']);
                                
                                //$("#make"+srno).val(data[0]['make']);
                            }
                        }
                    });
                }

                function get_service(product,srno)
                {
                    var appurl="{{url('/')}}";
                    $.ajax({
                        url:appurl+'/admin/get_service',
                        data:{service:product},
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

                    var gst_per = $(ele).closest('tr').find('.gst_per').val();
                   // alert(gst_per);
                    var gst_amount=Number(total)*Number(gst_per)/100;
                    //alert(amount);
                    $(ele).closest('tr').find('.gst_amount').val(Math.round(gst_amount));

                    var netprice=Number(total)+Number(gst_amount);

                    $(ele).closest('tr').find('.netprice').val(Math.round(netprice));
                    
                    var totalss = $(".total");
                    var item_total=0;
                    for(var i = 0; i < totalss.length; i++){
                      item_total=Number(item_total)+Number($(totalss[i]).val());
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
                    $("#gsttotal").val(gst_total);
                    $("#grand_total").val(netpricestotal);
                }

                function save_quot(srno)
                {
                    var product=$("#product"+srno).val();
                    var make=$("#make"+srno).val();
                    var model=$("#model"+srno).val();
                    var rate=$("#rate"+srno).val();
                    var qty=$("#qty"+srno).val();
                    var amount=$("#amount1"+srno).val();
                    var gst_per=$("#gst_per"+srno).val();
                    var gst_amount=$("#gst_amount"+srno).val();
                    var total_amount=$("#total_amount"+srno).val();
                    var quot_date=$("#quot_date").val();
                    var appurl="{{url('/')}}";
                    var quot_no=$("#quot_no").val();
                    var quot_date=$("#quot_date").val();
                    var customer=$("#customer").val();

                    $.ajax({
                        url:appurl+'/admin/save_product',
                        data:{product:product,make:make,model:model,rate:rate,qty:qty,amount:amount,gst_per:gst_per,gst_amount:gst_amount,total_amount:total_amount,'_token':"{{csrf_token()}}",quot_date:quot_date,quot_no:quot_no,customer:customer,quot_date:quot_date},
                        method:'post',
                        success:function(res)
                        {
                            $(".tabledata").html(res);
                        }
                    });
                }

                function update_quot(srno,itemid)
                {
                    var product=$("#product"+srno).val();
                    var make=$("#make"+srno).val();
                    var model=$("#model"+srno).val();
                    var rate=$("#rate"+srno).val();
                    var qty=$("#qty"+srno).val();
                    var amount=$("#amount1"+srno).val();
                    var gst_per=$("#gst_per"+srno).val();
                    var gst_amount=$("#gst_amount"+srno).val();
                    var total_amount=$("#total_amount"+srno).val();
                    var quot_date=$("#quot_date").val();
                    var appurl="{{url('/')}}";
                    var quot_no=$("#quot_no").val();
                    var quot_date=$("#quot_date").val();
                    var customer=$("#customer").val();

                    $.ajax({
                        url:appurl+'/admin/update_quot_item',
                        data:{product:product,make:make,model:model,rate:rate,qty:qty,amount:amount,gst_per:gst_per,gst_amount:gst_amount,total_amount:total_amount,'_token':"{{csrf_token()}}",quot_date:quot_date,quot_no:quot_no,customer:customer,quot_date:quot_date,itemid:itemid},
                        method:'post',
                        success:function(res)
                        {
                            $(".tabledata").html(res);
                        }
                    });
                }

                function quot_print()
                {
                    var quot_no=$("#quot_no").val();
                   if( quot_no === undefined) {
                    alert("Save quot item first after you will be print quotation");
                   }else{
                    var appurl="{{url('/')}}";
                    window.location=appurl+'/admin/quot_print/'+quot_no;
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

                

  $( function() {
    $( "#quot_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );

   $( function() {
    $( "#valid_until" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );
  </script>
  <script>
   
      ClassicEditor
    .create( document.querySelector( '#term_condition' ) )
    .catch( error => {
        console.error( error );
    } );

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
        data:{service:id},
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
    data +='<td style="vertical-align: top !important;"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
    data +='<td style="vertical-align: top !important;"><input type="text" name="price[]" class="price form-control" id="price'+i+'"></td>';
    data +='<td style="vertical-align: top !important;"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'"></td>';

    data +='<td style="vertical-align: top !important;"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
    data +='<td class="actions" style="vertical-align: top !important;"><a onclick="remove_row(this)" class="rowremove">Remove</a></td></tr>';
    
    $("#caltable").append(data);
    
    $("#totrow").val(i);
});


$("#add_service").click(function(e){
e.preventDefault();
     var i=$("#totrow").val();
      i++;

    var data="<tr id='row"+i+"'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_service(this.value,"+i+")' name='product[]' id='product"+i+"'> <option>select</option>@foreach($service as $prod)<option value='{{$prod->id}}'>{{$prod->service_name}}</option>@endforeach</select><div class='input-group-btn'><a class='service_btn btn btn-default' onclick='service_search("+i+")'><img src='<?=asset('public/service_icon.jpg');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";
    data +='<td style="vertical-align: top !important;"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
    data +='<td style="vertical-align: top !important;"><input type="text" name="price[]" class="price form-control" id="price'+i+'"></td>';
    data +='<td style="vertical-align: top !important;"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'"></td>';

    data +='<td style="vertical-align: top !important;"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

    data +='<td style="vertical-align: top !important;"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
    data +='<td class="actions" style="vertical-align: top !important;"><a onclick="remove_row(this)" class="rowremove">Remove</a></td></tr>';
    
    $("#caltable").append(data);
    
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
   $(ele).closest('tr').remove();

   var totalss = $(".total");
                    var item_total=0;
                    for(var i = 0; i < totalss.length; i++){
                      item_total=Number(item_total)+Number($(totalss[i]).val());
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
                    $("#gsttotal").val(gst_total);
                    $("#grand_total").val(netpricestotal);
}
$(".rowremove").click(function(){
   // alert("sdd");
            $(this).parent().parent().remove();
        });
</script>
  
<script type="text/javascript">
    $(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#datatable-buttons tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#datatable-buttons').DataTable({
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    });
 
} );
</script>
  @extends('admin.table_footer_script')
    </body>
</html>