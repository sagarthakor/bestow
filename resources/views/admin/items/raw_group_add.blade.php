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
                                        {{Form::open(['method'=>'post','route'=>'post.raw_material_group_save','files'=>'true'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Item Group Name</label>
                                                        {{Form::text('group_name',null,['class'=>'form-control','id'=>'group_name'])}}

                                                    </div>
                                                </div>

                                                                </div>


                                                            </div><!-- end row -->


                                                        </div>

                                                    </div>
                                                    <!-- end row -->


                                                    <!-- end row -->




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
