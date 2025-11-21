@extends('admin.layout.master')

@section('title', 'Add Service')

@section('sidebar')
    @parent

@endsection

@section('content')
        <!-- Plugins css-->

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

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">


                        <div class="row">
                            <div class="col-xs-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Service Add </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url('client/service/list')}}">Service List </a>
                                        </li>
                                        <li>
                                            Add Service
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
                                                {{Form::open(['method'=>'post','route'=>'post.service_save','files'=>'true'])}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">
                                                        <div class="col-md-3">
                                                          <div class="form-group">
                                                                    <label class="control-label">Service Name</label>
                                                                    {{Form::text('product_name',null,["required",'class'=>'form-control'])}}

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                              <div class="form-group">
                                                                        <label class="control-label">Category</label>
                                                                        {{Form::select('category',$category,null,["required",'class'=>'form-control js-example-basic-single','id'=>'category'])}}

                                                                </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                          <div class="form-group">
                                                                    <label class="control-label">Sales Start Date</label>
                                                                    {{Form::date('sales_start_date',null,['class'=>'form-control','autocomplete'=>'off'])}}

                                                            </div>
                                                        </div>

                                                         <div class="col-md-3">
                                                              <div class="form-group">
                                                                        <label class="control-label">Sales End Date</label>
                                                                        {{Form::date('sales_end_date',null,['class'=>'form-control','autocomplete'=>'off'])}}

                                                              </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                              <div class="form-group">
                                                                        <label class="control-label">Vendor</label>
                                                                        {{Form::select('vendor',$vendor,null,['class'=>'form-control js-example-basic-single','id'=>'vendor'])}}
                                                                        <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_vendor()"> Add New</span></span>

                                                              </div>
                                                        </div>



                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="card-box">
                                    <div class="row">
                                        <div class="col-xs-12">

                                            <div class="row">

                                                       <div class="demo-box">


                                                        <div class="col-md-2">
                                                          <div class="form-group">
                                                                    <label class="control-label">Unit Price</label>
                                                                    {{Form::text('price',null,["required",'class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                          <div class="form-group">
                                                                    <label class="control-label">GST %</label>
                                                                    {{Form::select('gst',$gst,null,['class'=>'form-control js-example-basic-single'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                          <div class="form-group">
                                                                    <label class="control-label">UOM</label>
                                                                    {{Form::select('uom',$uom,null,['class'=>'form-control js-example-basic-single'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Description</label>
                                                                    {{Form::textarea('description',null,['class'=>'form-control','cols'=>'1','rows'=>'1'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <button class="btn btn-primary">Save</button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                {{Form::close()}}

                                            </div><!-- end row -->


                                        </div>

                                    </div>
                                    <!-- end row -->


                                    <!-- end row -->


                                </div> <!-- end card-box -->
                            </div><!-- end col-->

                        </div>
                        <!-- end row -->


                    </div> <!-- container -->

                </div> <!-- content -->
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
   @endsection
