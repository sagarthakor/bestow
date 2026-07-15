@extends('admin.layout.master_material')

@section('title', 'Add New | Product')

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
    <style type="text/css">
        input[type=file]{
            display: inline;
        }
        #image_preview{
            border: 1px solid black;
            padding: 10px;
        }
        #image_preview img{
            width: 200px;
            padding: 5px;
        }
    </style>

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Add New Product</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('client/product/list')}}">Product List </a>
                                </li>
                                <li>
                                    Add New Product
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
                                        {{Form::open(['method'=>'post','route'=>'post.product_save','files'=>'true'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-4" style="display:none">
                                                    <div class="form-group">
                                                        <label class="control-label">Product Group</label>
                                                        {{Form::select('status',['product'=>'Finished Product','raw material'=>'Raw Material'],null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>


                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Item Code <span style="color:red"> *</span></label>
                                                        {{Form::text('item_code',null,['required','class'=>'form-control'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Bar Code <span style="color:red"> *</span></label>
                                                        {{Form::text('bar_code',null,['required','class'=>'form-control'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Product Name <span style="color:red"> *</span></label>
                                                        {{Form::text('product_name',null,['required','class'=>'form-control'])}}

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Category <span style="color:red"> *</span></label>
                                                        {{Form::select('category',$category,null,['required','class'=>'form-control','id'=>'category'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Subcategory</label>
                                                        {{Form::select('subcategory',[''=>'select subcategory'],null,['class'=>'form-control','id'=>'subcategory'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Brand</label>
                                                        {{Form::select('brand',$brand,null,['class'=>'form-control','id'=>'brand'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Material</label>
                                                        {{Form::select('material',$material,null,['class'=>'form-control','id'=>'material','onchange'=>'gethsn()'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Cotton</label>
                                                        {{Form::select('cotton',$cotton,null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Spendex</label>
                                                        {{Form::select('spendex',$spendex,null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Elastics</label>
                                                        {{Form::select('elastics',$elastics,null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Nylon</label>
                                                        {{Form::select('nylon',$nylon,null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Polyester</label>
                                                        {{Form::select('polyester',$polyester,null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">P.P Yarn</label>
                                                        {{Form::select('p_p_yarn',$P_P_Yarn,null,['class'=>'form-control','onchange'=>"status_change(this.value)"])}}
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manufacturer</label>
                                                        {{Form::select('manufacturer',$manufacturer,null,["required",'class'=>'form-control','id'=>'manufacturer'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Importer</label>
                                                        {{Form::select('importer',$importer,null,["required",'class'=>'form-control','id'=>'importer'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Packer</label>
                                                        {{Form::select('packer',$packer,null,["required",'class'=>'form-control','id'=>'packer'])}}

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
                                                        <label class="control-label">Purchase Price </label>
                                                        {{Form::text('purchase_price',null,['class'=>'form-control','placeholder'=>'unit price'])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-2" >
                                                    <div class="form-group">
                                                        <label class="control-label">Selling Price <span style="color:red"> *</span></label>
                                                        {{Form::text('price',null,['required','class'=>'form-control','placeholder'=>'unit price'])}}
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


                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="tabledata">
                                                            <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                                <thead>
                                                                <tr>
                                                                    <th style="text-align: center;">Color</th>
                                                                    <th style="text-align: center;">Size</th>
                                                                    <th style="text-align: center;">Price</th>
                                                                    <th style="text-align: center;">Image</th>
                                                                    <th>Action</th>
                                                                </tr>

                                                                </thead>
                                                                <tbody>
                                                                <tr class="gradeX" id="row1">

                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <select class="form-control attribute_value" name="color[]">
                                                                                    <option value="">select color</option>
                                                                                    @foreach($color_value as $color_value1)
                                                                                        <option>{{$color_value1 ->variation_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                        </div>

                                                                    </td>


                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <select class="form-control attribute_value" name="size[]">
                                                                                    <option value="">select size</option>
                                                                                    @foreach($size_value as $size_value1)
                                                                                        <option>{{$size_value1->variation_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" name="price[]">
                                                                            </div>

                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <input type="file" class="form-control-file " name="attribute_image[]">
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
                                                                <tfoot>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <div class="col-md-12">
                                                                            <button style="float: right" id="btnattribute" class="btn btn-purple">+ Add More</button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tfoot>
                                                            </table>


                                                        </div>
                                                    </div>
                                                </div>

                                                {{--                                                         <div class="col-md-1">--}}
                                                {{--                                                          <div class="form-group">--}}
                                                {{--                                                                    <label class="control-label">ID</label>--}}
                                                {{--                                                                    {{Form::text('inner_diameter',null,['class'=>'form-control'])}}--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}

                                                {{--                                                        <div class="col-md-1">--}}
                                                {{--                                                          <div class="form-group">--}}
                                                {{--                                                                    <label class="control-label">OD</label>--}}
                                                {{--                                                                    {{Form::text('outer_diameter',null,['class'=>'form-control'])}}--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}

                                                {{--                                                        <div class="col-md-1">--}}
                                                {{--                                                          <div class="form-group">--}}
                                                {{--                                                                    <label class="control-label">Thikness</label>--}}
                                                {{--                                                                    {{Form::text('thikness',null,['class'=>'form-control'])}}--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}




                                                {{--                                                           <div class="col-md-2">--}}
                                                {{--                                                               <div class="form-group">--}}
                                                {{--                                                                   <label class="control-label">Opening Stock</label>--}}
                                                {{--                                                                   {{Form::text('opening_stock',null,['class'=>'form-control'])}}--}}
                                                {{--                                                               </div>--}}
                                                {{--                                                           </div>--}}



                                                <div class="col-md-12">
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
                                                        <label class="control-label">Cover Image</label>
                                                        {{Form::file('product_image',['class'=>'form-control',"id"=>"single_image"])}}
                                                    </div>
                                                    <br/>
                                                    <div id="single_image_preview"></div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>product visible on website</label><br>
                                                        <input type="checkbox" id="switch1" name="show_hide" checked="" value="show" switch="none">
                                                        <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>price visible on website</label><br>
                                                        <input type="checkbox" name="price_show_hide" value="show" id="switch3" switch="bool">
                                                        <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Bom</label><br>
                                                        <input type="checkbox" name="bom" value="1" id="switch4" switch="bool">
                                                        <label for="switch4" data-on-label="Yes" data-off-label="No"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary" >Save</button>
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

    <script src="/adminpanel/default/assets/js/jquery.min.js"></script>
    <script type="text/javascript">
        $("#uploadFile").change(function(){
            $('#image_preview').html("");
            var total_file=document.getElementById("uploadFile").files.length;
            for(var i=0;i<total_file;i++)
            {
                $('#image_preview').append("<img height='150px' src='"+URL.createObjectURL(event.target.files[i])+"'>");
            }

        });
        $("#single_image").change(function(){
            $('#single_image_preview').html("");
            var total_file=document.getElementById("single_image").files.length;
            for(var i=0;i<total_file;i++)
            {
                $('#single_image_preview').append("<img height='150px' src='"+URL.createObjectURL(event.target.files[i])+"'>");
            }

        });

    </script>
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

        function getvariation1(attribute1)
        {
            // var attribute = $(ele).closest('tr').find('.attribute').val();
            //alert(attribute);
            var appurl="{{ url('/') }}";
            $.ajax({
                url:appurl+'/get_variation',
                data:{attribute:attribute1},
                method:'get',
                success:function(res)
                {
                    $('#value1').html(res);
                }
            });
        }

        function getvariation2(attribute2)
        {
            // var attribute = $(ele).closest('tr').find('.attribute').val();
            //alert(attribute);
            var appurl="{{ url('/') }}";
            $.ajax({
                url:appurl+'/get_variation',
                data:{attribute:attribute2},
                method:'get',
                success:function(res)
                {
                    $('#value2').html(res);
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
                data +='<select class="form-control attribute" onchange="getvariation(this)" name="color[]" id="">';
                data +='<option value="">select color</option>';
                data +='@foreach($color_value as $val)<option value="{{ $val->variation_name }}">{{ $val->variation_name }}</option>@endforeach';
                data +='</select></div></td>';
                data +='<td><div class="col-md-12"><div class="form-group">';
                data +='<select class="form-control attribute_value" name="size[]" id="">';
                data +='<option value="">select size</option>';
                data +='@foreach($size_value as $val1)<option value="{{ $val1->variation_name }}">{{ $val1->variation_name }}</option>@endforeach';
                data +='</select></div></td>';
                data +='<td><div class="col-md-12"><div class="form-group">';
                data +="<input type='text' name='price[]' class='form-control'>";
                data +="<td><div class='col-md-12'><div class='form-group'><input type='file' name='attribute_image[]' class='form-file-control'></td>"
                data +='<td><div class="col-md-2"><a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>';
                data +='</div></td></tr>';
                $("#caltable").append(data);
            });

            $("#category").change(function(){
                var appurl="{{ url('/') }}";
                var category=this.value;

                $.ajax({
                    url:appurl+'/admin/getsubcategory',
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
