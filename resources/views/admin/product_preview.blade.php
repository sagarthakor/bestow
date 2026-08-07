@extends('admin.layout.table_master')

@section('title', 'Preview | Product')

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
                                <h4 class="page-title">Product Add </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">{{Session::get('software_title')}}</a>
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
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#home">Details</a></li>
                                        <li><a data-toggle="tab" href="#quot">Customer</a></li>
                                        <!--  <li><a data-toggle="tab" href="#menu2">Vendor</a></li> -->
                                        <ul class="nav navbar-nav navbar-right">
                                          <li><a href="{{url('client/product/edit/'.$data->id)}}"><span class="glyphicon glyphicon-user"></span>Edit</a></li>
                                          <li><a onclick="return confirm('Are you sure you want to delete this item?');" href="{{url('product_delete/'.$data->id)}}"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                                      </ul>
                                  </ul>

                                  <div class="tab-content">
                                      <div id="home" class="tab-pane fade in active">
                                        <div class="col-xs-12">
                                            
                                             <div class="row">
                                                 <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Item Code</label>
                                        <div class="col-sm-6">
                                         {{$data->item_code}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Product Name</label>
                                        <div class="col-sm-6">
                                         <x-product-name :row="$data" />
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Category</label>
                                        <div class="col-sm-6">
                                         {{$data->category_name}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                                    
                                     <div class="row">
                                                 <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Subcategory</label>
                                        <div class="col-sm-6">
                                         {{$data->subcategory_name}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Brand</label>
                                        <div class="col-sm-6">
                                         {{$data->brand_name}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Category</label>
                                        <div class="col-sm-6">
                                         {{$data->material_name}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                                    
                                    <div class="row">
                                                 <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Manufacturer</label>
                                        <div class="col-sm-6">
                                         {{$data->manufacturer_name}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Importer</label>
                                        <div class="col-sm-6">
                                         {{$data->iname}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Packer</label>
                                        <div class="col-sm-6">
                                         {{$data->pname}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                                    
                                     <div class="row">
                                                 <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Purchase Price</label>
                                        <div class="col-sm-6">
                                         {{$data->purchase_price}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Selling Price</label>
                                        <div class="col-sm-6">
                                         {{$data->price}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">GST</label>
                                        <div class="col-sm-6">
                                         {{$data->gst_per}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                      <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">HSN</label>
                                        <div class="col-sm-6">
                                         {{$data->hsn}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                     <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">UOM</label>
                                        <div class="col-sm-6">
                                         {{$data->uom_name}}
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Product Image</label>
                                        <div class="col-sm-6">
                                         <a target="_blank" href="{{asset('public/product_image/'.$data->product_image)}}">
                                             <img height="60px" src="{{asset('public/product_image/'.$data->product_image)}}">
                                        </a>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    
                                    </div>
                                    
                                    
                                    
                                            <div class="row">
                                              
                                            <div class="col-md-12">
                                                <label>Product Multiple Images</label>
                                             @php    
                                                            $img=json_decode($product_multi_image->product_image);
                                                        @endphp
                                                        <br>
                                                        @foreach($img as $imgs)
                                                            <img height="150px" src="{{asset('public/product_image/'.$imgs)}}">
                                                            <a href="{{url('product-img-delete/'.$data->item_code.'/'.$imgs)}}" class="btn btn-sm btn-danger">Delete</a>
                                                        @endforeach
                                            </div>
                                                
                                            </div>
                            </div>
                        </div>

                        <div id="quot" class="tab-pane fade">
                           <iframe style="width: 100%;height: 1000px" src="{{url('client/product_quot_list/'.$data->id)}}" frameborder="0">
                           </iframe>
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
    window.open(this.src, '_blank');
   // window.location=this.src;

}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
</script>
@endsection
