@extends('admin.layout.master')

@section('title', 'Edit | Product')

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
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .product-form-section {
            margin-bottom: 22px;
            border: 1px solid #eef0f2;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
        }

        .product-form-section .section-heading {
            background: #f4f8fc;
            border-left: 4px solid #188ae2;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 600;
            color: #313a46;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .product-form-section .section-body {
            padding: 18px 15px 4px 15px;
        }

        .product-form-section .form-group label.control-label {
            font-weight: 500;
            color: #4b5563;
        }

        #caltable thead th {
            background: #f4f8fc;
            vertical-align: middle;
        }

        #caltable .form-group {
            margin-bottom: 0;
        }

        .visibility-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .visibility-toggle label.toggle-title {
            font-weight: 500;
            margin: 0;
            min-width: 220px;
        }

        .form-actions {
            padding: 16px 0 20px 0;
        }

        .form-actions .btn {
            min-width: 120px;
        }

        .current-image-preview img {
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 3px;
            cursor: zoom-in;
            margin-right: 6px;
        }

        .variant-image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
        }

        .variant-image-preview img {
            height: 50px;
            width: 50px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
    </style>

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Product Update </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.product.list')}}">Product List </a>
                                </li>
                                <li>
                                    Edit Product
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
                                        {{Form::model($data,['method'=>'post','route'=>'post.product_update','files'=>'true','id'=>'my_form'])}}
                                        {{Form::hidden('id',null)}}
                                        <div class="col-md-12">

                                            <!-- Basic Information -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Basic Information</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Item Code <span style="color:red">*</span></label>
                                                                {{Form::text('item_code',null,['required','class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Bar Code <span style="color:red">*</span></label>
                                                                {{Form::text('bar_code',null,['required','class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Product Name <span style="color:red">*</span></label>
                                                                {{Form::text('product_name',null,['required','class'=>'form-control','placeholder'=>'Enter product name only'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Category</label>
                                                                {{Form::select('category',$category,null,['class'=>'form-control js-select2','id'=>'category'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Subcategory</label>
                                                                {{Form::select('subcategory',$subcategory,null,['class'=>'form-control','id'=>'subcategory'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Brand</label>
                                                                {{Form::select('brand',$brand,null,['class'=>'form-control js-select2','id'=>'brand'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Material & Composition -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Material</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Material</label>
                                                                {{Form::select('material',$material,null,['class'=>'form-control js-select2','id'=>'material','onchange'=>'gethsn()'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">HSN</label>
                                                                {{Form::text('hsn',null,['class'=>'form-control','id'=>'hsn'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">Usage Unit</label>
                                                                {{Form::select('uom',$uom,null,['class'=>'form-control js-select2'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pricing & Tax -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Pricing &amp; Tax</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Purchase Price <span style="color:red">*</span></label>
                                                                {{Form::text('purchase_price',null,['class'=>'form-control','placeholder'=>'unit price'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Selling Price <span style="color:red">*</span></label>
                                                                {{Form::text('price',null,['class'=>'form-control','placeholder'=>'unit price'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">GST %</label>
                                                                {{Form::select('gst',$gst,null,['class'=>'form-control js-select2'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Variant (Color / Size) -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Variant &mdash; Color / Size / Image</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="tabledata">
                                                            <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                                <thead>
                                                                <tr>
                                                                    <th style="text-align: center;">Attribute</th>
                                                                    <th style="text-align: center;">Option</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr class="gradeX" id="row1">
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <select onchange="getvariation(this)" class="form-control attribute" name="attribute1" id="">
                                                                                    <option value="{{$data->attribute1}}">{{$data->attribute1}}</option>
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
                                                                                    <option value="{{$data->value1}}">{{$data->value1}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                <tr class="gradeX" id="row1">
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <select onchange="getvariation(this)" class="form-control attribute" name="attribute2" id="">
                                                                                    <option value="{{$data->attribute2}}">{{$data->attribute2}}</option>
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
                                                                                    <option value="{{$data->value2}}">{{$data->value2}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Variant Images</td>
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                @php
                                                                                    $existingVariantImages = $data->variant_images ?: ($data->product_image ? [$data->product_image] : []);
                                                                                @endphp
                                                                                @if(count($existingVariantImages))
                                                                                    <div class="current-image-preview" style="margin-bottom:8px">
                                                                                        @foreach($existingVariantImages as $existingImg)
                                                                                            <img height="65px" width="65px" onclick="imgshow('/product_image/{{ $existingImg }}')" src="/product_image/{{ $existingImg }}">
                                                                                        @endforeach
                                                                                    </div>
                                                                                @endif
                                                                                <input type="file" class="form-control-file variant-image-input" name="attribute_image[]" multiple accept="image/*">
                                                                                <div class="variant-image-preview"></div>
                                                                                <small class="text-muted">New photos are added alongside the existing ones above.</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Description & Media -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Description &amp; Media</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Product Description <small>(shown on website)</small></label>
                                                                {{Form::textarea('product_description',null,['id'=>'product_description','class'=>'form-control','cols'=>'15','rows'=>'2'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Internal Notes <small>(not shown on website)</small></label>
                                                                {{Form::textarea('description',null,['id'=>'description','class'=>'form-control','cols'=>'15','rows'=>'2'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Visibility -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Website Visibility</div>
                                                <div class="section-body">
                                                    <div class="visibility-toggle">
                                                        <label class="toggle-title">Show product on website</label>
                                                        <input type="checkbox" id="switch1" name="show_hide" value="show" switch="none" {{ $data->show_hide=="show" ? 'checked' : '' }}>
                                                        <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                                    </div>

                                                    <div class="visibility-toggle">
                                                        <label class="toggle-title">Show price on website</label>
                                                        <input type="checkbox" name="price_show_hide" value="show" id="switch3" switch="bool" {{ $data->price_show_hide=="show" ? 'checked' : '' }}>
                                                        <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <button class="btn btn-primary" id="submitBtn" type="button">Update Product</button>
                                                <a href="{{route('admin.product.list')}}" class="btn btn-default">Cancel</a>
                                            </div>

                                        </div>
                                        {{Form::close()}}
                                    </div><!-- end row -->
                                </div>
                            </div>
                            <!-- end row -->
                        </div> <!-- end card-box -->
                    </div><!-- end col-->
                </div>
                <!-- end row -->
            </div> <!-- container -->
        </div> <!-- content -->
    </div>

    <div class="modal fade" id="myModal1" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="text-align:center">
                    <img class="img-responsive" id="img01" style="max-width:100%;max-height:70vh;margin:0 auto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        function getvariation(ele) {
            var attribute = $(ele).closest('tr').find('.attribute').val();
            var appurl = "{{ url('/') }}";
            $.ajax({
                url: appurl + '/admin/get_variation',
                data: {attribute: attribute},
                method: 'get',
                success: function (res) {
                    $(ele).closest('tr').find('.attribute_value').html(res);
                }
            });
        }

        $(document).on('change', '.variant-image-input', function () {
            var $preview = $(this).closest('.form-group').find('.variant-image-preview');
            $preview.html('');
            var files = this.files;
            for (var i = 0; i < files.length; i++) {
                var img = document.createElement('img');
                img.src = URL.createObjectURL(files[i]);
                $preview.append(img);
            }
        });

        $(document).ready(function () {

            $("#submitBtn").click(function(){
                $("#my_form").submit();
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

            $('.js-select2').select2();
        });
    </script>
    <script type="text/javascript">
        function model_close() {
            $("#myModal1").modal('hide');
        }

        function gethsn() {
            var appurl = "{{url('/')}}";
            var material = $("#material").val();
            $.ajax({
                url: appurl + '/client/ajax_get_hsn',
                data: {material: material},
                method: 'get',
                success: function (res) {
                    $("#hsn").val(res);
                }
            });
        }
    </script>
    <script>
        function imgshow(src) {
            $("#img01").attr("src", src);
            $("#myModal1").modal('show');
        }
    </script>
@endsection
