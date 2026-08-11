@extends('admin.layout.master_material')

@section('title', 'Edit | Revise Quotation')

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
                            <h4 class="page-title">Revise Quotation </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('quotation-list')}}">Quotation List </a>
                                </li>
                                <li class="active">
                                    Revise Quotation
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                {{Form::model($data,['method'=>'post','route'=>'admin.quotation.revise.save','role'=>'form','data-parsley-validate novalidate'])}}
                {{Form::hidden('id',null)}}
                {{Form::hidden('challan_no',$data->challan_no)}}

                <div class="panel">

                    <div class="panel-body">
                        <div class="row">
                        <!-- <div class="col-md-4" style="display: none;" id="qno">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation No</label>
                                                                    {{Form::text('quot_no',null,['class'=>'form-control','id'=>"quot_no"])}}

                            </div>
                        </div> -->
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="control-label">Customer Name <span style="color: red">*</span></label>
                                    <div class="input-group">

                                        {{Form::select('customer',$customer,null,['required','class'=>'form-control js-example-basic-single','id'=>'customer','onchange'=>'getcustomer(this.value)'])}}
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-plus"
                                                                           onclick="add_customer()"></i></span>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="control-label">Quotation No. <span
                                                style="color: red">*</span></label>
                                        {{Form::text('quotation_no',null,['required','class'=>'form-control input-daterange-datepicker','autocomplete'=>'off','readonly'])}}

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Subject <span style="color: red">*</span></label>
                                        {{Form::text('subject',null,['required','class'=>'form-control','id'=>"subject"])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="control-label">Quotation Date <span
                                                style="color: red">*</span></label>
                                        {{Form::text('quot_date',date('d-m-Y',strtotime($data->quot_date)),['required','class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact Name <span
                                                style="color: red">*</span></label>
                                        {{Form::select('contact_name',$contact_name,null,['required','class'=>'form-control','id'=>"contact_name"])}}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Valid Until <span
                                                style="color: red">*</span></label>
                                        {{Form::text('valid_until',Date('d-m-Y', strtotime('+14 days'))  ,['required','class'=>'form-control input-daterange-datepicker','id'=>"valid_until",'autocomplete'=>'off'])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');
                                        ?>
                                        <label class="control-label">Quote Stage <span style="color: red">*</span>
                                        </label>
                                        {{Form::select('quot_stage',$stage,null,['required','class'=>'form-control js-example-basic-single','id'=>'quot_stage','onchange'=>"stage_change(this.value)"])}}


                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        {{Form::text('remark',null,['class'=>'form-control'])}}

                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none;" id="invoicediv">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Invoice No</label>
                                        {{Form::text('invoice_no',$data->invoice_no,['class'=>'form-control','required','id'=>'invoice'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">

                                <h3>Address Details</h3>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Billing Address <span style="color: red">*</span></label>
                                        {{Form::textarea('billing_address',null,['required','class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'billing_address'])}}

                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Country <span style="color: red">*</span></label>

                                        {{Form::text('billing_country',null,['required','class'=>'form-control','id'=>"billing_country"])}}

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>State <span style="color: red">*</span></label>

                                        {{Form::text('billing_state',null,['required','class'=>'form-control','id'=>"billing_state"])}}

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>City <span style="color: red">*</span></label>

                                        {{Form::text('billing_city',null,['required','class'=>'form-control','id'=>"billing_city"])}}

                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Postal Code <span style="color: red">*</span></label>

                                        {{Form::text('billing_postalcode',null,['required','class'=>'form-control','id'=>"billing_postalcode"])}}

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Address <span style="color: red">*</span></label>
                                        {{Form::textarea('shipping_address',null,['required','class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'shipping_address'])}}

                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Country <span style="color: red">*</span></label>
                                        {{Form::text('shipping_country',null,['required','class'=>'form-control','id'=>'shipping_country'])}}

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>City <span style="color: red">*</span></label>
                                        {{Form::text('shipping_city',null,['required','class'=>'form-control','id'=>'shipping_city'])}}

                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>State <span style="color: red">*</span></label>
                                        {{Form::text('shipping_state',null,['required','class'=>'form-control','id'=>'shipping_state'])}}

                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Postal Code <span style="color: red">*</span></label>
                                        {{Form::text('shipping_postalcode',null,['required','class'=>'form-control','id'=>'shipping_postalcode'])}}

                                    </div>
                                </div>

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
                                    {{--                                    <th style="text-align: center;">ID</th>--}}
                                    {{--                                    <th style="text-align: center;">OD</th>--}}
                                    {{--                                    <th style="text-align: center;">THK</th>--}}
                                    <th style="text-align: center;">HSN</th>
                                    <th style="text-align: center;">Qty</th>
                                    <th style="text-align: center;">Price</th>
                                    <th style="text-align: center;">Net Amt</th>
                                    <th style="text-align: center;">Disc %</th>
                                    <th style="text-align: center;">Disc Amt</th>
                                    <th style="text-align: center;">CGST %</th>
                                    <th style="text-align: center;">CGST Amt</th>
                                    <th style="text-align: center;">SGST %</th>
                                    <th style="text-align: center;">SGST Amt</th>
                                    <th style="text-align: center;">IGST %</th>
                                    <th style="text-align: center;">IGST Amt</th>
                                    <th style="text-align: center;">Total</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $srno = $total = $gsttotal = $cgsttotal=$sgsttotal=$grand = $discount_total = 0;
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
                                        <td style="vertical-align: top !important;width: 20%">
                                            <div class="input-group">
                                                <select class="form-control product"
                                                        onchange="get_product(this.value,{{$srno}})" name="product[]"
                                                        id="product{{$srno}}" required>
                                                    <option value="{{$item->product}}" selected>{{ \App\product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null) }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label> </label>
                                                <textarea id="description{{$srno}}" name="description[]"
                                                          class="form-control">{{$item->description}}</textarea>

                                            </div>
                                        </td>
                                        {{--                                        <td style="vertical-align: top !important;text-align: center;">--}}
                                        {{--                                            <input type="text" class="form-control" name="inner_diamitter[]"--}}
                                        {{--                                                   value="{{$item->inner_diameter}}" id="inner_diamitter{{$srno}}"--}}
                                        {{--                                                   style="text-align: center;">--}}
                                        {{--                                        </td>--}}
                                        {{--                                        <td style="vertical-align: top !important;text-align: center;">--}}
                                        {{--                                            <input type="text" class="form-control" name="outer_diamitter[]"--}}
                                        {{--                                                   value="{{$item->outer_diameter}}" id="outer_diamitter{{$srno}}"--}}
                                        {{--                                                   style="text-align: center;">--}}
                                        {{--                                        </td>--}}
                                        {{--                                        <td style="vertical-align: top !important;text-align: center;">--}}
                                        {{--                                            <input type="text" class="form-control" name="thikness[]"--}}
                                        {{--                                                   value="{{$item->thikness}}" id="thikness{{$srno}}">--}}
                                        {{--                                        </td>--}}
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" class="form-control" name="hsn[]" value="{{$item->hsn}}"
                                                   id="hsn{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="qty[]" onkeyup="cal(this)" value="{{$item->qty}}"
                                                   class="qty form-control" id="qty{{$srno}}">
                                        </td>

                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="price[]" class="price form-control"
                                                   value="{{$item->price}}" id="price{{$srno}}" oninput="cal(this)">
                                        </td>


                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="total_amount[]" value="{{$item->amount}}"
                                                   class="total form-control" id="total_amount{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="discount_per[]" value="{{$item->discount_per}}"
                                                   class="discount_per form-control" id="discount_per{{$srno}}"
                                                   oninput="cal(this)">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="discount_amount[]"
                                                   value="{{$item->discount_amount}}"
                                                   class="discount_amount form-control" id="discount_amount{{$srno}}"
                                                   oninput="cal(this)">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="cgst_per[]" value="{{$item->cgst_per}}"
                                                   class="cgst_per form-control" id="cgst_per{{$srno}}"
                                                   oninput="cal(this)">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="cgst_amount[]" value="{{$item->cgst_amount}}"
                                                   class="cgst_amount form-control" id="cgst_amount{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="sgst_per[]" value="{{$item->sgst_per}}"
                                                   class="sgst_per form-control" id="sgst_per{{$srno}}"
                                                   oninput="cal(this)">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="sgst_amount[]" value="{{$item->sgst_amount}}"
                                                   class="sgst_amount form-control" id="sgst_amount{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="gst_per[]" value="{{$item->gst_per}}"
                                                   class="gst_per form-control" id="gst_per{{$srno}}"
                                                   oninput="cal(this)">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="gst_amount[]" value="{{$item->gst_amount}}"
                                                   class="gst_amount form-control" id="gst_amount{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" name="net_price[]" value="{{$item->grand_total}}"
                                                   class="netprice form-control" id="net_price{{$srno}}">
                                        </td>
                                        <td class="actions" style="vertical-align: top !important;text-align: center;">
                                            <a onclick="remove_row(this)" style="cursor: pointer;"
                                               class="on-editing save-row" title="save"><i class="fa fa-trash"
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
                                            <a class="btn btn-default" id="add_product">+ Add Product</a>
                                        </div>

                                        <div class="col-md-2">
                                            <a class="btn btn-default" id="add_service">+ Add Service</a>
                                        </div>

                                        <div class="col-md-2">
                                            <a class="btn btn-default" id="add_bom">+ Add BOM</a>
                                        </div>

                                        <input type="hidden" id="totrow" value="{{$srno}}">
                                    </td>
                                </tr>

                                </tfoot>
                            </table>
                            <table class="table table-striped add-edit-table table-bordered"
                                   style="margin-left: 62%;width: 38%;">
                                <tr>
                                    <td colspan="10" style="text-align: right;">Item Total (+)</td>
                                    <td style="text-align: right;"><input type="text" class="form-control item_total"
                                                                          name="item_total" id="item_total"
                                                                          value="{{$data->net_amount ?? 0}}"></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="10" style="text-align: right;">Discount Total (-)</td>
                                    <td style="text-align: right;"><input type="text"
                                                                          class="form-control discount_total"
                                                                          name="discount_total" id="discount_total"
                                                                          value="{{$data->discount_total ?? 0}}"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="10" style="text-align: right;">CGST Total (+)</td>
                                    <td style="text-align: right;"><input type="text" class="form-control cgsttotal"
                                                                          name="cgsttotal" id="cgsttotal"
                                                                          value="{{$data->cgsttotal ?? 0}}"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="10" style="text-align: right;">SGST Total (+)</td>
                                    <td style="text-align: right;"><input type="text" class="form-control sgsttotal"
                                                                          name="sgsttotal" id="sgsttotal"
                                                                          value="{{$data->sgsttotal ?? 0}}"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="10" style="text-align: right;">IGST Total (+)</td>
                                    <td style="text-align: right;"><input type="text" class="form-control gsttotal"
                                                                          name="gsttotal" id="gsttotal"
                                                                          value="{{$data->gst_amount ?? 0}}"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="10" style="text-align: right;">Adjustment</td>
                                    <td style="text-align: right;"><input type="text" class="form-control adjustment"
                                                                          name="adjustment" id="adjustment"
                                                                          value="{{$data->adjustment ?? 0}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="10" style="text-align: right;">Grand Total (+)</td>
                                    <td style="text-align: right;"><input type="text" class="form-control grand_total"
                                                                          name="grand_total" id="grand_total"
                                                                          value="{{$data->grand_total ?? 0}}"></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-sm-12">

                            <h3>Terms & Conditions</h3>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Module</label>
                                {{Form::select('module',$module,null,['class'=>'form-control','id'=>'module'])}}
                            </div>
                        </div>

                        <div class="col-md-12" id="terms">
                            <div class="form-group">
                                <label>Terms & Conditions</label>
                                {{Form::textarea('term_condition',null,['class'=>'form-control','cols'=>'1','rows'=>'1','id'=>'term_condition','required'])}}

                            </div>
                        </div>


                    </div>
                    <!-- end: page -->

                </div> <!-- end Panel -->
                <div class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <button style="text-align: center;" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                {{Form::close()}}
            </div> <!-- container -->

        </div> <!-- content -->



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


        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->

        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>

        @include('admin.partials._product_search')
        <script type="text/javascript">

            $(document).ready(function () {

                $("select.product").each(function () {
                    initProductAjaxSelect2($(this), 'product');
                });

                $(".adjustment").on("input", function(){
                    var item_total = $("#item_total").val();
                    var discount_total = $('#discount_total').val();
                    var cgsttotal = $('#cgsttotal').val();
                    var sgsttotal = $('#sgsttotal').val();
                    var gsttotal = $('#gsttotal').val();
                    var adjustment = $('#adjustment').val();
                    var grand_total1=Number(item_total)-Number(discount_total)+Number(cgsttotal)+Number(sgsttotal)+Number(gsttotal);
                    var grand_total=Number(item_total)-Number(discount_total)+Number(cgsttotal)+Number(sgsttotal)+Number(gsttotal)+Number(adjustment);

                    if(adjustment==".")
                    {
                        $('#grand_total').val(grand_total1.toFixed(2));
                    }else{
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

            // The picker itself lives in admin.partials._product_search so every
            // document screen searches the catalogue the same way; this stays as
            // the name the row builders already call it by.
            function initProductAjaxSelect2($select, status) {
                ProductSearch.attach($select, status);
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

            function get_product(product, srno) {
                var appurl = "{{url('/')}}";
                var customer = $("#customer").val();
                $.ajax({
                    url: appurl + '/admin/get_product',
                    data: {product: product, customer: customer},
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
                            $("#cgst_per" + srno).val("");
                            $("#sgst_per" + srno).val("");
                            $("#gst_amount" + srno).val("");

                            $("#description" + srno).val(data[0]['description']);
                            $("#price" + srno).val(data[0]['price']);
                            $("#gst_per" + srno).val(data[0]['gst']);
                            $("#cgst_per" + srno).val(data[0]['cgst']);
                            $("#sgst_per" + srno).val(data[0]['sgst']);
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

            function get_service(product, srno) {
                var appurl = "{{url('/')}}";
                var customer = $("#customer").val();
                $.ajax({
                    url: appurl + '/admin/get_product',
                    data: {product: product,customer: customer},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var len = data.length;
                        if (len > 0) {
                            $("#description" + srno).val(data[0]['description']);
                            $("#price" + srno).val(data[0]['price']);
                            $("#gst_per" + srno).val(data[0]['gst']);
                            $("#cgst_per" + srno).val(data[0]['cgst']);
                            $("#sgst_per" + srno).val(data[0]['sgst']);

                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }

            function get_bom(product, srno) {
                var appurl = "{{url('/')}}";
                var customer = $("#customer").val();
                $.ajax({
                    url: appurl + '/admin/get_product',
                    data: {product: product,customer: customer},
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
                            $("#cgst_per" + srno).val("");
                            $("#cgst_amount" + srno).val("");
                            $("#sgst_per" + srno).val("");
                            $("#sgst_amount" + srno).val("");

                            $("#gst_per" + srno).val("");
                            $("#gst_amount" + srno).val("");
                            $("#description" + srno).html(data[0]['description']);


                            $("#price" + srno).val(data[0]['price']);
                            $("#gst_per" + srno).val(data[0]['gst']);
                            $("#cgst_per" + srno).val(data[0]['cgst']);
                            $("#sgst_per" + srno).val(data[0]['sgst']);
                            $("#inner_diamitter" + srno).val(data[0]['inner_diameter']);
                            $("#outer_diamitter" + srno).val(data[0]['outer_diameter']);
                            $("#thikness" + srno).val(data[0]['thikness']);
                            $("#hsn" + srno).val(data[0]['hsn']);
                            $("#discount_per" + srno).val(data[0]['discper']);

                            var htmlString = data[0]['description'];

                            var stripedHtml = $("#description" + srno).html(htmlString).text();

                        }
                    }
                });
            }

            function cal(ele) {
                var rate = $(ele).closest('tr').find('.price').val();
                var qty = $(ele).closest('tr').find('.qty').val();
                var total = Number(rate) * Number(qty);

                $(ele).closest('tr').find('.total').val(total.toFixed(2));

                var discount_per = $(ele).closest('tr').find('.discount_per').val();

                var gst_per = $(ele).closest('tr').find('.gst_per').val();
                var cgst_per = $(ele).closest('tr').find('.cgst_per').val();
                var sgst_per = $(ele).closest('tr').find('.sgst_per').val();

                var discount_amount = Number(total) * Number(discount_per) / 100;

                var afterdisc = Number(total) - Number(discount_amount);

                $(ele).closest('tr').find('.discount_amount').val(discount_amount.toFixed(2));

                // alert(gst_per);
                var gst_amount = Number(afterdisc) * Number(gst_per) / 100;
                var cgst_amount = Number(afterdisc) * Number(cgst_per) / 100;
                var sgst_amount = Number(afterdisc) * Number(sgst_per) / 100;
                //alert(gst_amount);
                $(ele).closest('tr').find('.gst_amount').val(gst_amount.toFixed(2));
                $(ele).closest('tr').find('.cgst_amount').val(cgst_amount.toFixed(2));
                $(ele).closest('tr').find('.sgst_amount').val(sgst_amount.toFixed(2));

                var netprice = Number(afterdisc) + Number(gst_amount)+Number(cgst_amount)+Number(sgst_amount);

                $(ele).closest('tr').find('.netprice').val(netprice.toFixed(2));

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

                var round=Math.round(netpricestotal);
                var diff=Number(round)-Number(netpricestotal);
                var gtot=Number(netpricestotal)+Number(diff);
                $("#adjustment").val(diff.toFixed(2));
                $("#grand_total").val(round.toFixed(2));

            }


            function model_close() {
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

        </script>

        <script>

            $("#add_product").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr id='row" + i + "'><td><div class='input-group'><select class='form-control product' onchange='get_product(this.value," + i + ")' name='product[]' id='product" + i + "'> <option>select</option></select></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='form-control'></textarea></div></td>";
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control" id="inner_diamitter' + i + '"></td>';
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control" id="outer_diamitter' + i + '"></td>';
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control" id="thikness' + i + '"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="form-control" id="hsn' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty' + i + '"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price' + i + '" oninput="cal(this)"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" class="discount_per form-control" oninput="cal(this)" id="discount_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_per[]" oninput="cal(this)" class="cgst_per form-control" id="cgst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_amount[]" class="cgst_amount form-control" id="cgst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_per[]" oninput="cal(this)" class="sgst_per form-control" id="sgst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_amount[]" class="sgst_amount form-control" id="sgst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per' + i + '" oninput="cal(this)"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price' + i + '"></td>';
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);
                initProductAjaxSelect2($("#product" + i), 'product');
                $("#totrow").val(i);
            });


            $("#add_service").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr id='row" + i + "'><td><div class='input-group'><select class='form-control product' onchange='get_service(this.value," + i + ")' name='product[]' id='product" + i + "'> <option>select</option></select></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='form-control'></textarea></div></td>";

                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control" id="inner_diamitter' + i + '"></td>';
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control" id="outer_diamitter' + i + '"></td>';
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control" id="thikness' + i + '"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="form-control" id="hsn' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty' + i + '"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price' + i + '"></td>';


                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" class="discount_per form-control" oninput="cal(this)" id="discount_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_per[]" oninput="cal(this)" class="cgst_per form-control" id="cgst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_amount[]" class="cgst_amount form-control" id="cgst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_per[]" oninput="cal(this)" class="sgst_per form-control" id="sgst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_amount[]" class="sgst_amount form-control" id="sgst_amount' + i + '"></td>';


                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price' + i + '"></td>';
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="cursor:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);
                initProductAjaxSelect2($("#product" + i), 'service');
                $("#totrow").val(i);
            });

            $("#add_bom").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr id='row" + i + "'><td><div class='input-group'><select class='form-control product' onchange='get_bom(this.value," + i + ")' name='product[]' id='product" + i + "'> <option>select</option></select></div><div class='form-group'><label></label><textarea id='description" + i + "' name='description[]' class='form-control editor'></textarea></div></td>";
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control" id="inner_diamitter' + i + '"></td>';
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control" id="outer_diamitter' + i + '"></td>';
                // data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control" id="thikness' + i + '"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="form-control" id="hsn' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty' + i + '"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price' + i + '" oninput="cal(this)"></td>';
                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" oninput="cal(this)" class="discount_per form-control" id="discount_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" oninput="cal(this)" class="discount_amount form-control" id="discount_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_per[]" oninput="cal(this)" class="cgst_per form-control" id="cgst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_amount[]" class="cgst_amount form-control" id="cgst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_per[]" oninput="cal(this)" class="sgst_per form-control" id="sgst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_amount[]" class="sgst_amount form-control" id="sgst_amount' + i + '"></td>';


                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" oninput="cal(this)" class="gst_per form-control" id="gst_per' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount' + i + '"></td>';

                data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price' + i + '"></td>';
                data += '<td class="actions" style="vertical-align: top !important;text-align:center;cursor:pointer"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

                $("#caltable").append(data);
                initProductAjaxSelect2($("#product" + i), 'bom');
                $("#totrow").val(i);
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
