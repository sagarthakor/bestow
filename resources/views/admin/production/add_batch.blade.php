@extends('admin.layout.master_material')

@section('title', 'Add New | Batch')

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
                            <h4 class="page-title">Production Batch Machine : {{$machine->machine_name}}</h4>
                            <ol class="breadcrumb p-0 m-0">


                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row text-center">


                    <div class="col-xs-12">
                        <div class="card-box">

                            <div class="tab-content">

                                    <div  class="tab-pane fade active in">
                                        {{Form::open(['method'=>'post','route'=>'admin.production.allow_machine_production'])}}
                                        {{Form::hidden("machine_id",$machine->id)}}
                                        <?php
                                        $nosid="nos_";
                                        $nosid .=$machine->id;
                                        $sizeid="size_";
                                        $sizeid .=$machine->id;
                                        $colourid="colour_";
                                        $colourid .=$machine->id;
                                        $requiredmat="required_qty_";
                                        $requiredmat .=$machine->id;
                                        ?>
                                        @php
                                            // Carried over from "Move to Production" on the sales out-of-stock
                                            // report; empty on a normal visit, which just leaves the form blank.
                                            $prefill = $prefill ?? [];
                                            $preProduct  = $prefill['finish_product'] ?? null;
                                            $preNos      = $prefill['nos'] ?? null;
                                            $preCustomer = $prefill['customer'] ?? null;
                                            $preSoNo     = $prefill['so_no'] ?? null;
                                        @endphp

                                        @if($preSoNo)
                                            <div class="alert alert-info text-left">
                                                Covering the shortfall on sales order <b>{{ $preSoNo }}</b>.
                                                Check the numbers below, then press Enter to save the batch.
                                            </div>
                                        @endif

                                        <div class="row">
                                            <h2>{{$machine->machine_name}}</h2>
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Product</label>
                                                        <select onchange="getimage(this.value,{{$machine->id}})" id="finish_product_{{$machine->id}}" name="finish_product" class="js-example-basic-single form-control">
                                                            <option value="">Select product</option>
                                                            @foreach($product as $prod)
                                                                <option value="{{$prod->id}}" {{ (string) $preProduct === (string) $prod->id ? 'selected' : '' }}>{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" style="text-align:left">
                                                    <div class="form-group product-photo-box">
                                                        <label class="control-label" style="display:block;">Photo</label>
                                                        <img class="product-photo img_{{$machine->id}}" style="display:none;" title="Click to enlarge">
                                                        <span class="product-photo-empty"></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Customer</label>
                                                        <select name="customer" class="form-control js-example-basic-single">
                                                            <option value="">Select Customer</option>
                                                            @foreach($customer as $cust)
                                                                <option value="{{$cust->id}}" {{ (string) $preCustomer === (string) $cust->id ? 'selected' : '' }}>{{$cust->customer_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Nos.</label>
                                                        {{Form::text('nos',$preNos,['oninput'=>"getmaterial(this.value,$machine->id)",'class'=>'form-control','id'=>$nosid,"style"=>"text-align:center"])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Colour</label>
                                                        {{Form::text('colour',null,['readonly','oninput'=>"getmaterial(this.value,$machine->id)",'class'=>'form-control','id'=>$colourid,"style"=>"text-align:center"])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Size</label>
                                                        {{Form::text('size',null,['readonly','oninput'=>"getmaterial(this.value,$machine->id)",'class'=>'form-control','id'=>$sizeid,"style"=>"text-align:center"])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Total Material</label>
                                                        {{Form::text('total_material',null,['oninput'=>'cal(this)','class'=>'form-control total_mat','id'=>$requiredmat,"style"=>"text-align:center"])}}

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-12" id="material_div_{{$machine->id}}">
                                            </div>

                                        </div>
                                        {{Form::close()}}
                                    </div>

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

                @if($preProduct)
                    // Arrived from "Move to Production": the product is already chosen,
                    // so pull its photo/size/colour and then the material requirement,
                    // exactly as if the user had picked it and typed the qty by hand.
                    getimage({{ (int) $preProduct }}, {{ $machine->id }}, function () {
                        getmaterial($("#size_{{ $machine->id }}").val(), {{ $machine->id }});
                    });
                @endif

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
                    url:appurl+'/admin/production/getproduction_mat',
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
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/production/getproduct_stock',
                    data:{material:material},
                    method:'get',
                    dataType:'json',
                    success:function (response)
                    {
                       var stockqty= $(ele).closest('tr').find('.stock_qty').val(response['stockqty']);
                       var required_qty=$(ele).closest('tr').find('.required_qty').val();

                        console.log(response['stockqty']);
                        console.log(required_qty);
                        if(Number(required_qty)>Number(response['stockqty']))
                        {
                            var required=Number(response['stockqty'])-Number(required_qty);
                            $(ele).closest('tr').find('.required_stock_qty').val(Math.abs(required));
                            console.log("required more");
                            $(ele).closest('tr').css('border','1px solid red');
                        }else{
                            var required=Number(response['stockqty'])-Number(required_qty);
                            $(ele).closest('tr').find('.required_stock_qty').val(0);
                            $(ele).closest('tr').css('border','1px solid #000');
                        }
                    }
                });
            }
            // `done` is optional - only the prefill path below passes one, so it can
            // work out the material list once size/colour have actually landed.
            function getimage(product,machine,done)
            {
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/admin/production/getproduct_image',
                    data:{product:product},
                    method: 'get',
                    dataType: 'json',
                    success:function (res)
                    {
                        // The photo itself comes from the shared widget, which
                        // knows about per-variant images and opens the popup.
                        ProductPhoto.load(product, $(".img_"+machine));
                        $("#size_"+machine).val(res["size"]);
                        $("#colour_"+machine).val(res["colour"]);

                        if (typeof done === 'function') { done(); }
                    }
                });
            }
        </script>

@endsection
