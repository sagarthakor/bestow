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
        <title>{{Session::get('software_title')}} - Quotaion Add</title>

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
                                                                    <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_vendor()"> Add New</span></span>
                                                                   
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
                                                    {{Form::textarea('billing_address',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'editor'])}}

                                                </div>
                                                
                                            </div>

                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Shipping Address </label>
                                                    {{Form::textarea('shipping_address',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'editor2'])}}

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
                                                    {{Form::text('shipping_country',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                
                                                <h3>Terms & Conditions</h3>

                                            </div>
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Terms & Conditions</label>
                                                    {{Form::textarea('term_condition',null,['class'=>'form-control','cols'=>'1','rows'=>'1','id'=>'term_condition'])}}

                                                </div>
                                            </div>
                                                        
                                  <!--   <div class="col-sm-6">
                                        <div class="m-b-30">
                                            <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></button>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="tabledata">
                                    <table class="table table-striped add-edit-table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Selling Price</th>
                                                <th>Total</th>
                                                <th>Net Price</th>
                                              
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="gradeX">
                                                <td>
                                                    <select class="form-control" onchange="get_product(this.value,1)" name="product[]" id="product1">
                                                        <option>select</option>
                                                        @foreach($product as $prod)
                                                        <option value="{{$prod->id}}">{{$prod->product_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>
                                                        <textarea id="description1" name="description1"></textarea>
                                                    </label>
                                                </td>
                                               
                                                <td>
                                                    <input type="text" name="qty[]" oninput="cal(this.value,1)" class="form-control" id="qty1">
                                                </td>
                                                <td>
                                                    <input type="text" name="amount[]" class="form-control" id="amount1">
                                                </td>
                                               <td>
                                                    <input type="text" name="gst_per[]" class="form-control" id="gst_per1">
                                                </td>
                                                <td>
                                                    <input type="text" name="gst_amount[]" class="form-control" id="gst_amount1">
                                                </td>
                                                <td>
                                                    <input type="text" name="total_amount[]" class="form-control" id="total_amount1">
                                                </td>
                                                <td class="actions">
                                                    <a href="#" onclick="save_quot(1)" class="on-editing save-row" title="save"><i class="fa fa-save" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>
                                            </tr>
                                         
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end: page -->

                        </div> <!-- end Panel -->
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                             <button style="text-align: center;" class="btn btn-primary" onclick="quot_print()">Print</button>
                            </div>
                        </div>
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

            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
          
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->



      @extends("admin.form_fotter")

<script src="{{asset('public/adminpanel/default/assets/js/jquery-1.12.4.js')}}"></script>
  <script src="{{asset('public/adminpanel/default/assets/js/jquery-ui.js')}}"></script>
 
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
                            //alert(data);
                             var len = data.length;
                            if (len > 0) {
                                var name = data[0]['name'];
                                //alert(data[0]['name']);
                                var address = data[0]['address'];
                                var mobile = data[0]['mobile'];
                                var email = data[0]['email'];
                                var city = data[0]['city'];
                                var state = data[0]['state'];
                                var country = data[0]['country'];
                                var gst = data[0]['gst'];

                                $("#address").val(address);
                                $("#mobile").val(mobile);
                                $("#email").val(email);
                                $("#city").val(city);
                                $("#state").val(state);
                                $("#country").val(country);
                                $("#gst").val(gst);
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
                                $("#make"+srno).val(data[0]['make']);
                                $("#model"+srno).val(data[0]['model']);
                                $("#rate"+srno).val(data[0]['price']);
                                $("#gst_per"+srno).val(data[0]['gst']);
                                //$("#make"+srno).val(data[0]['make']);
                            }
                        }
                    });
                }

                function cal(qty,srno)
                {
                   var rate=$("#rate"+srno).val();
                   var amount=Number(qty)*Number(rate);

                   $("#amount"+srno).val(Math.round(amount));
                   var gstper=$("#gst_per"+srno).val();
                   var gst_amount=Number(amount)*Number(gstper)/100;
                   $("#gst_amount"+srno).val(Math.round(gst_amount));
                   $("#total_amount"+srno).val(Math.round(amount)+Math.round(gst_amount));
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
                        success:function(res)
                        {
                            if(res=="1")
                            {
                                alert("error in customer save");
                                $("#myModal").hide();
                            }else{
                                $("#customer").html(res);
                                $("#myModal").hide();
                            }
                        }
                    });
                }
                function customer_form()
                {
                    window.location="{{url('customer-list')}}";
                }
            </script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
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
    .create( document.querySelector( '#editor' ) )
    .catch( error => {
        console.error( error );
    } );

    ClassicEditor
    .create( document.querySelector( '#editor2' ) )
    .catch( error => {
        console.error( error );
    } );

    ClassicEditor
    .create( document.querySelector( '#editor3' ) )
    .catch( error => {
        console.error( error );
    } );
      ClassicEditor
    .create( document.querySelector( '#term_condition' ) )
    .catch( error => {
        console.error( error );
    } );

</script>
    </body>
</html>