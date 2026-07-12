@extends('admin.layout.master')

@section('title', 'Edit | Raw Material')

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
                                    <h4 class="page-title">Raw Material  </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url('product/raw-material/list')}}">Raw Material List </a>
                                        </li>
                                        <li>
                                           Edit Raw Material
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
                                                {{Form::model($data,['method'=>'post','route'=>'post.raw_material_update','files'=>'true'])}}
                                                {{Form::hidden("id",null)}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Raw Material Group</label>
                                                                {{Form::select('raw_material_group',$raw_material_group,null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Product Group</label>
                                                                {{Form::select('status',['raw material'=>'Raw Material'],null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                            </div>
                                                        </div>

                                                         <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Item Code <span style="color:red"> *</span></label>
                                                                    {{Form::text('item_code',null,['required','class'=>'form-control'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Product Name <span style="color:red"> *</span></label>
                                                                    {{Form::text('product_name',null,['required','class'=>'form-control'])}}

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="display:none">
                                                              <div class="form-group">
                                                                        <label class="control-label">Category <span style="color:red"> *</span></label>
                                                                        {{Form::select('category',$category,null,['class'=>'form-control js-example-basic-single','id'=>'category'])}}

                                                                </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Subcategory</label>
                                                                {{Form::select('subcategory',[''=>'select subcategory'],null,['class'=>'form-control js-example-basic-single','id'=>'subcategory'])}}

                                                            </div>
                                                        </div>

                                                         <div class="col-md-4" style="display:none">
                                                              <div class="form-group">
                                                                        <label class="control-label">Material</label>
                                                                        {{Form::select('material',$material,null,['class'=>'form-control js-example-basic-single','id'=>'material','onchange'=>'gethsn()'])}}

                                                                </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Manufacturer</label>
                                                                {{Form::select('manufacturer',$manufacturer,null,['class'=>'form-control js-example-basic-single','id'=>'manufacturer'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Importer</label>
                                                                {{Form::select('importer',$importer,null,['class'=>'form-control js-example-basic-single','id'=>'importer'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                            <div class="form-group" >
                                                                <label class="control-label">Packer</label>
                                                                {{Form::select('packer',$packer,null,['class'=>'form-control js-example-basic-single','id'=>'packer'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Brand</label>
                                                                {{Form::select('brand',$brand,null,['class'=>'form-control js-example-basic-single','id'=>'brand'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none">
                                                          <div class="form-group">
                                                                    <label class="control-label">Sales Start Date</label>
                                                                    {{Form::date('sales_start_date',null,['class'=>'form-control','autocomplete'=>'off'])}}

                                                            </div>
                                                        </div>

                                                         <div class="col-md-4" style="display:none">
                                                              <div class="form-group">
                                                                        <label class="control-label">Sales End Date</label>
                                                                        {{Form::date('sales_end_date',null,['class'=>'form-control','autocomplete'=>'off'])}}

                                                              </div>
                                                        </div>

                                                        <div class="col-md-4" style="display:none;">
                                                              <div class="form-group">
                                                                        <label class="control-label">Vendor</label>
                                                                        {{Form::select('vendor',$vendor,null,['class'=>'form-control js-example-basic-single','id'=>'vendor'])}}
                                                                        <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_vendor()"> Add New</span></span>

                                                              </div>
                                                        </div>

                                                        <div class="col-md-2" >
                                                            <div class="form-group">
                                                                <label class="control-label">Purchase Price <span style="color:red"> *</span></label>
                                                                {{Form::text('purchase_price',null,['required','class'=>'form-control','placeholder'=>'unit price'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Selling Price <span style="color:red"> *</span></label>
                                                                {{Form::text('price',null,['class'=>'form-control','placeholder'=>'unit price'])}}
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
                                                                <label class="control-label">HSN</label>
                                                                {{Form::text('hsn',null,['class'=>'form-control','id'=>'hsn'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">SKU</label>
                                                                {{Form::text('sku',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">Usage Unit</label>
                                                                {{Form::select('uom',$uom,null,['class'=>'form-control js-example-basic-single'])}}
                                                            </div>
                                                        </div>





                                                        <div class="col-md-12" style="display:none">
                                                            <div class="row">
                                                                <div class="tabledata">
                                                                    <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                                        <thead>
                                                                        <tr>
                                                                            <th style="text-align: center;">Attribute</th>
                                                                            <th style="text-align: center;">Option</th>
                                                                            <th>Action</th>
                                                                        </tr>

                                                                        </thead>
                                                                        <tbody>
                                                                        <tr class="gradeX" id="row1">
                                                                            <td>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <select onchange="getvariation(this)" class="form-control attribute" name="attribute1" id="">
                                                                                            <option value="">select attribute</option>
                                                                                            @foreach($attribute as $val)
                                                                                                @if($val->attribute_name=="Colour")
                                                                                                <option value="{{ $val->attribute_name }}">{{ $val->attribute_name }}</option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>

                                                                                </div>

                                                                            </td>

                                                                            <td>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-control attribute_value" name="value1" id="">
                                                                                            <option value="">select option</option>

                                                                                        </select>
                                                                                    </div>

                                                                                </div>

                                                                            </td>

                                                                            <td>
                                                                                <div class="col-md-2">
                                                                                    <a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>

                                                                        <tr class="gradeX" id="row1">
                                                                            <td>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <select onchange="getvariation(this)" class="form-control attribute" name="attribute2" id="">
                                                                                            <option value="">select attribute</option>
                                                                                            @foreach($attribute as $val)
                                                                                                @if($val->attribute_name=="Size")
                                                                                                <option value="{{ $val->attribute_name }}">{{ $val->attribute_name }}</option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>

                                                                                </div>

                                                                            </td>

                                                                            <td>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-control attribute_value" name="value2" id="">
                                                                                            <option value="">select option</option>

                                                                                        </select>
                                                                                    </div>

                                                                                </div>

                                                                            </td>

                                                                            <td>
                                                                                <div class="col-md-2">
                                                                                    <a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>

                                                                        </tbody>
{{--                                                                        <tfoot>--}}
{{--                                                                        <tr>--}}
{{--                                                                            <td colspan="3">--}}
{{--                                                                                <div class="col-md-12">--}}
{{--                                                                                    <button style="float: right" id="btnattribute" class="btn btn-purple">+ Add More</button>--}}
{{--                                                                                </div>--}}
{{--                                                                            </td>--}}
{{--                                                                        </tr>--}}
{{--                                                                        </tfoot>--}}
                                                                    </table>


                                                                </div>
                                                            </div>
                                                        </div>

{{--                                                         <div class="col-md-1">--}}
{{--                                                          <div class="form-group">--}}
{{--                                                                    <label class="control-label">ID</label>--}}
{{--                                                                    {{Form::text('inner_diameter',null,['tabindex'=>'7','class'=>'form-control'])}}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="col-md-1">--}}
{{--                                                          <div class="form-group">--}}
{{--                                                                    <label class="control-label">OD</label>--}}
{{--                                                                    {{Form::text('outer_diameter',null,['tabindex'=>'8','class'=>'form-control'])}}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="col-md-1">--}}
{{--                                                          <div class="form-group">--}}
{{--                                                                    <label class="control-label">Thikness</label>--}}
{{--                                                                    {{Form::text('thikness',null,['tabindex'=>'9','class'=>'form-control'])}}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}











{{--                                                           <div class="col-md-2">--}}
{{--                                                               <div class="form-group">--}}
{{--                                                                   <label class="control-label">Opening Stock</label>--}}
{{--                                                                   {{Form::text('opening_stock',null,['tabindex'=>'15','class'=>'form-control'])}}--}}
{{--                                                               </div>--}}
{{--                                                           </div>--}}



                                                        <div class="col-md-12" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Product Description</label>
                                                                {{Form::textarea('product_description',null,['id'=>'product_description','class'=>'form-control','cols'=>'15','rows'=>'2'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Description (For internal use)</label>
                                                                {{Form::textarea('description',null,['class'=>'form-control','cols'=>'15','rows'=>'2'])}}
                                                            </div>
                                                        </div>

                                                         <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Raw Material Image</label>
                                                                    {{Form::file('product_image',['tabindex'=>'17','class'=>'form-control'])}}
                                                            </div>
                                                             @if(isset($data->product_image))

                                                                 <label>
                                                                     <img height="65px"
                                                                          onclick="imgshow(/product_image/{{$data->product_image}})"
                                                                          width="65px"
                                                                          src="/product_image/{{$data->product_image}}"
                                                                          id="myImg">
                                                                 </label>
                                                             @endif
                                                        </div>

{{--                                                           <div class="col-md-12">--}}
{{--                                                               <div class="form-group">--}}
{{--                                                                   <label class="control-label">Remark</label>--}}
{{--                                                                   {{Form::textarea('remark',null,['tabindex'=>'18','class'=>'form-control','style'=>'height: normal !important;','rows'=>'2','cols'=>'15'])}}--}}
{{--                                                               </div>--}}
{{--                                                           </div>--}}


                                                        <div class="col-md-12" style="display:none">
                                                            <div class="form-group">
                                                                <label>product visible on website</label><br>
                                                                <input type="checkbox" id="switch1" name="show_hide" value="show" switch="none">
                                                                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12" style="display:none">
                                                            <div class="form-group">
                                                                <label>price visible on website</label><br>
                                                                <input type="checkbox" name="price_show_hide" value="show" id="switch3" switch="bool">
                                                                <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <button class="btn btn-primary" tabindex="19">Save</button>
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

        <script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
               <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
            <script type="text/javascript">
                function status_change (status){

                    if(status=="raw material")
                    {
                        $(".tabledata").hide();
                    }else{
                        $(".tabledata").show();
                    }
                }

                function getvariation(ele)
                {
                    var attribute = $(ele).closest('tr').find('.attribute').val();
                    //alert(attribute);
                    var appurl="{{ url('/') }}";
                    $.ajax({
                        url:appurl+'/get_variation',
                        data:{attribute:attribute},
                        method:'get',
                        success:function(res)
                        {
                            $(ele).closest('tr').find('.attribute_value').html(res);
                        }
                    });
                }

                function remove_row(ele) {
                    if (confirm("Are you sure you want to delete this?")) {

                        $(ele).closest('tr').remove();
                    }
                }


                $(document).ready(function() {


                    ClassicEditor
                        .create(document.querySelector('#description'))
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#product_description'))
                        .catch(error => {
                            console.error(error);
                        });

                    $("#btnattribute").click(function(e){
                        e.preventDefault();
                        var data;
                        data ='<tr class="gradeX" id="row1">';
                        data +='<td><div class="col-md-12"><div class="form-group">';
                        data +='<select class="form-control attribute" onchange="getvariation(this)" name="product_attribute[]" id="">';
                        data +='<option value="">select attribute</option>';
                        data +='@foreach($attribute as $val)<option value="{{ $val->id }}">{{ $val->attribute_name }}</option>@endforeach';
                        data +='</select></div></td>';
                        data +='<td><div class="col-md-12"><div class="form-group">';
                        data +='<select class="form-control attribute_value" name="product_value[]" id="">';
                        data +='<option value="">select attribute</option>';
                        data +='</select></div></td>';
                        data +='<td><div class="col-md-2"><a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>';
                        data +='</div></td></tr>';
                        $("#caltable").append(data);
                    });

                    $("#category").change(function(){
                        var appurl="{{ url('/') }}";
                        var category=this.value;

                        $.ajax({
                            url:appurl+'/erp/getsubcategory',
                            data:{category:category},
                            method:'get',
                            success:function(res)
                            {
                                $("#subcategory").html(res);
                            }
                        });
                    });

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
                function gethsn()
                {
                    var appurl="{{url('/')}}";
                    var material=$("#material").val();
                    $.ajax({
                        url:appurl+'/client/ajax_get_hsn',
                        data:{material:material},
                        method:'get',
                        success:function(res)
                        {
                            $("#hsn").val(res);
                        }
                    });
                }


            </script>
   @endsection
