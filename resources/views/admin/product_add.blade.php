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
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        input[type=file] {
            display: inline;
        }

        #image_preview {
            border: 1px solid black;
            padding: 10px;
        }

        #image_preview img {
            width: 200px;
            padding: 5px;
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
                            <h4 class="page-title">Add New Product</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.product.list')}}">Product List </a>
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

                                            <!-- Basic Information -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Basic Information</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Item Code <span style="color:red">*</span></label>
                                                                {{Form::text('item_code',null,['required','class'=>'form-control','placeholder'=>'e.g. IC-1001'])}}
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
                                                                <label class="control-label">Category <span style="color:red">*</span></label>
                                                                {{Form::select('category',$category,null,['required','class'=>'form-control js-select2','id'=>'category'])}}
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
                                                                <label class="control-label">GST %</label>
                                                                {{Form::select('gst',$gst,null,['class'=>'form-control js-select2'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Variants -->
                                            <div class="product-form-section">
                                                <div class="section-heading">Variants &mdash; Color / Size / Price / Image</div>
                                                <div class="section-body">
                                                    <div class="row">
                                                        <div class="tabledata">
                                                            <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                                <thead>
                                                                <tr>
                                                                    <th style="text-align: center;">Color</th>
                                                                    <th style="text-align: center;">Size</th>
                                                                    <th style="text-align: center;">MRP</th>
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
                                                                                <select class="form-control attribute_value variant-tag-select" name="color[]">
                                                                                    <option value="">select color</option>
                                                                                    @foreach($color_value as $color_value1)
                                                                                        <option value="{{ $color_value1->variation_name }}">{{ $color_value1->variation_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <select class="form-control attribute_value variant-tag-select" name="size[]">
                                                                                    <option value="">select size</option>
                                                                                    @foreach($size_value as $size_value1)
                                                                                        <option value="{{ $size_value1->variation_name }}">{{ $size_value1->variation_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" name="mrp[]" placeholder="MRP">
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
                                                                                <input type="file" class="form-control-file variant-image-input" name="attribute_image[0][]" multiple accept="image/*">
                                                                                <div class="variant-image-preview"></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="col-md-2">
                                                                            <a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="Remove"><i class="fa fa-trash" style="font-size: 22px"></i></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <td colspan="6">
                                                                        <button style="float: right" id="btnattribute" class="btn btn-purple">+ Add More</button>
                                                                    </td>
                                                                </tr>
                                                                </tfoot>
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
                                                                {{Form::textarea('description',null,['class'=>'form-control','cols'=>'15','rows'=>'2'])}}
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
                                                        <input type="checkbox" id="switch1" name="show_hide" checked="" value="show" switch="none">
                                                        <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                                    </div>

                                                    <div class="visibility-toggle">
                                                        <label class="toggle-title">Show price on website</label>
                                                        <input type="checkbox" name="price_show_hide" value="show" id="switch3" switch="bool">
                                                        <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <button class="btn btn-primary" type="submit">Save Product</button>
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

    <script src="/adminpanel/default/assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript">

        function remove_row(ele) {
            if (confirm("Are you sure you want to delete this?")) {
                $(ele).closest('tr').remove();
            }
        }

        var variantRowIndex = 1;

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

        $(document).ready(function() {
            var editorImageUploadConfig = {
                ckfinder: {
                    uploadUrl: "{{ route('admin.editor.image.upload') }}"
                }
            };

            ClassicEditor
                .create(document.querySelector('#description'), editorImageUploadConfig)
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#product_description'), editorImageUploadConfig)
                .catch(error => {
                    console.error(error);
                });

            $("#btnattribute").click(function(e){
                e.preventDefault();
                var data;
                data ='<tr class="gradeX" id="row1">';
                data +='<td><div class="col-md-12"><div class="form-group">';
                data +='<select class="form-control attribute_value variant-tag-select" name="color[]" id="">';
                data +='<option value="">select color</option>';
                data +='@foreach($color_value as $val)<option value="{{ $val->variation_name }}">{{ $val->variation_name }}</option>@endforeach';
                data +='</select></div></td>';
                data +='<td><div class="col-md-12"><div class="form-group">';
                data +='<select class="form-control attribute_value variant-tag-select" name="size[]" id="">';
                data +='<option value="">select size</option>';
                data +='@foreach($size_value as $val1)<option value="{{ $val1->variation_name }}">{{ $val1->variation_name }}</option>@endforeach';
                data +='</select></div></td>';
                data +='<td><div class="col-md-12"><div class="form-group">';
                data +="<input type='text' name='mrp[]' class='form-control' placeholder='MRP'>";
                data +='</div></div></td>';
                data +='<td><div class="col-md-12"><div class="form-group">';
                data +="<input type='text' name='price[]' class='form-control'>";
                data +="</div></div><td><div class='col-md-12'><div class='form-group'><input type='file' name='attribute_image["+variantRowIndex+"][]' class='form-control-file variant-image-input' multiple accept='image/*'><div class='variant-image-preview'></div></div></div></td>"
                data +='<td><div class="col-md-2"><a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="Remove"><i class="fa fa-trash" style="font-size: 22px"></i></a>';
                data +='</div></td></tr>';
                variantRowIndex++;
                var $newRow = $(data);
                $("#caltable tbody").append($newRow);
                $newRow.find('.variant-tag-select').select2({tags: true, width: '100%'});
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

            $('.js-select2').select2();
            $('.variant-tag-select').select2({tags: true, width: '100%'});
        });
    </script>
    <script type="text/javascript">
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
