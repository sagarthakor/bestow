@extends('admin.layout.master')

@section('title', 'Quotation Preview')

@section('sidebar')
    @parent

@endsection

@section('content')
    <style type="text/css">
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            border-top:0px !important;
        }

    </style>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Quotation </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('quot/list')}}">Quotation List </a>
                                </li>
                                <li class="active">
                                    Quotation Preview
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Details</a></li>

                    <ul class="nav navbar-nav navbar-right">
                        @can('quotation_update')
                            <li><a href="{{url('client/quot/normal/edit/'.$data->id)}}"><span class="glyphicon glyphicon-user"></span>Edit</a></li>
                        @endcan
                    </ul>
                </ul>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="panel">

                            <div class="panel-body">
                                <div class="row">
                                <!-- <div class="col-md-4" style="display: none;" id="qno">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation No</label>
                                                                    {{Form::text('quot_no',null,['class'=>'form-control','id'=>"quot_no"])}}

                                    </div>
                                </div> -->
                                    <table class="table table-borderless" style="border:0px !important">
                                        <caption style="color: #222;font-weight: 600">Organization Details</caption>
                                        <tr>
                                            <td style="width: 20%">Customer Name </td>
                                            <td style="color: #222;"> {{$data->customer_name}}</td>
                                            <td style="width: 20%">Quotation Date</td>
                                            <td style="color: #222;">{{date('d-m-Y',strtotime($data->quot_date))}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Subject </td>
                                            <td style="color: #222;"> {{$data->subject}}</td>
                                            <td style="width: 20%">Valid Until</td>
                                            <td style="color: #222;"> {{date('d-m-Y',strtotime($data->valid_until))}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Remarks </td>
                                            <td style="color: #222;"> {{$data->remark}}</td>
                                            <td style="width: 20%">Quote Stage</td>
                                            <td style="color: #222;"> {{$data->quot_stage}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="color: #222;font-weight: 600">
                                                Address Details
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Billing Address </td>
                                            <td style="color: #222;"> {{$data->billing_address}}</td>
                                            <td style="width: 20%">Country</td>
                                            <td style="color: #222;"> {{$data->billing_country}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">State </td>
                                            <td style="color: #222;"> {{$data->billing_state}}</td>
                                            <td style="width: 20%">City</td>
                                            <td style="color: #222;"> {{$data->billing_city}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Postal Code </td>
                                            <td style="color: #222;"> {{$data->billing_postalcode}}</td>

                                        </tr>


                                        <tr>
                                            <td style="width: 20%">Shipping Address </td>
                                            <td style="color: #222;"> {{$data->shipping_address}}</td>
                                            <td style="width: 20%">Country</td>
                                            <td style="color: #222;"> {{$data->shipping_country}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">State </td>
                                            <td style="color: #222;"> {{$data->shipping_state}}</td>
                                            <td style="width: 20%">City</td>
                                            <td style="color: #222;"> {{$data->shipping_city}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Postal Code </td>
                                            <td style="color: #222;"> {{$data->shipping_postalcode}}</td>

                                        </tr>


                                    </table>

                                </div>
                                <!--   <div class="col-sm-6">
                                      <div class="m-b-30">
                                          <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></button>
                                      </div>
                                  </div> -->
                            </div>

                            <div class="tabledata">
                                <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">Product Name</th>

                                        <th style="text-align: center;">HSN</th>
                                        <th style="text-align: center;">Qty</th>
                                        <th style="text-align: center;">Price</th>
                                        <th style="text-align: center;">Total</th>
                                        <th style="text-align: center;">Disc %</th>
                                        <th style="text-align: center;">Disc Amt</th>
                                        <th style="text-align: center;">GST %</th>
                                        <th style="text-align: center;">GST Amt</th>
                                        <th style="text-align: center;">Total</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $srno=$total=$gsttotal=$grand=$discount_total=0;
                                    ?>
                                    @foreach($quotitem as $item)
                                        <?php
                                        $srno++;
                                        ?>
                                        <?php
                                        $total=$total+$item->amount;
                                        $gsttotal=$gsttotal+$item->gst_amount;
                                        $grand=$grand+$item->grand_total;
                                        $discount_total=$discount_total+$item->discount_amount;
                                        ?>
                                        <tr id="row{{$srno}}">
                                            <td style="vertical-align: top !important;width: 20%">
                                                <x-product-name :row="$item" />

                                            </td>

                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->hsn}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->qty}}
                                            </td>

                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->price}}
                                            </td>


                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->amount}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->discount_per}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->discount_amount}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->gst_per}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->gst_amount}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->grand_total}}
                                            </td>


                                        </tr>
                                    @endforeach


                                    </tbody>

                                </table>
                                <table class="table table-striped add-edit-table table-bordered" style="margin-left: 62%;width: 38%;">
                                    <tr>
                                        <td colspan="10" style="text-align: right;">Item Total (+)</td><td style="text-align: right;">{{$total}}</td><td></td>
                                    </tr>

                                    <tr>
                                        <td colspan="10" style="text-align: right;">Discount Total (-)</td><td style="text-align: right;">{{$discount_total}}</td><td></td>
                                    </tr>

                                    <tr>
                                        <td colspan="10" style="text-align: right;">GST Total (+)</td><td style="text-align: right;">{{$gsttotal}}</td><td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10" style="text-align: right;">Grand Total (+)</td><td style="text-align: right;">{{$grand}}</td><td></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-12">

                                <h3>Terms & Conditions</h3>

                            </div>


                            <div class="col-md-12" id="terms">
                                <div class="form-group">
                                    <label>Terms & Conditions</label>
                                    <?php
                                    echo $data->term_condition;
                                    ?>

                                </div>
                            </div>



                        </div>
                        <!-- end: page -->

                    </div> <!-- end Panel -->

                </div> <!-- container -->

            </div> <!-- content -->

            @include('admin/footer')

        </div>


        <!-- MODAL -->

        <!-- end Modal -->

        <div class="modal" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <a type="button" class="close" onclick="model_close()">&times;</a>
                        <h4 class="modal-title">Quick Create Organization</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Organization Name</label>
                                    <input type="text" class="form-control" name="organization_name" id="organization_name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" class="form-control" name="website" id="website">
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
                        <a type="button" onclick="customer_form()" class="btn btn-default">Go to full form</a>
                        <a type="button" onclick="customer_save()" class="btn btn-primary">Save</a>
                        <a type="button" class="btn btn-default" onclick="model_close()">Close</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="product_model" role="dialog" style="width: 100% !important">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="model_close()">&times;</button>
                        <h4 class="modal-title">Products</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card-box table-responsive">
                                    <input type="hidden" name="srid" id="srid">
                                    <table style="width: 100% !important" id="datatable-buttons" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>

                                            <th>Product Name</th>

                                            <th>UOM</th>
                                            <th>Price</th>
                                            <th>GST</th>

                                        </tr>
                                        </thead>


                                        <tbody>
                                        @foreach($product as $serarchprod)
                                            <tr value="{{$serarchprod->id}}">
                                                <td style="width: 10%"><x-product-name :row="$serarchprod" /></td>
                                                <td>{{$serarchprod->uom_name}}</td>
                                                <td>{{$serarchprod->price}}</td>
                                                <td>{{$serarchprod->gst_per}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal" id="service_model" role="dialog" style="width: 100% !important">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="model_close()">&times;</button>
                        <h4 class="modal-title">Services</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card-box table-responsive">
                                    <input type="hidden" name="servicesrid" id="servicesrid">
                                    <table style="width: 100% !important" id="service_datatable-buttons" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>

                                            <th>Service Name</th>

                                            <th>UOM</th>
                                            <th>Price</th>
                                            <th>GST</th>

                                        </tr>
                                        </thead>


                                        <tbody>
                                        @foreach($service as $serarchservice)
                                            <tr value="{{$serarchservice->id}}">
                                                <td style="width: 10%"><x-product-name :row="$serarchservice" /></td>
                                                <td>{{$serarchservice->uom_name}}</td>
                                                <td>{{$serarchservice->price}}</td>
                                                <td>{{$serarchservice->gst_per}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->


        <!-- Right Sidebar -->

        <!-- /Right-bar -->

    <script type="text/javascript">

        $(".btnproductsearch").click(function(e){
            e.preventDefault();
        });
        $("#module").change(function(){
            var modules=$("#module").val();
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/admin/get_terms',
                data:{module:modules},
                method:'get',
                success:function(data)
                {
                    $("#terms").html(data);
                    ClassicEditor
                        .create( document.querySelector( '#term_condition' ) )
                        .catch( error => {
                            console.error( error );
                        } );
                }
            })
        });

        ClassicEditor
            .create( document.querySelector( '#term_condition' ) )
            .catch( error => {
                console.error( error );
            } );
        function getcustomer(customer)
        {
            var appurl="{{url('/')}}";

            $.ajax({
                url:appurl+'/admin/get_customer',
                data:{customer:customer},
                method:'get',
                dataType:'json',
                success:function(data)
                {
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
        }

        function get_product(product,srno)
        {
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/admin/get_product',
                data:{product:product},
                method:'get',
                dataType:'json',
                success:function(data)
                {
                    var len=data.length;
                    if(len>0)
                    {
                        $("#qty"+srno).val("");
                        $("#price"+srno).val("");
                        $("#total_amount"+srno).val("");
                        $("#discount_per"+srno).val("");
                        $("#discount_amount"+srno).val("");
                        $("#gst_per"+srno).val("");
                        $("#gst_amount"+srno).val("");

                        $("#description"+srno).val(data[0]['description']);
                        $("#price"+srno).val(data[0]['price']);
                        $("#gst_per"+srno).val(data[0]['gst']);
                        $("#inner_diamitter"+srno).val(data[0]['inner_diameter']);
                        $("#outer_diamitter"+srno).val(data[0]['outer_diameter']);
                        $("#thikness"+srno).val(data[0]['thikness']);
                        $("#hsn"+srno).val(data[0]['hsn']);
                        //$("#make"+srno).val(data[0]['make']);
                    }
                }
            });
        }

        function get_service(product,srno)
        {
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/admin/get_product',
                data:{product:product},
                method:'get',
                dataType:'json',
                success:function(data)
                {
                    var len=data.length;
                    if(len>0)
                    {
                        $("#description"+srno).val(data[0]['description']);
                        $("#price"+srno).val(data[0]['price']);
                        $("#gst_per"+srno).val(data[0]['gst']);

                        //$("#make"+srno).val(data[0]['make']);
                    }
                }
            });
        }

        function cal(ele)
        {
            var rate = $(ele).closest('tr').find('.price').val();
            var qty = $(ele).closest('tr').find('.qty').val();
            var total=Number(rate)*Number(qty);

            $(ele).closest('tr').find('.total').val(Math.round(total));

            var discount_per = $(ele).closest('tr').find('.discount_per').val();

            var gst_per = $(ele).closest('tr').find('.gst_per').val();

            var discount_amount=Number(total)*Number(discount_per)/100;

            var afterdisc=Number(total)-Number(discount_amount);

            $(ele).closest('tr').find('.discount_amount').val(Math.round(discount_amount));

            // alert(gst_per);
            var gst_amount=Number(afterdisc)*Number(gst_per)/100;
            //alert(gst_amount);
            $(ele).closest('tr').find('.gst_amount').val(Math.round(gst_amount));

            var netprice=Number(afterdisc)+Number(gst_amount);

            $(ele).closest('tr').find('.netprice').val(Math.round(netprice));

            var totalss = $(".total");
            var item_total=0;
            for(var i = 0; i < totalss.length; i++){
                item_total=Number(item_total)+Number($(totalss[i]).val());
            }

            var discount_amount = $(".discount_amount");
            var discount_total=0;
            for(var i = 0; i < discount_amount.length; i++){
                discount_total=Number(discount_total)+Number($(discount_amount[i]).val());
            }

            var gst_amounts = $(".gst_amount");
            var gst_total=0;
            for(var i = 0; i < gst_amounts.length; i++){
                gst_total=Number(gst_total)+Number($(gst_amounts[i]).val());
            }

            var netprices = $(".netprice");
            var netpricestotal=0;
            for(var i = 0; i < netprices.length; i++){
                netpricestotal=Number(netpricestotal)+Number($(netprices[i]).val());
            }


            $("#item_total").val(item_total);
            $("#discount_total").val(discount_total);
            $("#gsttotal").val(gst_total);
            $("#grand_total").val(netpricestotal);

        }


        function model_close()
        {
            $("#product_model").hide();
            $("#service_model").hide();
            $("#myModal").hide();
        }

        function customer_save()
        {
            var organization_name=$("#organization_name").val();
            var website=$("#website").val();
            var primary_phone=$("#primary_phone").val();
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/client/ajax_customer_save',
                data:{organization_name:organization_name,website:website,primary_phone:primary_phone},
                method:'get',
                dataType:'json',
                success:function(data)
                {
                    var len=data.length;
                    if(len>0)
                    {
                        $("#primary_phone").val(data[0]['primary_phone']);
                        $("#website").val(data[0]['website']);
                        $("#customer").html(data[0]['str']);

                    }
                    $("#myModal").hide();
                }
            });
        }
        function customer_form()
        {
            window.location="{{url('customer_add')}}";
        }

        function product_search(srno)
        {
            $("#product_model").show();
            $("#srid").val(srno);
        }

        function service_search(srno)
        {
            $("#service_model").show();
            $("#servicesrid").val(srno);
        }
    </script>

    <script>


        $("#datatable-buttons").on('click','tr',function(e){
            e.preventDefault();
            var id = $(this).attr('value');
            var srno=$("#srid").val();
            get_product(id,srno)
            $("#product_model").hide();
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/client/ajax_getproduct',
                data:{product:id},
                method:'get',
                success:function(data)
                {
                    $("#product"+srno).html(data);
                }
            });
        });


        $("#service_datatable-buttons").on('click','tr',function(e){
            e.preventDefault();
            var id = $(this).attr('value');
            var srno=$("#servicesrid").val();
            get_service(id,srno)
            $("#service_model").hide();
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/client/ajax_getservice',
                data:{product:id},
                method:'get',
                success:function(data)
                {
                    $("#product"+srno).html(data);
                }
            });
        });

        $("#add_product").click(function(e){
            e.preventDefault();
            var i=$("#totrow").val();
            i++;

            var data="<tr id='row"+i+"'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_product(this.value,"+i+")' name='product[]' id='product"+i+"'> <option>select</option>@foreach($product as $prod)<option value='{{$prod->id}}'>{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}</option>@endforeach</select><div class='input-group-btn'><a class='btn btn-default product_btn'  onclick='product_search("+i+")'><img src='<?=asset('public/product_icon.png');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control" id="inner_diamitter'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control" id="outer_diamitter'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control" id="thikness'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="form-control" id="hsn'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price'+i+'" oninput="cal(this)"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" class="discount_per form-control" oninput="cal(this)" id="discount_per'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'" oninput="cal(this)"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
            data +='<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

            $("#caltable").append(data);

            $("#totrow").val(i);
        });



        $("#add_service").click(function(e){
            e.preventDefault();
            var i=$("#totrow").val();
            i++;

            var data="<tr id='row"+i+"'><td><div class='input-group'><select class='form-control js-example-basic-single' onchange='get_service(this.value,"+i+")' name='product[]' id='product"+i+"'> <option>select</option>@foreach($service as $prod)<option value='{{$prod->id}}'>{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}</option>@endforeach</select><div class='input-group-btn'><a class='service_btn btn btn-default' onclick='service_search("+i+")'><img src='<?=asset('public/service_icon.jpg');?>' style='height:20px'></a></div></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
            data +='<td class="actions" style="vertical-align: top !important;text-align:center"><a style="cursor:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

            $("#caltable").append(data);

            $("#totrow").val(i);
        });

        $(".service_btn").click(function(e){
            e.preventDefault();
        });
        $(".product_btn").click(function(e){
            e.preventDefault();
        });
        $("#product_btn1").click(function(e){
            e.preventDefault();
        });
        function add_customer()
        {
            $("#myModal").show();
        }
        function remove_row(ele)
        {
            if(confirm("Are you sure you want to delete this?")){
                $(ele).closest('tr').remove();

                var totalss = $(".total");
                var item_total=0;
                for(var i = 0; i < totalss.length; i++){
                    item_total=Number(item_total)+Number($(totalss[i]).val());
                }

                var discount_amount = $(".discount_amount");
                var discount_total=0;
                for(var i = 0; i < discount_amount.length; i++){
                    discount_total=Number(discount_total)+Number($(discount_amount[i]).val());
                }

                var gst_amounts = $(".gst_amount");
                var gst_total=0;
                for(var i = 0; i < gst_amounts.length; i++){
                    gst_total=Number(gst_total)+Number($(gst_amounts[i]).val());
                }

                var netprices = $(".netprice");
                var netpricestotal=0;
                for(var i = 0; i < netprices.length; i++){
                    netpricestotal=Number(netpricestotal)+Number($(netprices[i]).val());
                }


                $("#item_total").val(item_total);
                $("#discount_total").val(discount_total);
                $("#gsttotal").val(gst_total);
                $("#grand_total").val(netpricestotal);
            }
            else{
                return false;
            }

        }
        $(".rowremove").click(function(){
            // alert("sdd");
            $(this).parent().parent().remove();
        });
    </script>

    <script type="text/javascript">
        $.noConflict();
        jQuery(document).ready(function ($) {
            $( "#quot_date" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });
            $( "#valid_until" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });
        });
    </script>
@endsection
