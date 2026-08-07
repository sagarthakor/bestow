@extends('admin.layout.master')

@section('title', 'Edit BOM')

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
    </head>


    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">BOM Edit </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('client/bom/list')}}">BOM List </a>
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
                            <!--   @if ($errors->any())
                                <div class="col-xs-12">
                                    <div class="alert alert-danger">
                                        <ul>
@foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                                @endforeach
                                    </ul>
                                </div>
                            </div>

@endif -->
                                <div class="col-xs-12">

                                    <div class="row">
                                        {{Form::model($bom,['method'=>'post','route'=>'post.bom_update','files'=>'true'])}}
                                        {{Form::hidden('id',null)}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-12">

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">BOM Name</label>
                                                        {{Form::text('product_name',null,['class'=>'form-control'.$errors->first('product_name',' error')])}}
                                                        @if($errors->has('product_name'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Bar Code</label>
                                                            {{Form::text('bar_code',null,['required','class'=>'form-control'.$errors->first('bar_code',' error')])}}
                                                            @if($errors->has('bar_code'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Category</label>
                                                        {{Form::select('category',$category,null,['class'=>'form-control js-example-basic-single','id'=>'category'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Subcategory</label>
                                                        {{Form::select('subcategory',$subcategory,null,['class'=>'form-control js-example-basic-single','id'=>'subcategory'])}}

                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Material</label>
                                                        {{Form::select('material',$material,null,['class'=>'form-control js-example-basic-single','id'=>'material','onchange'=>'gethsn()'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Manufacturer</label>
                                                        {{   Form::select('manufacturer',$manufacturer,null,['class'=>'form-control js-example-basic-single','id'=>'manufacturer'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Brand</label>
                                                        {{Form::select('brand',$brand,null,['class'=>'form-control js-example-basic-single','id'=>'brand'])}}

                                                    </div>
                                                </div>


{{--                                                <div class="col-md-2">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="control-label">ID</label>--}}
{{--                                                        {{Form::text('inner_diameter',null,['class'=>'form-control'])}}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-2">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="control-label">OD</label>--}}
{{--                                                        {{Form::text('outer_diameter',null,['class'=>'form-control'])}}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-1">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="control-label">Thikness</label>--}}
{{--                                                        {{Form::text('thikness',null,['class'=>'form-control'])}}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">HSN</label>
                                                        {{Form::text('hsn',null,['class'=>'form-control','id'=>'hsn'])}}
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Price <span
                                                                style="color:red"> *</span></label>
                                                        {{Form::text('price',null,['class'=>'form-control'.$errors->first('price',' error'),'placeholder'=>'unit price'])}}
                                                        @if($errors->has('price'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">GST %</label>
                                                        {{Form::select('gst',$gst,null,['class'=>'form-control js-example-basic-single'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">UOM</label>
                                                        {{Form::select('uom',$uom,null,['class'=>'form-control js-example-basic-single'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3" >
                                                    <div class="form-group">
                                                        <label class="control-label">SKU <span style="color:red"> *</span></label>
                                                        {{Form::text('sku',null,['class'=>'form-control','placeholder'=>'sku'])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-3" >
                                                    <div class="form-group">
                                                        <label class="control-label">BOM Image <span style="color:red"> *</span></label>
                                                        {{Form::file('product_image',['class'=>'form-control'])}}
                                                        @if($bom->product_image !="")
                                                            <img src="{{asset('public/product_image/'.$bom->product_image)}}" style="height: 100px;width: 100px" class="img img-responsive">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="tabledata">
                                                            <table
                                                                class="table table-striped add-edit-table table-bordered"
                                                                id="caltable1">
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
                                                                                    <select
                                                                                        onchange="getvariation(this)"
                                                                                        class="form-control attribute"
                                                                                        name="attribute1"
                                                                                        id="">
                                                                                        <option
                                                                                            value="{{$bom->attribute1}}">{{$bom->attribute1}}</option>
                                                                                        @foreach($attribute as $val)
                                                                                            @if($val->attribute_name=="Colour")
                                                                                                <option
                                                                                                    value="{{ $val->attribute_name }}">{{ $val->attribute_name }}</option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                            </div>

                                                                        </td>

                                                                        <td>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <select
                                                                                        class="form-control attribute_value"
                                                                                        name="value1" id="">
                                                                                        <option value="{{$bom->value1}}">{{$bom->value1}}</option>

                                                                                    </select>
                                                                                </div>

                                                                            </div>

                                                                        </td>

                                                                        <td>
                                                                            <div class="col-md-2">
                                                                                <a onclick="remove_row(this)"
                                                                                   style="cursor: pointer;"
                                                                                   class="on-editing save-row"
                                                                                   title="save"><i class="fa fa-trash"
                                                                                                   style="font-size: 22px"></i></a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr class="gradeX" id="row1">
                                                                        <td>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <select
                                                                                        onchange="getvariation(this)"
                                                                                        class="form-control attribute"
                                                                                        name="attribute2"
                                                                                        id="">
                                                                                        <option
                                                                                            value="{{$bom->attribute2}}">{{$bom->attribute2}}</option>
                                                                                        @foreach($attribute as $val)
                                                                                            @if($val->attribute_name=="Size")
                                                                                            <option
                                                                                                value="{{ $val->attribute_name }}">{{ $val->attribute_name }}</option>
                                                                                            @endif

                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>

                                                                            </div>

                                                                        </td>

                                                                        <td>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <select
                                                                                        class="form-control attribute_value"
                                                                                        name="value2" id="">
                                                                                        <option value="{{$bom->value2}}">{{$bom->value2}}</option>

                                                                                    </select>
                                                                                </div>

                                                                            </div>

                                                                        </td>

                                                                        <td>
                                                                            <div class="col-md-2">
                                                                                <a onclick="remove_row(this)"
                                                                                   style="cursor: pointer;"
                                                                                   class="on-editing save-row"
                                                                                   title="save"><i class="fa fa-trash"
                                                                                                   style="font-size: 22px"></i></a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>


                                                                </tbody>
{{--                                                                <tfoot>--}}
{{--                                                                <tr>--}}
{{--                                                                    <td colspan="3">--}}
{{--                                                                        <div class="col-md-12">--}}
{{--                                                                            <button style="float: right"--}}
{{--                                                                                    id="btnattribute"--}}
{{--                                                                                    class="btn btn-purple">+ Add More--}}
{{--                                                                            </button>--}}
{{--                                                                        </div>--}}
{{--                                                                    </td>--}}
{{--                                                                </tr>--}}
{{--                                                                </tfoot>--}}
                                                            </table>


                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tabledata">
                                                    <table class="table table-striped add-edit-table table-bordered"
                                                           id="caltable">
                                                        <thead>

                                                        <tr>
                                                            <th style="text-align: center;">Product Name</th>
{{--                                                            <th style="text-align: center;">ID</th>--}}
{{--                                                            <th style="text-align: center;">OD</th>--}}
{{--                                                            <th style="text-align: center;">THK</th>--}}
                                                            <th style="text-align: center;">HSN</th>
                                                            <th style="text-align: center;">Qty</th>
                                                            <th style="text-align: center;">UOM</th>
                                                            <th style="text-align: center;display:none">Price</th>
                                                            <th style="text-align: center;display:none">Net Amount</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($item as $item)
                                                            <tr class="gradeX" id="row1">
                                                                <td style="width: 30%">
                                                                    <div class="form-group">
                                                                        <select onchange="get_product(this)"
                                                                                name="product[]" id="product1"
                                                                                class="product form-control"
                                                                                required>
                                                                            <option
                                                                                value="{{$item->product}}" selected><x-product-name :row="$item" /> - Stock - {{$item->stockqty}}</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label> </label>
                                                                        <textarea style="width: 100%;" class="description"
                                                                                  id="description1"
                                                                                  name="p_description[]">{{$item->description}}</textarea>

                                                                    </div>
                                                                </td>
{{--                                                                <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                    <input type="text" name="p_inner_diamitter[]"--}}
{{--                                                                           id="inner_diamitter1"--}}
{{--                                                                           class="form-control inner_diamitter"--}}
{{--                                                                           value="{{$item->inner_daimitter}}">--}}
{{--                                                                </td>--}}
{{--                                                                <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                    <input type="text" name="p_outer_diamitter[]"--}}
{{--                                                                           id="outer_diamitter1"--}}
{{--                                                                           class="form-control outer_diamitter"--}}
{{--                                                                           value="{{$item->outer_daimitter}}">--}}
{{--                                                                </td>--}}
{{--                                                                <td style="vertical-align: top !important;text-align: center;">--}}
{{--                                                                    <input type="text" name="p_thikness[]"--}}
{{--                                                                           id="thikness1" class="form-control thikness"--}}
{{--                                                                           value="{{$item->thikness}}">--}}
{{--                                                                </td>--}}
                                                                <td style="vertical-align: top !important;text-align: center;">
                                                                    <input type="text" name="p_hsn[]" id="hsn1"
                                                                           class="form-control hsn"
                                                                           value="{{$item->hsn}}">
                                                                </td>
                                                                <td style="vertical-align: top !important;text-align: center;">
                                                                    <input type="text" name="p_qty[]"
                                                                           onkeyup="cal(this)" class="qty form-control"
                                                                           id="qty1" value="{{$item->qty}}">
                                                                    <span class="stockQty">Stock : {{$item->stockqty ?? 0}}</span>
                                                                </td>

                                                                <td style="vertical-align: top !important;text-align: center;">
                                                                    <input type="text" name="p_uom[]"
                                                                           onkeyup="cal(this)" class="uom form-control"
                                                                           id="uom1" value="{{$item->uom}}">
                                                                </td>

                                                                <td style="vertical-align: top !important;text-align: center;display:none">
                                                                    <input type="text" name="p_price[]"
                                                                           class="price form-control" id="price1"
                                                                           onkeyup="cal(this)" value="{{$item->price}}">
                                                                </td>

                                                                <td style="vertical-align: top !important;text-align: center;display:none">
                                                                    <input type="text" name="p_amount[]"
                                                                           class="total form-control" id="total_amount1"
                                                                           onkeyup="cal(this)"
                                                                           value="{{$item->amount}}">
                                                                </td>

                                                                <td class="actions"
                                                                    style="vertical-align: top !important;text-align: center;">
                                                                    <a onclick="remove_row(this)"
                                                                       style="cursor: pointer;"
                                                                       class="on-editing save-row" title="save"><i
                                                                            class="fa fa-trash"
                                                                            style="font-size: 22px"></i></a>
                                                                    <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                                    <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                                     <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>

                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="14">
                                                                <div class="col-md-2">
                                                                    <a class="btn btn-default" id="add_product">+ Add
                                                                        Product</a>
                                                                </div>


                                                                <input type="hidden" id="totrow" value="1">
                                                            </td>
                                                        </tr>

                                                        </tfoot>
                                                    </table>
                                                    <table class="display:none;table table-striped add-edit-table table-bordered"
                                                           style="margin-left: 62%;width: 38%;">
                                                        <tr>
                                                            <td colspan="11" style="text-align: right;display:none">Item Total (+)
                                                            </td>
                                                            <td colspan="2" style="text-align: right;display:none">
                                                                {{Form::text('item_total',null,['class'=>'form-control item_total','id'=>'item_total'])}}

                                                            </td>
                                                        </tr>

                                                    </table>




                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Product Description (display on website)</label>
                                                            {{Form::textarea('product_description',null,['id'=>'product_description','class'=>'form-control','cols'=>'15','rows'=>'2'])}}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Description (For internal use)</label><br>
                                                            {{Form::textarea('bom_description',null,['class'=>'',"style"=>"width:100%",'cols'=>'2','rows'=>'2'])}}
                                                        </div>
                                                    </div>


                                                    @if($bom->show_hide=="show")
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>product visible on website</label><br>
                                                                <input type="checkbox" id="switch1" name="show_hide" checked="" value="show" switch="none">
                                                                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($bom->show_hide=="hide")
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>product visible on website</label><br>
                                                                <input type="checkbox" id="switch1" name="show_hide" value="show" switch="none">
                                                                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($bom->price_show_hide=="show")
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>price visible on website</label><br>
                                                                <input type="checkbox" name="price_show_hide" checked="" value="show" id="switch3" switch="bool">
                                                                <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($bom->price_show_hide=="hide")
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>price visible on website</label><br>
                                                                <input type="checkbox" name="price_show_hide" value="show" id="switch3" switch="bool">
                                                                <label for="switch3" data-on-label="Yes" data-off-label="No"></label>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <button class="btn btn-primary">Save</button>


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


        <script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {

                $("select.product").each(function () {
                    initProductAjaxSelect2($(this), 'product');
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
        <script type="text/javascript">
            $(document).ready(function () {
                $('.js-example-basic-single').select2();
            });
            $(document).ready(function () {
                $('form').parsley();
                // /getdate("Sd");
            });
            $(function () {
                $('#demo-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.alert-info').toggleClass('hidden', !ok);
                    $('.alert-warning').toggleClass('hidden', ok);
                })
                    .on('form:submit', function () {
                        return false; // Don't submit form for this demo
                    });
            });

            $("#quot_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'mm/dd/yy',
                onClose: function () {
                    getdate($(this).val());
                }
            });
            $("#valid_until").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });


            var cust = $("#customer").val();
            getcustomer(cust);


            $("#module").change(function () {
                var modules = $("#module").val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_terms',
                    data: {module: modules},
                    method: 'get',
                    success: function (data) {
                        $("#terms").html(data);

                        ClassicEditor
                            .create(document.querySelector('#term_condition'))
                            .catch(error => {
                                console.error(error);
                            });

                    }
                })
            })

            function getcustomer(customer) {
                var appurl = "{{url('/')}}";

                $.ajax({
                    url: appurl + '/admin/get_customer',
                    data: {customer: customer},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
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

                getcontact(customer);
            }

            function getcontact(customer) {
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_contact',
                    data: {customer: customer},
                    method: 'get',
                    success: function (data) {
                        $("#contact_name").html(data);
                    }
                });
            }


            function getvariation(ele) {
                var attribute = $(ele).closest('tr').find('.attribute').val();
                //alert(attribute);
                var appurl = "{{ url('/') }}";
                $.ajax({
                    url: appurl + '/get_variation',
                    data: {attribute: attribute},
                    method: 'get',
                    success: function (res) {
                        $(ele).closest('tr').find('.attribute_value').html(res);
                    }
                });
            }

            function get_product(ele) {
     var customer = $("#customer").val();
     if(customer==""){
         alert("please select customer first");
     }else {
         var product = $(ele).closest('tr').find('.product').val();
         var appurl = "{{url('/')}}";
         $(ele).closest('tr').find('.qty').val("");
         $(ele).closest('tr').find('.total').val("");
         $(ele).closest('tr').find('.discount_per').val("");
         $(ele).closest('tr').find('.discount_amount').val("");
         $(ele).closest('tr').find('.cgst_per').val("");
         $(ele).closest('tr').find('.cgst_amount').val("");
         $(ele).closest('tr').find('.sgst_per').val("");
         $(ele).closest('tr').find('.sgst_amount').val("");
         $(ele).closest('tr').find('.gst_per').val("");
         $(ele).closest('tr').find('.gst_amount').val("");
         $(ele).closest('tr').find('.netprice').val("");
         $(ele).closest('tr').find('.stock').val(0);

         $.ajax({
             url: appurl + '/invoice/get_products',
             data: {product: product,customer:customer},
             method: 'get',
             dataType: 'json',
             success: function (data) {
                 var len = data.length;
                 if (len > 0) {
                     $(ele).closest('tr').find('.description').val(data[0]['description']);
                     $(ele).closest('tr').find('.price').val(data[0]['price']);
                     $(ele).closest('tr').find('.gst_per').val(data[0]['gst']);
                     $(ele).closest('tr').find('.cgst_per').val(data[0]['cgst']);
                     $(ele).closest('tr').find('.sgst_per').val(data[0]['sgst']);

                     $(ele).closest('tr').find('.inner_diamitter').val(data[0]['inner_diameter']);
                     $(ele).closest('tr').find(".outer_diamitter").val(data[0]['outer_diameter']);
                     $(ele).closest('tr').find(".thikness").val(data[0]['thikness']);
                     $(ele).closest('tr').find(".hsn").val(data[0]['hsn']);
                     $(ele).closest('tr').find(".hsnSpan").val(data[0]['hsn']);
                     $(ele).closest('tr').find(".uom").val(data[0]['uom']);
                     $(ele).closest('tr').find(".uomSpan").val(data[0]['hsn']);
                     // alert(data[0]['stockqty']);
                     $(ele).closest('tr').find(".stockqty").val(data[0]['stockqty']);
                     $(ele).closest('tr').find(".stock").val(data[0]['stockqty']);

                    //  if(data[0]['stockqty'] == 0)
                    //  {
                    //      alert("This item not in stock first add in stock after you will make this item delivery challan");

                    //      setTimeout(function() {   //calls click event after a certain time
                    //          $(ele).closest('tr').remove();
                    //      }, 1000);
                    //  }
                     //$("#make"+srno).val(data[0]['make']);
                     $(ele).closest('tr').find(".stockQty").text("Stock : "+data[0]["stockqty"]);
                     $(ele).closest('tr').find(".photo").attr('src','{{asset('public/product_image/')}}/'+data[0]['product_image']);
                     //$("#make"+srno).val(data[0]['make']);
                 }
             }
         });
     }
 }


            function get_service(product, srno) {
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_product',
                    data: {product: product},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var len = data.length;
                        if (len > 0) {
                            $("#qty" + srno).val("");
                            $("#price" + srno).val("");
                            $("#total_amount" + srno).val("");
                            $("#discount_per" + srno).val("");
                            $("#discount_amount" + srno).val("");
                            $("#gst_per" + srno).val("");
                            $("#gst_amount" + srno).val("");

                            $("#description" + srno).val(data[0]['description']);
                            $("#price" + srno).val(data[0]['price']);
                            $("#gst_per" + srno).val(data[0]['gst']);
                            $("#inner_diamitter" + srno).val(data[0]['inner_diameter']);
                            $("#outer_diamitter" + srno).val(data[0]['outer_diameter']);
                            $("#thikness" + srno).val(data[0]['thikness']);
                            $("#hsn" + srno).val(data[0]['hsn']);
                            $("#discount_per" + srno).val(data[0]['discper']);

                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }

            function cal(ele) {
                var rate = $(ele).closest('tr').find('.price').val();
                var qty = $(ele).closest('tr').find('.qty').val();
                var total = Number(rate) * Number(qty);

                $(ele).closest('tr').find('.total').val(total);

                var discount_per = $(ele).closest('tr').find('.discount_per').val();

                var gst_per = $(ele).closest('tr').find('.gst_per').val();

                var discount_amount = Number(total) * Number(discount_per) / 100;

                var afterdisc = Number(total) - Number(discount_amount);

                $(ele).closest('tr').find('.discount_amount').val(discount_amount);

                // alert(gst_per);
                var gst_amount = Number(afterdisc) * Number(gst_per) / 100;
                //alert(gst_amount);
                $(ele).closest('tr').find('.gst_amount').val(gst_amount);

                var netprice = Number(afterdisc) + Number(gst_amount);

                $(ele).closest('tr').find('.netprice').val(netprice);

                var totalss = $(".total");
                var item_total = 0;
                for (var i = 0; i < totalss.length; i++) {
                    item_total = Number(item_total) + Number($(totalss[i]).val());
                }

                var discount_amount = $(".discount_amount");
                var discount_total = 0;
                for (var i = 0; i < discount_amount.length; i++) {
                    discount_total = Number(discount_total) + Number($(discount_amount[i]).val());
                }

                var gst_amounts = $(".gst_amount");
                var gst_total = 0;
                for (var i = 0; i < gst_amounts.length; i++) {
                    gst_total = Number(gst_total) + Number($(gst_amounts[i]).val());
                }

                var netprices = $(".netprice");
                var netpricestotal = 0;
                for (var i = 0; i < netprices.length; i++) {
                    netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                }


                $("#item_total").val(item_total);
                $("#discount_total").val(discount_total);
                $("#gsttotal").val(gst_total);
                $("#grand_total").val(netpricestotal.toFixed(2));

            }


            function model_close() {
                $("#service_model").hide();
                $("#myModal").hide();
            }

            function customer_save() {
                var organization_name = $("#organization_name").val();
                var website = $("#website").val();
                var primary_phone = $("#primary_phone").val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/client/ajax_customer_save',
                    data: {organization_name: organization_name, website: website, primary_phone: primary_phone},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var len = data.length;
                        if (len > 0) {
                            $("#primary_phone").val(data[0]['primary_phone']);
                            $("#website").val(data[0]['website']);
                            $("#customer").html(data[0]['str']);

                        }
                        $("#myModal").hide();
                    }
                });
            }

            function customer_form() {
                window.location = "{{url('customer_add')}}";
            }

            function service_search(srno) {
                $("#service_model").show();
                $("#servicesrid").val(srno);
            }

            // Search-as-you-type product picker: fetches only the matching
            // rows from the server instead of dumping the whole product
            // table (7000+ rows) into every row.
            function initProductAjaxSelect2($select, status) {
                $select.select2({
                    ajax: {
                        url: "{{ route('admin.product.search_options') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {term: params.term, status: status};
                        },
                        processResults: function (data) {
                            return data;
                        },
                        cache: true
                    },
                    minimumInputLength: 2,
                    placeholder: 'Type to search...',
                    width: '100%'
                });
            }
        </script>

        <script>

            $("#service_datatable-buttons").on('click', 'tr', function (e) {
                e.preventDefault();
                var id = $(this).attr('value');
                var srno = $("#servicesrid").val();
                get_product(id, srno)
                $("#service_model").hide();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/client/ajax_getservice',
                    data: {product: id},
                    method: 'get',
                    success: function (data) {
                        $("#product" + srno).html(data);
                    }
                });
            });

            $("#add_product").click(function(e){
                e.preventDefault();
                var i=$("#totrow").val();
                i++;

                var data="<tr id='row"+i+"'><td style='width: 20%'><div class='form-group'><select class='form-control product' onchange='get_product(this)' name='product[]' id='product"+i+"'> <option value=''>select</option></select></div><div class='form-group'><label></label><textarea style='width:100%' id='description"+i+"' name='p_description[]' class='description'></textarea></div></td>";
{{--                data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="p_inner_diamitter[]"  class="form-control inner_diamitter" id="inner_diamitter'+i+'"></td>';--}}
{{--                data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="p_outer_diamitter[]"  class="form-control outer_diamitter" id="outer_diamitter'+i+'"></td>';--}}
{{--                data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="p_thikness[]"  class="form-control thikness" id="thikness'+i+'"></td>';--}}
                data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="p_hsn[]"  class="form-control hsn" id="hsn'+i+'"></td>';

                data +='<td style="vertical-align: top !important;text-align:center"><input type="text" value="1" name="p_qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"><label class="stockQty">Stock : </label><input type="hidden" class="stock"></td>';

                data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="p_uom[]" oninput="cal(this)" class="uom form-control" id="uom'+i+'"></td>';

                data +='<td style="vertical-align: top !important;text-align:center;display:none"><input type="text" name="p_price[]" class="price form-control" id="price'+i+'" oninput="cal(this)"></td>';

                data +='<td style="vertical-align: top !important;text-align:center;display:none"><input type="text" name="p_amount[]" class="total form-control" id="total'+i+'" oninput="cal(this)"></td>';

                data +='<td class="actions" style="vertical-align: top !important;text-align:center;cursor:pointer"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);

                initProductAjaxSelect2($("#product" + i), 'product');

                $("#totrow").val(i);
            });


            $(".service_btn").click(function (e) {
                e.preventDefault();
            });
            $(".product_btn").click(function (e) {
                e.preventDefault();
            });
            $("#product_btn1").click(function (e) {
                e.preventDefault();
            });

            function add_customer() {
                $("#myModal").show();
            }

            function remove_row(ele) {
                if (confirm("Are you sure you want to delete this?")) {

                    $(ele).closest('tr').remove();

                    var totalss = $(".total");
                    var item_total = 0;
                    for (var i = 0; i < totalss.length; i++) {
                        item_total = Number(item_total) + Number($(totalss[i]).val());
                    }

                    var discount_amount = $(".discount_amount");
                    var discount_total = 0;
                    for (var i = 0; i < discount_amount.length; i++) {
                        discount_total = Number(discount_total) + Number($(discount_amount[i]).val());
                    }

                    var gst_amounts = $(".gst_amount");
                    var gst_total = 0;
                    for (var i = 0; i < gst_amounts.length; i++) {
                        gst_total = Number(gst_total) + Number($(gst_amounts[i]).val());
                    }

                    var netprices = $(".netprice");
                    var netpricestotal = 0;
                    for (var i = 0; i < netprices.length; i++) {
                        netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                    }


                    $("#item_total").val(item_total);
                    $("#discount_total").val(discount_total);
                    $("#gsttotal").val(gst_total);
                    $("#grand_total").val(netpricestotal);
                } else {
                    return false;
                }
            }

            $(".rowremove").click(function () {
                // alert("sdd");
                $(this).parent().parent().remove();
            });
        </script>

        <script type="text/javascript">
            function getdate(qdate) {
                //alert(qdate);
                var tt = document.getElementById('quot_date').value;
                var date = new Date(tt);
                var newdate = new Date(date);
                newdate.setDate(newdate.getDate() + 14);

                var dd = newdate.getDate();
                var mm = newdate.getMonth() + 1;
                var y = newdate.getFullYear();

                var someFormattedDate = dd + '-' + mm + '-' + y;
                document.getElementById('valid_until').value = someFormattedDate;

                var ndate = tt.split('/');
                var nmm = ndate[0];
                var ndd = ndate[1];
                var nyy = ndate[2];

                var newquotdate = ndd + '-' + nmm + '-' + nyy;

                $("#quot_date").val(newquotdate);
            }

            $.noConflict();
            jQuery(document).ready(function ($) {
                $("#quot_date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'mm/dd/yy',
                    onClose: function () {
                        getdate($(this).val());
                    }
                });
                $("#valid_until").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            });


        </script>
@endsection
