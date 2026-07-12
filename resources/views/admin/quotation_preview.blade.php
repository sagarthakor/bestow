@extends('admin.layout.master')

@section('title', 'Quotation View')

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
                                <h4 class="page-title">Quotation </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">{{Session::get('software_title')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{url('quotation-list')}}">Quotation List </a>
                                    </li>
                                    <li class="active">
                                        Quotation Edit
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
                            <li><a href="{{url('admin/quotation_edit/'.$data->id)}}"><span class="glyphicon glyphicon-user"></span>Edit</a></li>
                            @endcan

                       </ul>
                   </ul>
                   {{Form::model($data,['method'=>'post','route'=>'post.quot_update'])}}
                   {{Form::hidden('id',null)}}
                   {{Form::hidden('quot_no',$data->quot_no)}}

                   <div class="tab-content">
                      <div id="home" class="tab-pane fade in active">
                        <div class="panel">

                            <div class="panel-body">
                               <div class="row">
                                    <div class="col-md-12">
                                       <h3>Organization Details</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Customer Name</label>
                                        <div class="col-sm-6">
                                         {{$data->customer_name}}
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">Quote No</label>
                                        <div class="col-sm-6">
                                         {{$data->quotation_no}}
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                   <div class="row">
                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Contact Name</label>
                                            <div class="col-sm-6">
                                             {{$data->contactname}}
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Quote Date</label>
                                            <div class="col-sm-6">
                                             {{date('d-m-Y',strtotime($data->quot_date))}}
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Quote Valid Until</label>
                                            <div class="col-sm-6">
                                             {{date('d-m-Y',strtotime($data->valid_until))}}
                                            </div>
                                        </div>
                                      </div>
                                   </div>

                                   <div class="row">
                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Subject</label>
                                            <div class="col-sm-6">
                                             {{$data->subject}}
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Payment Terms</label>
                                            <div class="col-sm-6">
                                             Net {{$data->payment_terms}} days
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Quote Stage</label>
                                            <div class="col-sm-6">
                                             {{$data->quot_stage}}
                                            </div>
                                        </div>
                                      </div>

                                   </div>
                                      <div class="row">
                                      <div class="col-md-4">
                                       <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Remark</label>
                                            <div class="col-sm-6">
                                             {{$data->remark}}
                                            </div>
                                        </div>
                                      </div>
                                      </div>
                                       <div class="row">
                                       <div class="col-md-12">
                                            <h3>Address Details</h3>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="col-md-12">
                                                 <div class="form-group">
                                                    <label>Billing Address</label><br>{{$data->billing_address}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Country</label>
                                                     <br>{{$data->billing_country}}
                                                 </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>State</label>
                                                     <br>
                                                        {{$data->billing_state}}

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>City</label>
                                                     <br>{{$data->billing_city}}
                                                 </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Postal Code</label>
                                                     <br>
                                                        {{$data->billing_postalcode}}

                                                </div>
                                            </div>

                                       </div>
                                       <div class="col-md-6">
                                           <div class="col-md-12">
                                                 <div class="form-group">
                                                    <label>Shipping Address</label><br>{{$data->shipping_address}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Country</label>
                                                     <br>{{$data->shipping_country}}
                                                 </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>State</label>
                                                     <br>
                                                        {{$data->shipping_state}}

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>City</label>
                                                     <br>{{$data->shipping_city}}
                                                 </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label>Postal Code</label>
                                                     <br>
                                                        {{$data->shipping_postalcode}}

                                                </div>
                                            </div>
                                       </div>
                                        </div>

                            <div class="tabledata">
                                <table class="table table-striped table-bordered" id="caltable">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;width: 20% !important;">Item Name</th>
                                        {{--                                    <th style="text-align: center;">ID</th>--}}
                                        {{--                                    <th style="text-align: center;">OD</th>--}}
                                        {{--                                    <th style="text-align: center;">THK</th>--}}

                                        <th>Photo</th>
                                        <th style="text-align: center;">HSN</th>
                                        <th colspan="2" style="text-align: center;">Qty</th>

                                        <th style="text-align: center;">Selling Price</th>
                                        <th style="text-align: center;">Total</th>


                                        <th style="text-align: center;">Net Price</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $srno = $total = $gsttotal = $cgsttotal = $sgsttotal = $grand = $discount_total = 0;
                                    ?>
                                    @foreach($quotitem as $item)
                                        <?php
                                        $srno++;
                                        ?>
                                        <?php
                                        $total = $total + $item->amount;
                                        $gsttotal = $gsttotal + $item->gst_amount;
                                        $cgsttotal = $cgsttotal + $item->cgst_amount;
                                        $sgsttotal = $sgsttotal + $item->sgst_amount;
                                        $grand = $grand + $item->grand_total;
                                        $discount_total = $discount_total + $item->discount_amount;
                                        ?>
                                        <tr id="row{{$srno}}">
                                            <td style="vertical-align: top !important;">
                                                <div class="form-group">
                                                    {{$item->product_name}}
                                                </div>

                                                <div class="form-group">
                                                    <label>Description </label><br>
                                                    {{$item->description}}
                                                </div>

                                            </td>

                                            <td style="vertical-align: top !important;text-align: center;">
                                                <img style="width: 80px;height: 80px" src="/product_image/{{$item->product_image}}" name="photo[]" style="height: 120px;width: 120px;" class="img-responsive photo">
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                                {{$item->hsn}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;width: 10%">
                                              {{$item->qty}}
                                            </td>
                                            <td style="vertical-align: top !important;text-align: center;">
                                               {{$item->uom_name}}
                                            </td>

                                            <td style="vertical-align: top !important;">
                                                <div>
                                                    {{$item->price}}&nbsp;
                                                </div>
                                                <div style="clear:both"></div>
                                                <div>
                                                <span>(-)&nbsp;<strong>
                                                         <a style="cursor: pointer" onclick="disDiv(this)">Discount</a>
                                                         (<span class="discountPerc">{{$item->discount_per}}</span>%)
                                                        </a> :
                                                        <div class="discountDiv" style="display: none">
                                                            <div class="form-group" style="width: 50%;display: inline;float: left;">
                                                                <label>Disc %</label><br>
                                                               {{$item->discount_per}}

                                                            </div>
                                                            <div class="form-group" style="width: 50%;float: left;">
                                                                <label>Disc Amt</label><br>
                                                                {{$item->discount_amount}}

                                                            </div>

                                                        </div>
                                                    </strong>
                                                </span>
                                                </div>

                                                <div style="width:150px;">
                                                    <strong>Total After Discount :</strong>
                                                </div>
                                                <div class="individualTaxContainer">(+)&nbsp;
                                                    <strong>
                                                        <a style="cursor: pointer" onclick="taxDiv(this)">Tax </a> (<span class="taxTotal">{{$item->cgst_per+$item->sgst_per+$item->gst_per}}</span>%):
                                                        <div style="display:none;" class="taxdiv">
                                                            <div class="form-group" style="width: 34%;display:inline;float: left;">
                                                                <label>CGST %</label><br>
                                                              {{$item->cgst_per}}

                                                            </div>
                                                            <div class="form-group" style="width: 34%;display:inline;float: left;">
                                                                <label>SGST %</label><br>
                                                               {{$item->sgst_per}}

                                                            </div>
                                                            <div class="form-group" style="width: 32%;float: left;">
                                                                <div class="form-group" >
                                                                    <label>IGST %</label><br>
                                                                   {{$item->gst_per}}

                                                                </div>

                                                            </div>
                                                    </strong>
                                                </div>
                                                <span class="taxDivContainer">
                                                <div class="taxUI hide" id="tax_div1">
                                                    <p class="popover_title hide">Set Tax for : <span
                                                            class="variable"></span>
                                                    </p>
                                                </div>
                                            </span>

                                            </td>

                                            <td style="vertical-align: top !important;">
                                                <input type="hidden" class="total" name="total_amount[]">
                                                <div  align="right" class="productTotal">{{$item->amount}}</div>
                                                <div style=" margin-top: 0px !important;"  align="right" class="discountTotal">
                                                    {{$item->discount_amount}}
                                                </div>
                                                <div  align="right" class="totalAfterDiscount">
                                                    {{$item->amount-$item->discount_amount}}
                                                </div>
                                                <div id="taxTotal1" align="right" class="productTaxTotal">
                                                    {{$item->cgst_amount+$item->igst_amount+$item->gst_amount}}
                                                </div>
                                            </td>


                                            <td style="display:none;vertical-align: top !important;text-align: center;">
                                                <input type="text" name="cgst_per1[]" value="{{$item->cgst_per}}"
                                                       class="cgst_per form-control" id="cgst_per{{$srno}}"
                                                       oninput="cal(this)">
                                            </td>
                                            <td style="display:none;vertical-align: top !important;text-align: center;">
                                                <input type="text" name="cgst_amount[]" value="{{$item->cgst_amount}}"
                                                       class="cgst_amount form-control" id="cgst_amount{{$srno}}">
                                            </td>
                                            <td style="display:none;vertical-align: top !important;text-align: center;">
                                                <input type="text" name="sgst_per1[]" value="{{$item->sgst_per}}"
                                                       class="sgst_per form-control" id="sgst_per{{$srno}}"
                                                       oninput="cal(this)">
                                            </td>
                                            <td style="display:none;vertical-align: top !important;text-align: center;">
                                                <input type="text" name="sgst_amount[]" value="{{$item->sgst_amount}}"
                                                       class="sgst_amount form-control" id="sgst_amount{{$srno}}">
                                            </td>
                                            <td style="display:none;vertical-align: top !important;text-align: center;">
                                                <input type="text" name="gst_per[]" value="{{$item->gst_per}}"
                                                       class="gst_per form-control" id="gst_per{{$srno}}"
                                                       oninput="cal(this)">
                                            </td>
                                            <td style="display:none;vertical-align: top !important;text-align: center;">
                                                <input type="text" name="gst_amount[]" value="{{$item->gst_amount}}"
                                                       class="gst_amount form-control" id="gst_amount{{$srno}}">
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
                <td colspan="10" style="text-align: right;">Tax Total (+)</td><td style="text-align: right;">{{$data->cgsttotal+$data->sgsttotal+$data->gst_amount}}</td><td></td>
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

{{Form::close()}}
</div> <!-- container -->

</div> <!-- content -->
                    <script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
                    <style>
                        .select2-container .select2-selection--single .select2-selection__rendered {
                            display: block;
                            padding-left: 8px;
                            padding-right: 20px;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            white-space: nowrap;
                            font-size: 13px !important;
                            width: 100px;
                        }
                    </style>
                    <script type="text/javascript">

                        $(document).ready(function () {
                            // ClassicEditor
                            //     .create(document.querySelector('.description'))
                            //     removePlugins: 'toolbar'
                            //     .catch(error => {
                            //         console.error(error);
                            //     });
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
                            });
                            $(".adjustment").on("input", function () {
                                var item_total = $("#item_total").val();
                                var discount_total = $('#discount_total').val();
                                var cgsttotal = $('#cgsttotal').val();
                                var sgsttotal = $('#sgsttotal').val();
                                var gsttotal = $('#gsttotal').val();
                                var adjustment = $('#adjustment').val();
                                var grand_total1 = Number(item_total) - Number(discount_total) + Number(cgsttotal) + Number(sgsttotal) + Number(gsttotal);
                                var grand_total = Number(item_total) - Number(discount_total) + Number(cgsttotal) + Number(sgsttotal) + Number(gsttotal) + Number(adjustment);

                                if (adjustment == ".") {
                                    $('#grand_total').val(grand_total1.toFixed(2));
                                } else {
                                    $('#grand_total').val(grand_total.toFixed(2));
                                }

                                // if(grand_total == "NaN")
                                // {
                                //     $('#grand_total').val(grand_total1.toFixed(2));
                                // }else{
                                //     $('#grand_total').val(grand_total.toFixed(2));
                                // }

                            });

                            ClassicEditor
                                .create(document.querySelector('#term_condition'))
                                .catch(error => {
                                    console.error(error);
                                });
                            //alert("df");
                            var inv = $("#invoice").val();
                            //alert(inv);
                            if (inv == "") {
                                $("#invoice").val(0);
                            }
                            var stage = $("#quot_stage").val();
                            if (stage == "Accepted") {
                                $("#invoicediv").show();
                            }


                            $('form').parsley();


                        });

                        function stage_change(stage) {
                            if (stage == "Accepted") {
                                $("#invoicediv").show();
                            } else {
                                $("#invoice").val(0);
                                $("#invoicediv").hide();

                            }
                        }

                        {{-- $(function () {
                            $('#demo-form').parsley().on('field:validated', function () {
                                var ok = $('.parsley-error').length === 0;
                                $('.alert-info').toggleClass('hidden', !ok);
                                $('.alert-warning').toggleClass('hidden', ok);
                            })
                            .on('form:submit', function () {
                                            return false; // Don't submit form for this demo
                                        });
                        }); --}}

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

                        function bom_search(srno) {
                            $("#bom_model").show();
                            $("#bomsrid").val(srno);
                        }

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

                        $(".btnproductsearch").click(function (e) {
                            e.preventDefault();
                        });


                        ClassicEditor
                            .create(document.querySelector('#term_condition'))
                            .catch(error => {
                                console.error(error);
                            });

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

                        function get_product(ele) {
                            var customer = $("#customer").val();
                            if (customer == "") {
                                alert("please select customer first");
                            } else {
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


                                $.ajax({
                                    url: appurl + '/admin/get_product',
                                    data: {product: product, customer: customer},
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
                                            //$("#make"+srno).val(data[0]['make']);
                                            $(ele).closest('tr').find(".stockqty").text(data[0]["stockqty"]);
                                            $(ele).closest('tr').find(".photo").attr('src','{{asset('public/product_image/')}}/'+data[0]['product_image']);
                                        }
                                    }
                                });
                            }
                            cal(ele);
                        }

                        function get_service(ele) {
                            var customer = $("#customer").val();
                            if (customer == "") {
                                alert("please select customer first");
                            } else {
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


                                $.ajax({
                                    url: appurl + '/admin/get_product',
                                    data: {product: product, customer: customer},
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
                                            //$("#make"+srno).val(data[0]['make']);
                                            $(ele).closest('tr').find(".stockqty").text(data[0]["stockqty"]);
                                            $(ele).closest('tr').find(".photo").attr('src','{{asset('public/product_image/')}}/'+data[0]['product_image']);
                                        }
                                    }
                                });
                            }
                            cal(ele);
                        }

                        function get_bom(ele) {
                            var customer = $("#customer").val();
                            if (customer == "") {
                                alert("please select customer first");
                            } else {
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


                                $.ajax({
                                    url: appurl + '/admin/get_product',
                                    data: {product: product, customer: customer},
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
                                            //$("#make"+srno).val(data[0]['make']);
                                            $(ele).closest('tr').find(".stockqty").text(data[0]["stockqty"]);
                                            $(ele).closest('tr').find(".photo").attr('src','{{asset('public/product_image/')}}/'+data[0]['product_image']);
                                        }
                                    }
                                });
                            }
                            cal(ele);
                        }
                        function discmatcal(ele)
                        {
                            var rate = $(ele).closest('tr').find('.price').val();
                            var qty = $(ele).closest('tr').find('.qty').val();
                            var total = Number(rate) * Number(qty);
                            //$(ele).closest('tr').find('.productTotal').text(total.toFixed(2));
                            var discount_amount=$(ele).closest('tr').find(".discount_amount").val();
                            var afterdisc = Number(total) - Number(discount_amount);
                            $(ele).closest('tr').find('.discountTotal').text(discount_amount);
                            $(ele).closest('tr').find('.totalAfterDiscount').text(afterdisc);
                        }
                        function taxDiv(ele)
                        {
                            $(ele).closest('tr').find('.taxdiv').toggle(500);
                        }
                        function disDiv(ele)
                        {
                            $(ele).closest('tr').find('.discountDiv').toggle(500);
                        }
                        function cal(ele) {
                            $(ele).closest('tr').find('.discountTotal').text(0);
                            var rate = $(ele).closest('tr').find('.price').val();
                            var qty = $(ele).closest('tr').find('.qty').val();
                            var total = Number(rate) * Number(qty);
                            $(ele).closest('tr').find('.productTotal').text(total.toFixed(2));

                            $(ele).closest('tr').find('.total').val(total.toFixed(2));

                            var discount_per = $(ele).closest('tr').find('.discount_per').val();

                            var gst_per = $(ele).closest('tr').find('.gst_per').val();
                            var cgst_per = $(ele).closest('tr').find('.cgst_per').val();
                            var sgst_per = $(ele).closest('tr').find('.sgst_per').val();

                            var discount_amount = Number(total) * Number(discount_per) / 100;
                            var afterdisc = Number(total) - Number(discount_amount);
                            $(ele).closest('tr').find('.totalAfterDiscount').text(afterdisc);

                            $(ele).closest('tr').find('.discount_amount').val(discount_amount.toFixed(2));
                            $(ele).closest('tr').find('.discountTotal').text(discount_amount.toFixed(2));
                            $(ele).closest('tr').find('.discountPerc').text(discount_per);
                            // alert(gst_per);
                            var gst_amount = Number(afterdisc) * Number(gst_per) / 100;
                            var cgst_amount = Number(afterdisc) * Number(cgst_per) / 100;
                            var sgst_amount = Number(afterdisc) * Number(sgst_per) / 100;
                            //alert(gst_amount);
                            $(ele).closest('tr').find('.gst_amount').val(gst_amount.toFixed(2));
                            $(ele).closest('tr').find('.cgst_amount').val(cgst_amount.toFixed(2));
                            $(ele).closest('tr').find('.sgst_amount').val(sgst_amount.toFixed(2));
                            var productTaxTotal = Number(gst_amount) + Number(cgst_amount) + Number(sgst_amount);
                            $(ele).closest('tr').find('.productTaxTotal').text(productTaxTotal.toFixed(2));
                            var netprice = Number(afterdisc) + Number(gst_amount) + Number(cgst_amount) + Number(sgst_amount);

                            $(ele).closest('tr').find('.taxTotal').text(Number(gst_per)+Number(sgst_per)+Number(cgst_per));

                            $(ele).closest('tr').find('.netprice').val(netprice.toFixed(2));

                            var totalss = $(".productTotal");
                            var item_total = 0;
                            for (var i = 0; i < totalss.length; i++) {
                                item_total = Number(item_total) + Number($(totalss[i]).text());
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

                            var cgst_amounts = $(".cgst_amount");
                            var cgst_total = 0;
                            for (var i = 0; i < cgst_amounts.length; i++) {
                                cgst_total = Number(cgst_total) + Number($(cgst_amounts[i]).val());
                            }

                            var sgst_amounts = $(".sgst_amount");
                            var sgst_total = 0;
                            for (var i = 0; i < sgst_amounts.length; i++) {
                                sgst_total = Number(sgst_total) + Number($(sgst_amounts[i]).val());
                            }

                            var netprices = $(".netprice");
                            var netpricestotal = 0;
                            for (var i = 0; i < netprices.length; i++) {
                                netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                            }


                            $("#item_total").val(item_total.toFixed(2));
                            $("#discount_total").val(discount_total.toFixed(2));
                            $("#gsttotal").val(gst_total.toFixed(2));
                            $("#cgsttotal").val(cgst_total.toFixed(2));
                            $("#sgsttotal").val(sgst_total.toFixed(2));

                            var round = Math.round(netpricestotal);
                            var diff = Number(round) - Number(netpricestotal);
                            var gtot = Number(netpricestotal) + Number(diff);
                            $("#adjustment").val(diff.toFixed(2));

                            $("#grand_total").val(round.toFixed(2));
                            // $("#grand_total").val(netpricestotal.toFixed(2));

                        }


                        function model_close() {
                            $("#product_model").hide();
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

                        function product_search(srno) {
                            $("#product_model").show();
                            $("#srid").val(srno);
                        }

                        function service_search(srno) {
                            $("#service_model").show();
                            $("#servicesrid").val(srno);
                        }
                    </script>

                    <script>


                        $("#datatable-buttons").on('click', 'tr', function (e) {
                            e.preventDefault();
                            var id = $(this).attr('value');
                            var srno = $("#srid").val();
                            get_product(id, srno)
                            $("#product_model").hide();
                            var appurl = "{{url('/')}}";
                            $.ajax({
                                url: appurl + '/client/ajax_getproduct',
                                data: {product: id},
                                method: 'get',
                                success: function (data) {
                                    $("#product" + srno).html(data);
                                }
                            });
                        });


                        $("#service_datatable-buttons").on('click', 'tr', function (e) {
                            e.preventDefault();
                            var id = $(this).attr('value');
                            var srno = $("#servicesrid").val();
                            get_service(id, srno)
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

                        $("#bom_datatable-buttons").on('click', 'tr', function (e) {
                            e.preventDefault();
                            var id = $(this).attr('value');
                            var srno = $("#bomsrid").val();
                            get_product(id, srno)
                            $("#bom_model").hide();
                            var appurl = "{{url('/')}}";
                            $.ajax({
                                url: appurl + '/client/ajax_getbom',
                                data: {product: id},
                                method: 'get',
                                success: function (data) {
                                    $("#product" + srno).html(data);
                                }
                            });
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

                                var cgst_amounts = $(".cgst_amount");
                                var cgst_total = 0;
                                for (var i = 0; i < cgst_amounts.length; i++) {
                                    cgst_total = Number(cgst_total) + Number($(cgst_amounts[i]).val());
                                }

                                var sgst_amounts = $(".sgst_amount");
                                var sgst_total = 0;
                                for (var i = 0; i < sgst_amounts.length; i++) {
                                    sgst_total = Number(sgst_total) + Number($(sgst_amounts[i]).val());
                                }

                                var netprices = $(".netprice");
                                var netpricestotal = 0;
                                for (var i = 0; i < netprices.length; i++) {
                                    netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                                }


                                $("#item_total").val(item_total.toFixed(2));
                                $("#discount_total").val(discount_total.toFixed(2));
                                $("#gsttotal").val(gst_total.toFixed(2));
                                $("#cgsttotal").val(cgst_total.toFixed(2));
                                $("#sgsttotal").val(sgst_total.toFixed(2));
                                $("#grand_total").val(netpricestotal.toFixed(2));
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
                        $.noConflict();
                        jQuery(document).ready(function ($) {
                            $("#quot_date").datepicker({
                                changeMonth: true,
                                changeYear: true,
                                dateFormat: 'dd-mm-yy'
                            });
                            $("#valid_until").datepicker({
                                changeMonth: true,
                                changeYear: true,
                                dateFormat: 'dd-mm-yy'
                            });
                        });
                    </script>
@endsection

