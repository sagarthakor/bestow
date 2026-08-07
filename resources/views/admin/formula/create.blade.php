@extends('admin.layout.master_material')

@section('title', 'Add New | Formula')

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
                            <h4 class="page-title">Create Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.production.formula_list')}}">Formula List </a>
                                </li>
                                <li>
                                    Create New Formula
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->



                <div class="row">
                    <div class="col-xs-12">
                        {{Form::open(['method'=>'post','route'=>'admin.production.formula_item_save'])}}
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
                                @if(session::has("message"))
                                <div class="col-xs-12">
                                        <div class="alert alert-info">
                                            <ul><li>{{ session::get("message") }}</li></ul>
                                        </div>
                                </div>
                                @endif
                                <div class="col-xs-12">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Choose Required Raw Material for Formula</h3>
                                        </div>


                                            <input type="hidden" name="action" value="update">
                                            <?php
                                        $nosid="nos_";
                                        $nosid .=1;
                                        $sizeid="size_";
                                        $sizeid .=1;
                                        $colourid="colour_";
                                        $colourid .=1;
                                        $requiredmat="required_qty_";
                                        $requiredmat .=1;
                                        ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Product</label>
                                                        <select onchange="getimage(this.value,1)" id="finish_product_1" name="product" class="js-example-basic-single form-control">
                                                            <option value="">Select product</option>
                                                            @foreach($product as $prod)
                                                                <option value="{{$prod->id}}">{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}{{ $prod->value2 ? ' - Size ' . $prod->value2 : '' }}{{ $prod->value1 ? ' - ' . $prod->value1 : '' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" style="text-align:left">
                                                    <div class="form-group">
                                                        <label class="control-label">Photo</label>
                                                        <img src="" name="photo" data-id="1" class="photo img_1 img-responsive">
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Colour</label>
                                                        {{Form::text('colour',null,['readonly','oninput'=>"getmaterial(this.value,1)",'class'=>'form-control','id'=>$colourid])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Size</label>
                                                        {{Form::text('size',null,['readonly','oninput'=>"getmaterial(this.value,1)",'class'=>'form-control','id'=>$sizeid])}}

                                                    </div>
                                                </div>
                                                </div>

                                                <!-- The Modal -->
                                                <div id="myModal_1" class="modal">
                                                    <span onclick="closmodal(1)" style="top:60px;color: red !important;" class="close"><i class="mdi mdi-close-box"></i></span>
                                                    <img class="modal-content" id="popup_img1">
                                                    <div id="caption"></div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Nos.</label>
                                                        {{Form::text('nos',null,['class'=>'form-control'])}}

                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Total Material</label>
                                                        {{Form::text('required_qty',null,['oninput'=>'cal(this)','class'=>'form-control total_mat'])}}

                                                    </div>
                                                </div>

                                                </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive product_material">

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button id="btnsave" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>



                                    </div><!-- end row -->


                                </div>

                            </div>
                            <!-- end row -->


                            <!-- end row -->


                        </div> <!-- end card-box -->
                        {{Form::close()}}
                    </div><!-- end col-->

                </div>
                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->
        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('.js-example-basic-single').select2();
            });

            function cal(ele) {
                const totalMat = Math.round(Number($(".total_mat").val()) || 0);

                const itemTotal = $(".required_qty_per")
                    .map((_, el) => Number($(el).val()) || 0)
                    .get()
                    .reduce((a, b) => a + b, 0);

                const roundedItemTotal = Math.round(itemTotal);

                console.log("Total Material:", totalMat);
                console.log("Item Total:", roundedItemTotal);

                $("#btnsave").toggle(roundedItemTotal === totalMat);
            }



            function updateUomHint(selectEl) {
                var $hint = $(selectEl).closest('tr').find('.uom-hint');
                var uom = $(selectEl).find(':selected').data('uom');
                if (!uom) {
                    $hint.text('');
                } else if (uom === 'KG') {
                    $hint.text('Enter in grams');
                } else {
                    $hint.text('Unit: ' + uom);
                }
            }

            function getimage(product,machine)
            {
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/production/getproduct_image',
                    data:{product:product},
                    method: 'get',
                    dataType: 'json',
                    success:function (res)
                    {
                        //alert(res["size"]);
                        $(".img_"+machine).attr("src","/product_image/"+res['product_image']);
                        $(".img_"+machine).attr('width', "50px");
                        $(".img_"+machine).attr('height', "80px");
                        $("#size_"+machine).val(res["size"]);
                        $("#colour_"+machine).val(res["colour"]);
                        $(".product_material").html(res["str"]);
                    }
                });
            }
        </script>
        <script>
            function show_image(machine)
            {
                alert(machine);
                alert(this.src);
            }

        </script>
@endsection

