@extends('admin.layout.master')

@section('title', 'Edit Service Renewal')

@section('sidebar')
    @parent

@endsection

@section('content')
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
                                <h4 class="page-title">Service Renewal Update</h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">Demo</a>
                                    </li>
                                    <li>
                                        <a href="{{url('client/service/renewal/list')}}">Service Renewal List </a>
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
                                            {{Form::model($data,['method'=>'post','route'=>'post.service_renewal_update','files'=>'true'])}}
                                            {{Form::hidden('id',null)}}
                                            <div class="col-md-12">
                                                <div class="demo-box">
                                                    <div class="col-md-3">
                                                      <div class="input-group">
                                                        <label class="control-label">Service Name</label>
                                                        {{Form::select('service',$service,null,['class'=>'form-control js-example-basic-single','required','id'=>'service'])}}

                                                        <div class="input-group-btn">
                <button type="button" class="btn btn-primary" id="service_btn" style="margin-top: 25px;background: #727579;color:#fff;">+
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                <label class="control-label">Organization Name</label>
                                                {{Form::select('customer',$customer,null,['class'=>'form-control js-example-basic-single','required','id'=>'customer'])}}
                                                 <div class="input-group-btn">
                <button type="button" class="btn btn-primary" id="organization_btn" style="margin-top: 25px;background: #727579;color:#fff;">+
                                                        </button>
                                                    </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="control-label">Usage unit </label>
                                            {{Form::select('usage_unit',$usage_unit,null,['class'=>'form-control js-example-basic-single','id'=>'usage_unit'])}}

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Category </label>
                                        {{Form::select('category',$category,null,['class'=>'form-control js-example-basic-single','id'=>'category'])}}

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
                            <label class="control-label">Support Start Date</label>
                            {{Form::date('support_start_date',null,['class'=>'form-control','autocomplete'=>'off'])}}

                        </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Support Expiry Date </label>
                        {{Form::date('support_expiry_date',null,['class'=>'form-control','autocomplete'=>'off'])}}

                    </div>
                </div>

                 <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Price</label>
                        {{Form::text('price',null,['class'=>'form-control','id'=>'price','autocomplete'=>'off'])}}

                    </div>
                </div>

                 <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Purchase Cost </label>
                        {{Form::text('purchase_cost',null,['class'=>'form-control','id'=>'purchase_cost','autocomplete'=>'off'])}}

                    </div>
                </div>

                <div class="col-md-12">
                    <button class="btn btn-primary">Submit</button>
                </div>


            </div>
        </div>
    </div>
</div>
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

<div class="modal" id="serviceModel" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" onclick="model_close()">&times;</button>
          <h4 class="modal-title">Quick Create Service</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Service Name</label>
                    <input type="text" class="form-control" name="model_service_name" id="model_service_name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" class="form-control" name="model_service_price" id="model_service_price">
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" onclick="service_form()" class="btn btn-default">Go to full form</button>
        <button type="button" onclick="service_save()" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" onclick="model_close()">Close</button>
    </div>
</div>
</div>
</div>

<div class="modal" id="organizationModel" role="dialog">
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
                    <label>Organization Name </label>
                    <input type="text" class="form-control" name="model_organization_name" id="model_organization_name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Website</label>
                    <input type="text" class="form-control" name="model_organization_website" id="model_organization_website">
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    <label>Primary Phone </label>
                    <input type="text" class="form-control" name="model_organization_phone" id="model_organization_phone">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" onclick="organization_form()" class="btn btn-default">Go to full form</button>
        <button type="button" onclick="organization_save()" class="btn btn-primary">Save</button>
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
        $('#serviceModel').hide();
        $("#organizationModel").hide();
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

   $("#service_btn").click(function(e){
        e.preventDefault();
         $("#serviceModel").show();
    });

   $("#organization_btn").click(function(e){
        e.preventDefault();
         $("#organizationModel").show();
    });

   function service_save()
   {
    var model_service_name=$("#model_service_name").val();
    var model_service_price=$("#model_service_price").val();
    var appurl="{{url('/')}}";

    if(model_service_name=="")
    {
        alert("please enter service name");
    }
    if(model_service_price=="")
    {
        alert("please enter service price");
    }
    if(model_service_name !='' && model_service_price !='')
    {
        $.ajax({
            url:appurl+'/client/ajax_service_save',
            data:{model_service_name:model_service_name,model_service_price:model_service_price},
            method:'get',
            success:function(res)
            {
                if(res=="1")
                {
                    alert("error in service save");
                    $('#serviceModel').hide();

                }else{
                    $("#service").html(res);
                    $('#serviceModel').hide();
                }
            }
        });
    }
}

function organization_save()
   {
    var organization_name=$("#model_organization_name").val();
    var website=$("#model_organization_website").val();
    var primary_phone=$("#model_organization_phone").val();
    var appurl="{{url('/')}}";

    if(organization_name=="")
    {
        alert("please enter organization name");
    }
    if(primary_phone=="")
    {
        alert("please enter organization phone");
    }
    if(organization_name !='' && primary_phone !='')
    {
        $.ajax({
            url:appurl+'/client/ajax_customer_save1',
            data:{organization_name:organization_name,website:website,primary_phone:primary_phone},
            method:'get',
            success:function(res)
            {
                $("#customer").html(res)
                $("#organizationModel").hide();
            }
        });
    }
}
</script>
@endsection
