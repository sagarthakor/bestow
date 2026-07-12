@extends('admin.layout.master')

@section('title', 'Add New | Item Group')

@section('sidebar')
    @parent

@endsection

@section('content')

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Group Create</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('client/product/category')}}">Group List </a>
                                </li>
                                <li>
                                    Add Group
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
                                        {{Form::open(['method'=>'post','route'=>'post.item_group_save','files'=>'true'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Item Group Name</label>
                                                        {{Form::text('group_name',null,['class'=>'form-control','id'=>'group_name'])}}

                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label><button style="margin-top: 30px;" id="generate_url" class="btn btn-sm">Get Url</button></label>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Category</label>
                                                        {{Form::select('category',$category,null,['class'=>'form-control js-example-basic-single','id'=>'category'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Subcategory</label>
                                                        {{Form::select('subcategory',[''=>'select subcategory'],null,['class'=>'form-control js-example-basic-single','id'=>'subcategory'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Material</label>
                                                        {{Form::select('material',$material,null,['class'=>'form-control js-example-basic-single','id'=>'material','onchange'=>'gethsn()'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Manufacturer</label>
                                                        {{Form::select('manufacturer',$manufacturer,null,['class'=>'form-control js-example-basic-single','id'=>'manufacturer'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Brand</label>
                                                        {{Form::select('brand',$brand,null,['class'=>'form-control js-example-basic-single','id'=>'brand'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Group Price</label>
                                                        {{Form::text('group_price',null,['class'=>'form-control','id'=>'group_price'])}}
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
                                                        <label class="control-label">GST %</label>
                                                        {{Form::select('gst',$gst,null,['class'=>'form-control js-example-basic-single'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Usage Unit</label>
                                                        {{Form::select('uom',$uom,null,['tabindex'=>'13','class'=>'form-control js-example-basic-single'])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Product URL</label>
                                                        {{Form::text('product_url',null,['class'=>'form-control','id'=>"product_url"])}}

                                                    </div>
                                                </div>

                                                <div class="tabledata">
                                                    <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                                        <thead>
                                                        <tr>
                                                            <th style="text-align: center;">Attribute</th>
                                                            <th>Action</th>
                                                        </tr>

                                                        </thead>
                                                        <tbody>
                                                        <tr class="gradeX" id="row1">
                                                            <td>
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <select onchange="getvariation(this.value)" class="form-control attribute" name="product_attribute[]" id="">
                                                                            <option value="">select attribute</option>
                                                                            @foreach($attribute as $val)
                                                                                <option value="{{ $val->id }}">{{ $val->attribute_name }}</option>
                                                                            @endforeach
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
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="col-md-12">
                                                                    <button style="float: right" id="btnattribute" class="btn btn-purple">+ Add</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>


                                                </div>

                                                <div class="col-md-12">
                                                    <button style="width: 100%;margin-bottom: 10px" class="btn btn-purple" id="newvariation">Show Variation</button>
                                                    <div class="variationdata" style="display: none">
{{--                                                        <input type="checkbox" onchange="price_regular()" id="regular_price" name="regular_price">Select Regular Price--}}
                                                        <table class="table table-striped add-edit-table table-bordered" id="variationtable">
                                                            <tbody>

                                                            </tbody>

                                                        </table>
                                                        <div class="col-md-12">
                                                            <button style="float: right" id="btnvariation" class="btn btn-purple">+ Add</button>
                                                        </div>
                                                    </div>
                                                </div>






                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Primary Image</label>
                                                            {{Form::file('primary_image',null,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Description</label>
                                                                                <br>
                                                                                Enter a concise description of the product. This will be displayed below the product’s title on your website’s product page.
                                                                                {{Form::textarea('description',null,['tabindex'=>'16','class'=>'form-control','style'=>'height: normal !important;','rows'=>'2','cols'=>'15'])}}
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label">Product Details</label>
                                                                                <br>
                                                                                Highlight your product’s functionality and features. These will be displayed on the product’s page.
                                                                                {{Form::textarea('product_description',null,['tabindex'=>'16','class'=>'form-control','style'=>'height: normal !important;','rows'=>'2','cols'=>'15'])}}
                                                                            </div>
                                                                        </div>


{{--                                                                        <div class="col-md-12">--}}
{{--                                                                            <div class="form-group">--}}
{{--                                                                                <label class="control-label">Product Image</label>--}}
{{--                                                                                {{Form::file('product_image',['tabindex'=>'17','class'=>'form-control'])}}--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}

{{--                                                                        <div class="col-md-12">--}}
{{--                                                                            <div class="form-group">--}}
{{--                                                                                <label class="control-label">Remark</label>--}}
{{--                                                                                {{Form::textarea('remark',null,['tabindex'=>'18','class'=>'form-control','style'=>'height: normal !important;','rows'=>'2','cols'=>'15'])}}--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}




                                                                </div>


                                                            </div><!-- end row -->


                                                        </div>

                                                    </div>
                                                    <!-- end row -->


                                                    <!-- end row -->


                                                </div>


                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        {{Form::close()}}


                    </div><!-- end col-->

                </div>
                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->

        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
        <script>
            function getvariation(colourname)
            {
                var appurl="{{ url('/') }}";
                $.ajax({
                    url:appurl+'/getvariationvalue',
                    data:{colourid:colourname},
                    method:'get',
                    success:function(res)
                    {
                        // $(".images_section").append(res);
                    }
                });
            }
            $(document).ready(function(){

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

                $(".options").change(function (){
                    alert("dd");
                });
                $("#generate_url").click(function(e){
                    e.preventDefault();

                    var appurl="{{ url('/') }}";
                    var product_name=$("#group_name").val();
                    if(product_name=="")
                    {
                        alert("Please enter product name");
                    }else{
                        $.ajax({
                            url:appurl+'/generate_url',
                            data:{product_name:product_name},
                            method:'get',
                            success:function(result)
                            {
                                $("#product_url").val(result);
                            }
                        });
                    }

                });

                $("#newvariation").click(function(e){
                    e.preventDefault();
                    var txt= $("#newvariation").text();
                    if(txt=="Show Variation")
                    {
                        $("#newattribute").text("Hide Variation");
                    }else{
                        $("#newvariation").text("Show Variation");
                    }
                    $(".variationdata").fadeToggle();
                    var arr = [];
                    i = 0;
                    $("#caltable tbody").find("tr").each(function() {
                        arr[i++] =ratingTdText = $(this).find('.attribute').val();


                    });
                    var appurl="{{ url('/') }}";
                    $.ajax({
                        url:appurl+'/erp/getvariation',
                        data:{attribute:arr},
                        method:'get',
                        success:function(res)
                        {
                            $("#variationtable").append(res);
                        }
                    });

                });

                $("#btnvariation").click(function(e){
                    e.preventDefault();

                    var arr = [];
                    i = 0;
                    $("#caltable tbody").find("tr").each(function() {
                        arr[i++] =ratingTdText = $(this).find('.attribute').val();


                    });
                    var appurl="{{ url('/') }}";
                    $.ajax({
                        url:appurl+'/erp/getvariation',
                        data:{attribute:arr},
                        method:'get',
                        success:function(res)
                        {
                            $("#variationtable").append(res);
                        }
                    });
                });

                $("#newattribute").click(function(e){
                    e.preventDefault();
                    var txt= $("#newattribute").text();
                    if(txt=="Show Attribute")
                    {
                        $("#newattribute").text("Hide Attribute");
                    }else{
                        $("#newattribute").text("Show Attribute");
                    }

                    $(".tabledata").fadeToggle();
                });

                $("#btnattribute").click(function(e){
                    e.preventDefault();
                    var data;
                    data ='<tr class="gradeX" id="row1">';
                    data +='<td><div class="col-md-10"><div class="form-group">';
                    data +='<select class="form-control attribute" onchange="getvariation(this.value)" name="product_attribute[]" id="">';
                    data +='<option value="">select attribute</option>';
                    data +='@foreach($attribute as $val)<option value="{{ $val->id }}">{{ $val->attribute_name }}</option>@endforeach';
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
            });

            function price_regular()
            {
                var totalss = $(".variation_price");
                var price=$("#price").val();

                if ($("#regular_price").is(":checked")== true) {
                    $("#variationtable tr").find(".variation_price").each(function(){
                        $(".variation_price").val(price);
                    });
                }
                else if ($("#regular_price").is(":checked")== false) {
                    $("#variationtable tr").find(".variation_price").each(function(){
                        $(".variation_price").val(0);
                    });
                }

            }
            function remove_row(ele)
            {
                if(confirm("Are you sure you want to delete this?"))
                {
                    $(ele).closest('tr').remove();
                }else{
                    return false;
                }
            }
        </script>
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>

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
