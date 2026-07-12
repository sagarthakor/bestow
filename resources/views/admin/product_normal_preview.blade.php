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
        <title>{{Session::get('software_title')}} - Product Add</title>

        <!-- Plugins css-->
        @extends('admin.form_header')
        <style type="text/css">
            .glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: arial !important;
    /* font-style: normal; */
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

        </style>
         <style type="text/css">
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            border-top:0px !important;
        }

    </style>
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
                                    <h4 class="page-title">Product Add </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Zircos</a>
                                        </li>
                                        <li>
                                            <a href="{{url('client/product/list')}}">Product List </a>
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
                                        <div class="col-xs-12">

                                            <div class="row">
                                                {{Form::model($data,['method'=>'post','route'=>'post.product_update','files'=>'true'])}}
                                                {{Form::hidden('id',null)}}

                                                <div class="col-md-12">
                                                    <div class="demo-box">
                                                      <table class="table table-borderless" style="border:0px !important">
                                                        <tr>
                                                             <td style="width: 20%">Product Name </td>
                                                             <td style="color: #222;"> {{$data->product_name}}</td>
                                                              <td style="width: 20%">Vendor</td>
                                                             <td style="color: #222;">  {{$vendor_name}}
                                                             </td>
                                                         </tr>

                                                         <tr>
                                                          <td style="width: 20%">Category</td>
                                                             <td style="color: #222;">{{$data->category_name}}</td>
                                                             <td style="width: 20%">Material </td>
                                                             <td style="color: #222;"> {{$data->material_name}}</td>
                                                              
                                                           
                                                         </tr>

                                                         <tr>
                                                            <td style="width: 20%">Sales Start Date</td>
                                                             <td style="color: #222;">  @if($data->sales_start_date=="1970-01-01")
                                                                     @else
                                                                     {{date('d-m-Y',strtotime($data->sales_start_date))}}
                                                                     @endif</td>
                                                             <td style="width: 20%">Sales End Date </td>
                                                             <td style="color: #222;"> @if($data->sales_end_date=="1970-01-01") 
                                                            @else
                                                            {{date('d-m-Y',strtotime($data->sales_end_date))}} @endif</td>
                                                           
                                                         </tr>

                                                          

                                                         <tr>
                                                             
                                                              <td style="width: 20%">HSN</td>
                                                             <td style="color: #222;">  {{$data->hsn}}
                                                             </td>
                                                              <td style="width: 20%">Unit Price </td>
                                                             <td style="color: #222;"> {{$data->price}}</td>
                                                         </tr>

                                                           <tr>
                                                            
                                                              <td style="width: 20%">GST %</td>
                                                             <td style="color: #222;">  {{$gstper}}
                                                                 <td style="width: 20%">Usage Unit </td>
                                                             <td style="color: #222;"> {{$data->uompar}}</td>
                                                             </td>
                                                         </tr>


                                                           <tr>
                                                            
                                                              <td style="width: 20%">Description</td>
                                                             <td colspan="3" style="color: #222;">  {{$data->description}}
                                                             </td>
                                                         </tr>

                                                        <tr>
                                                           <td>Image</td>
                                                           <td>@if(empty($data->category_image))
                                                                    <label>No Image Avalible</label>
                                                                    @else
                                                                    <label><img style="width: 65px;
    height: 65px;cursor: pointer;" src="{{asset('public/product_category/'.$data->category_image)}}" id="myImg" title="Preview"></label>
                                                                    @endif
                                                                  </td>
                                                         </tr>


                                                      </table>
                                                    



                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   {{Form::close()}}
                                    <!-- end row -->


                                    <!-- end row -->


                                </div> <!-- end card-box -->
                            </div><!-- end col-->

                        </div>
                        <!-- end row -->


                    </div> <!-- container -->

                </div> <!-- content -->

               <div class="modal" id="myModal1" role="dialog" style="left: 16%;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        
        <div class="modal-body">
           <img class="modal-content" id="img01">
        </div>
        <div class="modal-footer">
          
          <button type="button" class="btn btn-default" onclick="model_close()">Close</button>
        </div>
      </div>
    </div>
  </div>
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
                        <label>Vendor Name</label>
                        <input type="text" class="form-control" name="vendor_name" id="vendor_name">
                    </div>
                </div>
                 <div class="col-md-4">
                    <div class="form-group">
                        <label>Primary Email</label>
                        <input type="text" class="form-control" name="primary_email" id="primary_email">
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
            <button type="button" onclick="vendor_form()" class="btn btn-default">Go to full form</button>
             <button type="button" onclick="vendor_save()" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" onclick="model_close()">Close</button>
        </div>
      </div>
    </div>
  </div>
              @extends('admin.footer')

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
                function add_vendor()
                {
                    $("#myModal").show();
                }
                function model_close()
                {
                    $("#myModal").hide();
                    $("#myModal1").hide();
                }
                function vendor_save()
                {
                    var primary_phone=$("#primary_phone").val();
                    var primary_email=$("#primary_email").val();
                    var vendor_name=$("#vendor_name").val();
                    var appurl="{{url('/')}}";
                    $.ajax({
                        url:appurl+'/client/ajax_vendor_save',
                        data:{vendor_name:vendor_name,primary_email:primary_email,primary_phone:primary_phone},
                        method:'get',
                        success:function(res)
                        {
                            if(res=="1")
                            {
                                alert("error in vendor save");
                                $("#myModal").hide();
                            }else{
                                $("#vendor").html(res);
                                $("#myModal").hide();
                            }
                        }
                    });
                }
                function vendor_form()
                {
                    window.location="{{url('client/vendor/add')}}";
                }
            </script>

            <script>
// Get the modal
var modal = document.getElementById("myModal1");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("myImg");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script>
    </body>
</html>