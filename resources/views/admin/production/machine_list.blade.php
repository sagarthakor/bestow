@extends('admin.layout.master_material')

@section('title', 'List | Production Machine')

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
                            <h4 class="page-title">Production Machine</h4>
                            <ol class="breadcrumb p-0 m-0">


                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                @php
                    // Set when the user arrived from "Move to Production" on the sales
                    // out-of-stock report - carried on to whichever machine they pick.
                    $prefill = $prefill ?? [];
                    $carry   = array_filter($prefill, function ($v) { return $v !== null && $v !== ''; });
                    $forProduct = !empty($carry['finish_product'])
                        ? $product->firstWhere('id', (int) $carry['finish_product'])
                        : null;
                @endphp

                @if($forProduct)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-info">
                                <b>Producing:</b>
                                {{ \App\product::nameWithVariantInline($forProduct->product_name, $forProduct->value1 ?? null, $forProduct->value2 ?? null) }}
                                @if(!empty($carry['nos'])) &middot; <b>{{ $carry['nos'] }}</b> nos @endif
                                @if(!empty($carry['so_no'])) &middot; for order <b>{{ $carry['so_no'] }}</b> @endif
                                <br><small>Pick the machine to run it on - the batch form opens with these details already filled in.</small>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row text-center">

                    @foreach($machine as $mlist)
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <a href="{{route('admin.production.add_batch', array_merge(['id' => $mlist->id], $carry))}}">
                            <div class="card-box widget-box-one bg-secondary">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">{{$mlist->machine_name}} </p>
                                    <h1 class="text-dark"><span data-plugin="counterup">{{$production ?? 0}}</span></h1>
                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach

                    <div class="col-xs-12">


                            <div class="tab-content">
                                @foreach($machine as $mlist)
                                    <?php
                                    $nosid="nos_";
                                    $nosid .=$mlist->id;
                                    $sizeid="size_";
                                    $sizeid .=$mlist->id;
                                    $requiredmat="required_qty_";
                                    $requiredmat .=$mlist->id;
                                    ?>
                                <div id="m{{$mlist->id}}" class="tab-pane fade">
                                    {{Form::open(['method'=>'post','route'=>'admin.production.allow_machine_production'])}}
                                    {{Form::hidden("machine_id",$mlist->id)}}
                                    <div class="row">
                                        <h2>{{$mlist->machine_name}}</h2>
                                        <div class="col-md-12">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Product</label>
                                                <select onchange="getimage(this.value,{{$mlist->id}})" id="finish_product_{{$mlist->id}}" name="finish_product" class="js-example-basic-single form-control">
                                                    <option value="">Select product</option>
                                                    @foreach($product as $prod)
                                                        <option value="{{$prod->id}}">{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group product-photo-box" style="text-align:left;">
                                                <label class="control-label" style="display:block;">Photo</label>
                                                <img class="product-photo img_{{$mlist->id}}" style="display:none;" title="Click to enlarge">
                                                <span class="product-photo-empty"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Customer</label>
                                                <select name="customer" class="form-control js-example-basic-single">
                                                    <option value="">Select Customer</option>
                                                    @foreach($customer as $cust)
                                                        <option value="{{$cust->id}}">{{$cust->customer_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-12">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Nos.</label>
                                                {{Form::text('nos',null,['oninput'=>"getmaterial(this.value,$mlist->id)",'class'=>'form-control','id'=>$nosid])}}

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Size</label>
                                                {{Form::text('size',null,['oninput'=>"getmaterial(this.value,$mlist->id)",'class'=>'form-control','id'=>$sizeid])}}

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Total Material</label>
                                                {{Form::text('total_material',null,['oninput'=>'cal(this)','class'=>'form-control total_mat','id'=>$requiredmat])}}

                                            </div>
                                        </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                            <div class="col-md-12" id="material_div_{{$mlist->id}}">
                                            </div>

                                    </div>
                                    {{Form::close()}}
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>



                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->

<style>
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        /*background-color: rgb(0,0,0); !* Fallback color *!*/
        /*background-color: rgba(0,0,0,0.9); !* Black w/ opacity *!*/
    }

    /* Modal Content (image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* Caption of Modal Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation */
    .modal-content, #caption {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {-webkit-transform:scale(0)}
        to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }
</style>

        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
        @include('admin.partials._product_photo')
        <script type="text/javascript">
            // Using jQuery.
            $(document).ready(function () {
                $('.js-example-basic-single').select2();
            });
            $(function() {
                $('form').each(function() {
                    $(this).find('input').keypress(function(e) {
                        // Enter pressed?
                        if(e.which == 10 || e.which == 13) {
                            this.form.submit();
                        }
                    });

                    $(this).find('input[type=submit]').hide();
                });
            });
            function getmaterial(sizes,machine)
            {
                var finish_product=$("#finish_product_"+machine).val();
                var nos=$("#nos_"+machine).val();
                var size=$("#size_"+machine).val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/getproduction_mat',
                    data:{finish_product:finish_product,size:size,machine:machine,nos:nos,'_token':"{{csrf_token()}}"},
                    method:'post',
                    dataType:'json',
                    success:function (response)
                    {
                        $("#required_qty_"+machine).val(response['required_qty']);
                        $("#material_div_"+machine).html(response['item']);
                    }
                });
            }

            function getrawmaterial(ele)
            {
                var material=$(ele).closest('tr').find('.required_mat').val();
                alert(material);
            }
            function getimage(product,machine)
            {
                ProductPhoto.load(product, $(".img_"+machine));
            }
        </script>

@endsection
