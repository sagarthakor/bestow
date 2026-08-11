@extends('admin.layout.master_material')

@section('title', 'Edit | Invoice')

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
                            <h4 class="page-title">Invoice </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.invoice.list')}}">Invoice List </a>
                                </li>
                                <li class="active">
                                    Creating New Invoice
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                {{Form::model($data,['method'=>'post','route'=>'admin.invoice.update'])}}
                {{Form::hidden('id',null)}}
                <div class="panel">

                    <div class="panel-body">
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
                        <!-- <div class="col-md-4" style="display: none;" id="qno">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation No</label>
                                                                    {{Form::text('quot_no',null,['class'=>'form-control','id'=>"quot_no"])}}

                            </div>
                        </div> -->
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label class="control-label">Customer Name <span style="color: red">*</span></label>
                                    <?php

                                    ?>
                                    {{Form::select('customer',$customer,null,['required','class'=>'form-control js-example-basic-single','id'=>'customer','onchange'=>'getcustomer(this.value)'])}}


                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Quote No. </label>
                                    {{Form::text('quotation_no',null,['class'=>'form-control','id'=>"quotation_no"])}}


                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Quote Date</label>
                                     <?php
                                    if(isset($data->quot_date))
                                    {
                                        $quotDate=date('d-m-Y',strtotime($data->quot_date));
                                    }else{
                                        $quotDate="";
                                    }

                                    ?>
                                    {{Form::text('quot_date',$quotDate,['class'=>'form-control input-daterange-datepicker','id'=>"salaesorder_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Contact Name <span
                                                style="color: red">*</span></label>
                                        {{Form::select('contact_name',$contact_name,null,['required','class'=>'form-control','id'=>"contact_name"])}}

                                    </div>
                                </div>

                                <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">SO. No.</label>
                                    {{Form::text('salaesorder_no',null,['class'=>'form-control','id'=>"quotation_no"])}}


                                </div>
                            </div>

                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">SO Date </label>
                                    <?php
                                    if(isset($data->salaesorder_date))
                                    {
                                        $soDate=date('d-m-Y',strtotime($data->salaesorder_date));
                                    }else{
                                        $soDate="";
                                    }

                                    ?>
                                    {{Form::text('salaesorder_date',$soDate,['class'=>'form-control input-daterange-datepicker','id'=>"salaesorder_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Subject <span style="color: red">*</span></label>
                                    {{Form::text('subject',null,['required','class'=>'form-control','id'=>"subject"])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">DC No </label>
                                    {{Form::text('challan_number',null,['class'=>'form-control','id'=>"deliverychallan_no"])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                     <?php
                                    if(isset($data->invoice_date))
                                    {
                                        $challanDate=date('d-m-Y',strtotime($data->invoice_date));
                                    }else{
                                        $challanDate="";
                                    }

                                    ?>

                                    <label class="control-label">DC Date </label>
                                    {{Form::text('challan_date',$challanDate,['class'=>'form-control','id'=>"challan_date"])}}

                                </div>
                            </div>

                           <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Payment Terms <span style="color: red">*</span></label>
                                    <select onchange="getduedate(this.value)" class="form-control" name="payment_terms">
                                        <?php
                                        echo $payment_terms ?? "";
                                        ?>
                                    </select>
                                </div>
                            </div>

                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">PO No.</label>
                                    {{Form::text('purchase_order',null,['class'=>'form-control','id'=>"subject"])}}

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">PO Date.</label>
                                    {{Form::text('purchase_order_date',null,['class'=>'form-control','id'=>"purchase_order_date"])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Payment Due Date <span style="color: red">*</span></label>
                                    {{Form::text('invoice_duedate',$duedate,['required','class'=>'form-control input-daterange-datepicker','id'=>"due_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Invoice Date <span style="color: red">*</span></label>
                                    {{Form::text('invoice_date',date('d-m-Y'),['required','class'=>'form-control input-daterange-datepicker','id'=>"salaesorder_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="control-label">Invoice Date <span style="color: red">*</span></label>
                                    {{Form::text('invoice_number',null,['required','class'=>'form-control input-daterange-datepicker','id'=>"salaesorder_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-4">
                                <div class="form-group">
                                    <?php
                                    if(isset($data->delivery_date))
                                    {
                                        $deliveryDate=date('d-m-Y',strtotime($data->delivery_date));
                                    }else{
                                        $deliveryDate="";
                                    }

                                    ?>
                                    <label class="control-label">Delivery Date </label>
                                    {{Form::text('delivery_date',$deliveryDate,['class'=>'form-control input-daterange-datepicker','id'=>"delivery_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">E Way Bill No.</label>
                                    {{Form::text('eway_billno',null,['class'=>'form-control','id'=>"eway_billno",'autocomplete'=>'off'])}}

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">E Way Bill Date </label>
                                    <?php
                                    if(isset($data->eway_billdate))
                                    {
                                        $eway_billdate=date('d-m-Y',strtotime($data->eway_billdate));
                                    }else{
                                        $eway_billdate="";
                                    }

                                    ?>
                                    {{Form::text('eway_billdate',$eway_billdate,['class'=>'form-control input-daterange-datepicker','id'=>"eway_billdate",'autocomplete'=>'off'])}}

                                </div>
                            </div>


                            <div class="col-sm-12">

                                <h3>Address Details</h3>

                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Billing Address <span style="color: red">*</span></label>
                                        {{Form::textarea('billing_address',null,['required','class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'billing_address'])}}

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country <span style="color: red">*</span></label>

                                        {{Form::text('billing_country',null,['required','class'=>'form-control','id'=>"billing_country"])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State <span style="color: red">*</span></label>

                                        {{Form::text('billing_state',null,['required','class'=>'form-control','id'=>"billing_state"])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span style="color: red">*</span></label>

                                        {{Form::text('billing_city',null,['required','class'=>'form-control','id'=>"billing_city"])}}

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Postal Code <span style="color: red">*</span></label>

                                        {{Form::text('billing_postalcode',null,['required','class'=>'form-control','id'=>"billing_postalcode"])}}

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Shiping Address <span style="color: red">*</span></label>
                                        {{Form::textarea('shipping_address',null,['required','class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'shipping_address'])}}

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country <span style="color: red">*</span></label>
                                        {{Form::text('shipping_country',null,['required','class'=>'form-control','id'=>'shipping_country'])}}

                                    </div>
                                </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State <span style="color: red">*</span></label>
                                        {{Form::text('shipping_state',null,['required','class'=>'form-control','id'=>'shipping_state'])}}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span style="color: red">*</span></label>
                                        {{Form::text('shipping_city',null,['required','class'=>'form-control','id'=>'shipping_city'])}}

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Postal Code <span style="color: red">*</span></label>
                                        {{Form::text('shipping_postalcode',null,['required','class'=>'form-control','id'=>'shipping_postalcode'])}}

                                    </div>
                                </div>

                            </div>


                        </div>

                        <div class="tabledata">
                            <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                <thead>
                                <tr>
                                     <th style="text-align: center;width: 20% !important;">Bar Code</th>
                                    <th style="text-align: center;width: 20% !important;">Item Name</th>
                                    <th>Photo</th>
                                    <th style="text-align: center;">HSN</th>
                                    <th style="text-align: center;">Qty</th>
+
                                    <th style="text-align: center;">Unit Price</th>
                                    <th style="text-align: center;">Total</th>


                                    <th style="text-align: center;">Net Price</th>

                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $srno=$total=$gsttotal=$grand=0;
                                $amount=0;
                                $gstamount=0;
                                $netamount=0;

                                ?>
                                @forelse($quotitem as $item)
                                    <?php
                                    $srno++;
                                    ?>
                                    <?php
                                    $total=$total+$item->amount;
                                    $gsttotal=$gsttotal+$item->gst_amount;
                                    $grand=$grand+$item->grand_total;

                                    $stockqty=$item->stockqty ?? "0";

                                    ?>

                                    <tr  id="row{{$srno}}">
                                         <td   style='vertical-align: top !important;text-align: center;width:10%' width="10%"><input type="text" value="{{$item->bar_code}}"  onfocusout="search_product(this)" class="itemname form-control"></td>
                                        <td style="vertical-align: top !important;">
                                            <div class="form-group">
                                                <select class="product listPrice smallInputBox inputElement"
                                                        onchange="get_product(this)" name="product[]"
                                                        id="product{{$srno}}" required>
                                                    <option value="{{$item->product}}" selected>{{$item->item_code}} - {{ \App\product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null) }}</option>
                                                    {{--                                                        @foreach($product as $prod)--}}
                                                    {{--                                                            <option value="{{$prod->id}}">{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}</option>--}}
                                                    {{--                                                        @endforeach--}}
                                                </select>

                                            </div>

                                            <div class="form-group">
                                                <label>Description </label><br>
                                                <textarea cols="1" rows="1" style="width: 100%;" id="description{{$srno}}" name="description[]"
                                                          class="description">{{$item->description}}</textarea>
                                            </div>

                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            @if($item->product_image=="")
                                                <img src="{{asset('public/no-img.png')}}" name="photo[]" style="height: 100px;width: 100px;" class="img-responsive photo">
                                            @else
                                                <img src="{{asset('product_image/'.$item->product_image)}}" name="photo[]" style="height: 100px;width: 100px;" class="img-responsive photo">
                                            @endif
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">
                                            <input type="text" class="hsn smallInputBox inputElement" name="hsn[]" value="{{$item->hsn}}"
                                                   id="hsn{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;width: 10%">
                                            <input readonly type="text" name="qty[]" onkeyup="cal(this)" value="{{$item->qty}}"
                                                   class="qty smallInputBox inputElement" id="qty{{$srno}}">

                                        </td>


                                        <td style="vertical-align: top !important;">
                                            <div>
                                                <input oninput="cal(this)"  name="price[]" value="{{$item->price}}"
                                                       type="text"
                                                       data-rule-required="true" data-rule-positive="true"
                                                       class="price listPrice smallInputBox inputElement"
                                                       data-is-price-changed="false" list-info=""
                                                       data-base-currency-id="" aria-required="true" autocomplete="off"
                                                       aria-invalid="false">&nbsp;<span
                                                    class="priceBookPopup cursorPointer" data-popup="Popup"
                                                    title="Price Books" data-module-name="PriceBooks"
                                                    style="float:left">
                                                    <i class="vicon-pricebooks" title="Price Books"></i>
                                                </span>
                                            </div>
                                            <div style="clear:both"></div>
                                            <div>
                                                <span>(-)&nbsp;<strong>
                                                         <a style="cursor: pointer" onclick="disDiv(this)">Discount</a>
                                                         (<span class="discountPerc">{{$item->discount_per}}</span>%)
                                                        </a> :
                                                        <div class="discountDiv" style="display: none">
                                                            <div class="form-group" style="width: 50%;display: inline;float: left;">
                                                                <label>Disc %</label>
                                                                <input oninput="cal(this)"
                                                                       name="discount_per[]"
                                                                       value="{{$item->discount_per}}" type="text"
                                                                       data-rule-required="true"
                                                                       data-rule-positive="true"
                                                                       class="discount_per listPrice smallInputBox inputElement"
                                                                       data-is-price-changed="false" list-info=""
                                                                       data-base-currency-id="" aria-required="true"
                                                                       autocomplete="off" aria-invalid="false" style="width: 80%;">

                                                            </div>
                                                            <div class="form-group" style="width: 50%;float: left;">
                                                                <label>Disc Amt</label>
                                                                <input oninput="discmatcal(this)"
                                                                       name="discount_amount[]"
                                                                       value="{{$item->discount_amount}}" type="text"
                                                                       class="discount_amount inputElement" style="width: 80%;">

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
                                                            <label>CGST %</label>
                                                            <input style="width: 35px;" oninput="cal(this)"
                                                                   name="cgst_per[]"
                                                                   value="{{$item->cgst_per}}" type="text"
                                                                   data-rule-required="true" data-rule-positive="true"
                                                                   class="cgst_per listPrice smallInputBox inputElement"
                                                                   data-is-price-changed="false" list-info=""
                                                                   data-base-currency-id="" aria-required="true"
                                                                   autocomplete="off" aria-invalid="false">

                                                        </div>
                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">
                                                            <label>SGST %</label>
                                                            <input style="width: 35px;" oninput="cal(this)"
                                                                   name="sgst_per[]"
                                                                   value="{{$item->sgst_per}}" type="text"
                                                                   data-rule-required="true" data-rule-positive="true"
                                                                   class="sgst_per listPrice smallInputBox inputElement"
                                                                   data-is-price-changed="false" list-info=""
                                                                   data-base-currency-id="" aria-required="true"
                                                                   autocomplete="off" aria-invalid="false">

                                                        </div>
                                                        <div class="form-group" style="width: 32%;float: left;">
                                                            <div class="form-group" >
                                                                <label>IGST %</label>
                                                                <input style="width: 35px;" oninput="cal(this)"
                                                                       name="gst_per[]"
                                                                       value="{{$item->igst_per}}" type="text"
                                                                       data-rule-required="true" data-rule-positive="true"
                                                                       class="gst_per listPrice smallInputBox inputElement"
                                                                       data-is-price-changed="false" list-info=""
                                                                       data-base-currency-id="" aria-required="true"
                                                                       autocomplete="off" aria-invalid="false">

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
                                            <input type="hidden" class="total" value="{{$item->total_amount}}" name="total_amount[]">
                                            <div  align="right" class="productTotal">{{$item->total_amount}}</div>
                                            <div  align="right" class="discountTotal">
                                                {{$item->discount_amount}}
                                            </div>
                                            <div  align="right" class="totalAfterDiscount">
                                                {{$item->total_amount-$item->discount_amount}}
                                            </div>
                                            <div id="taxTotal1" align="right" class="productTaxTotal">
                                                {{$item->cgst_amount+$item->sgst_amount+$item->gst_amount}}
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
                                            <input type="text" name="gst_per[]" value="{{$item->igst_per}}"
                                                   class="gst_per form-control" id="gst_per{{$srno}}"
                                                   oninput="cal(this)">
                                        </td>
                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="gst_amount[]" value="{{$item->igst_amount}}"
                                                   class="gst_amount form-control" id="gst_amount{{$srno}}">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">

                                            <input type="text" name="net_price[]" value="{{$item->net_price}}"
                                                   class="netprice smallInputBox inputElement" id="net_price{{$srno}}">
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

                                    <?php
                                    $amount=$amount+$item->amount;
                                    $gstamount=$gstamount+$item->gst_amount;
                                    $netamount= $netamount+$item->grand_total;
                                    ?>
                                @empty
                                @endforelse


                                </tbody>
                                <tfoot>
                                {{--                                <tr>--}}
                                {{--                                    <td colspan="7">--}}
                                {{--                                        <div class="col-md-2">--}}
                                {{--                                            <a class="btn btn-default" id="add_product">+ Add Product</a>--}}
                                {{--                                        </div>--}}

                                {{--                                        <div class="col-md-2">--}}
                                {{--                                            <a class="btn btn-default" id="add_service">+ Add Service</a>--}}
                                {{--                                        </div>--}}

                                {{--                                        <div class="col-md-2">--}}
                                {{--                                            <a class="btn btn-default" id="add_bom">+ Add BOM</a>--}}
                                {{--                                        </div>--}}

                                {{--                                        <input type="hidden" id="totrow" value="1">--}}
                                {{--                                    </td>--}}
                                {{--                                </tr>--}}
                                <tr>
                                    <td colspan="5" style="text-align: right;">Item Total</td>
                                    <td colspan="2" style="text-align: right;">
                                        <input type="text" class="form-control item_total" name="item_total"
                                               id="item_total" value="{{$data->item_total ?? 0}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">Discount Total</td>
                                    <td colspan="2" style="text-align: right;"><input type="text"
                                                                                      class="form-control discount_total"
                                                                                      name="discount_total" id="discount_total"
                                                                                      value="{{$data->discount_total}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">CGST Total</td>
                                    <td style="text-align: right;" colspan="2"><input type="text" class="form-control cgsttotal"
                                                                                      name="cgsttotal" id="cgsttotal"
                                                                                      value="{{$data->cgsttotal ?? "0"}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">SGST Total</td>
                                    <td style="text-align: right;" colspan="2"><input type="text" class="form-control sgsttotal"
                                                                                      name="sgsttotal" id="sgsttotal"
                                                                                      value="{{$data->sgsttotal ?? "0"}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">IGST Total</td>
                                    <td style="text-align: right;" colspan="2"><input oninput="cal(this)" type="text" class="form-control gsttotal"
                                                                                      name="igsttotal" id="igsttotal"
                                                                                      value="{{$data->igsttotal ?? 0}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">Adjustment</td>
                                    <td style="text-align: right;" colspan="2"><input type="text" class="form-control adjustment"
                                                                                      name="adjustment" id="adjustment"
                                                                                      value="{{$data->adjustment ?? 0}}"></td>

                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">Grand Total</td>
                                    <td style="text-align: right;" colspan="2"><input type="text" class="form-control grand_total"
                                                                                      name="grand_total" id="grand_total"
                                                                                      value="{{$data->grand_total ?? "0"}}"></td>

                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-sm-12">

                            <h3>Terms & Conditions</h3>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Terms</label>
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


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    @include('admin.partials._product_search')
    <script type="text/javascript">

    function search_product(ele){
                    $(ele).closest('tr').find('.product option').remove();
                    var itemname= $(ele).closest('tr').find('.itemname').val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url:appurl+'/api/get_product',
                        data:{itemname:itemname},
                        method:'get',
                        success:function(response){

                            $(ele).closest('tr').find('.product').html(response);

                            get_product(ele);


                        }
                    });
                }

        $(document).ready(function () {

            ClassicEditor
                .create(document.querySelector('#term_condition'))
                .catch(error => {
                    console.error(error);
                });

            $('.js-example-basic-single').select2();

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
        })
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


                $.ajax({
                    url: appurl + '/invoice/get_product',
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
                            // alert(data[0]['stockqty']);
                            $(ele).closest('tr').find(".stockqty").val(data[0]['stockqty']);
                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }
        }

        function get_service(ele) {
            var customer = $("#customer").val();
            var product = $(ele).closest('tr').find('.product').val();
            var appurl = "{{url('/')}}";
            $.ajax({
                url: appurl + '/invoice/get_product',
                data: {product: product,customer:customer},
                method: 'get',
                dataType: 'json',
                success: function (data) {
                    var len = data.length;
                    if (len > 0) {
                        $(ele).closest('tr').find(".description").val(data[0]['description']);
                        $(ele).closest('tr').find(".price").val(data[0]['price']);
                        $(ele).closest('tr').find(".gst_per").val(data[0]['gst']);
                        $(ele).closest('tr').find('.cgst_per').val(data[0]['cgst']);
                        $(ele).closest('tr').find('.sgst_per').val(data[0]['sgst']);

                        //$("#make"+srno).val(data[0]['make']);
                    }
                }
            });
        }
        function get_bom(ele) {
            var customer = $("#customer").val();
            var product = $(ele).closest('tr').find('.product').val();
            var appurl = "{{url('/')}}";
            $.ajax({
                url: appurl + '/invoice/get_product',
                data: {product: product,customer:customer},
                method: 'get',
                dataType: 'json',
                success: function (data) {
                    var len = data.length;
                    if (len > 0) {
                        $(ele).closest('tr').find(".qty").val("");
                        $(ele).closest('tr').find(".price").val("");
                        $(ele).closest('tr').find(".total_amount").val("");
                        $(ele).closest('tr').find(".discount_per").val("");
                        $(ele).closest('tr').find(".discount_amount").val("");
                        $(ele).closest('tr').find(".gst_per").val("");
                        $(ele).closest('tr').find(".gst_amount").val("");
                        $(ele).closest('tr').find(".description").html(data[0]['description']);


                        $(ele).closest('tr').find(".price").val(data[0]['price']);
                        $(ele).closest('tr').find('.cgst_per').val(data[0]['cgst']);
                        $(ele).closest('tr').find('.sgst_per').val(data[0]['sgst']);
                        $(ele).closest('tr').find(".gst_per").val(data[0]['gst']);
                        $(ele).closest('tr').find(".inner_diamitter").val(data[0]['inner_diameter']);
                        $(ele).closest('tr').find(".outer_diamitter").val(data[0]['outer_diameter']);
                        $(ele).closest('tr').find(".thikness").val(data[0]['thikness']);
                        $(ele).closest('tr').find(".hsn").val(data[0]['hsn']);
                        $(ele).closest('tr').find(".discount_per").val(data[0]['discper']);

                        var htmlString = data[0]['description'];

                        var stripedHtml = $(ele).closest('tr').find(".description").html(htmlString).text();

                    }
                }
            });
        }

        function stockcheck(ele)
        {

        }
        function cal(ele) {
            var rate = $(ele).closest('tr').find('.price').val();
            var qty = $(ele).closest('tr').find('.qty').val();
            var total = Number(rate) * Number(qty);

            $(ele).closest('tr').find('.total').val(total.toFixed(2));

            var discount_per = $(ele).closest('tr').find('.discount_per').val();

            var cgst_per = $(ele).closest('tr').find('.cgst_per').val();

            var sgst_per = $(ele).closest('tr').find('.sgst_per').val();

            var gst_per = $(ele).closest('tr').find('.gst_per').val();

            var discount_amount = Number(total) * Number(discount_per) / 100;

            var afterdisc = Number(total) - Number(discount_amount);

            $(ele).closest('tr').find('.discount_amount').val(discount_amount.toFixed(2));

            // alert(gst_per);
            var cgst_amount=Number(afterdisc)*Number(cgst_per)/100;
            var sgst_amount=Number(afterdisc)*Number(sgst_per)/100;
            var gst_amount = Number(afterdisc) * Number(gst_per) / 100;
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
            $("#cgsttotal").val(cgst_total.toFixed(2));
            $("#sgsttotal").val(sgst_total.toFixed(2));
            $("#igsttotal").val(gst_total.toFixed(2));

            var round=Math.round(netpricestotal);
            var diff=Number(round)-Number(netpricestotal);
            var gtot=Number(netpricestotal)+Number(diff);
            $("#adjustment").val(diff.toFixed(2));
            $("#grand_total").val(round.toFixed(2));

            //$("#grand_total").val(netpricestotal.toFixed(2));

        }



        function model_close()
        {
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

        // The picker itself lives in admin.partials._product_search so every
        // document screen searches the catalogue the same way; this stays as
        // the name the row builders already call it by.
        function initProductAjaxSelect2($select, status) {
            ProductSearch.attach($select, status);
        }
    </script>

    <script>

        function getduedate(days)
        {
            var salaesorder_date=$("#salaesorder_date").val();
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/getinvoiceduedate',
                data:{salaesorder_date:salaesorder_date,days:days},
                method:'get',
                success:function (res)
                {
                    $("#due_date").val(res);
                }
            });
        }

        $("#add_product").click(function(e){
            e.preventDefault();
            var i=$("#totrow").val();
            i++;

            var data="<tr id='row"+i+"'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td style='width: 20%'><div class='input-group'><select class='product form-control' onchange='get_product(this)' name='product[]' id='product"+i+"'> <option>select</option></select></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control inner_diamitter" id="inner_diamitter'+i+'"></td>';
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control outer_diamitter" id="outer_diamitter'+i+'"></td>';
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control thikness" id="thikness'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="form-control hsn" id="hsn'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'" style="width:55px"><br>Stock on<br>hand : <input type="text" style="width: 36px;border: none" class="stockqty"></input></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price'+i+'" oninput="cal(this)"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" oninput="cal(this)" class="discount_per form-control" id="discount_per'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" oninput="cal(this)" class="discount_amount form-control" id="discount_amount'+i+'"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_per[]" oninput="cal(this)" class="cgst_per form-control" id="cgst_per' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_amount[]" class="cgst_amount form-control" id="cgst_amount' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_per[]" oninput="cal(this)" class="sgst_per form-control" id="sgst_per' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_amount[]" class="sgst_amount form-control" id="sgst_amount' + i + '"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" oninput="cal(this)" class="gst_per form-control" id="gst_per'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'" oninput="cal(this)"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
            data +='<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

            $("#caltable").append(data);

            initProductAjaxSelect2($("#product" + i), 'product');

            $("#totrow").val(i);
        });


        $("#add_bom").click(function(e){
            e.preventDefault();
            var i=$("#totrow").val();
            i++;

            var data="<tr id='row"+i+"'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td><div class='input-group'><select class='form-control product' onchange='get_bom(this)' name='product[]' id='product"+i+"'> <option>select</option></select></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control editor'></textarea></div></td>";
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="inner_diamitter form-control" id="inner_diamitter'+i+'"></td>';
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="outer_diamitter form-control" id="outer_diamitter'+i+'"></td>';
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="thikness form-control" id="thikness'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="hsn[]"  class="hsn form-control" id="hsn'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="price[]" class="price form-control" id="price'+i+'" oninput="cal(this)"></td>';
            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_per[]" oninput="cal(this)" class="discount_per form-control" id="discount_per'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="discount_amount[]" oninput="cal(this)" class="discount_amount form-control" id="discount_amount'+i+'"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_per[]" oninput="cal(this)" class="cgst_per form-control" id="cgst_per' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_amount[]" class="cgst_amount form-control" id="cgst_amount' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_per[]" oninput="cal(this)" class="sgst_per form-control" id="sgst_per' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_amount[]" class="sgst_amount form-control" id="sgst_amount' + i + '"></td>';


            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_per[]" oninput="cal(this)" class="gst_per form-control" id="gst_per'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
            data +='<td class="actions" style="vertical-align: top !important;text-align:center;cursor:pointer"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

            $("#caltable").append(data);
            initProductAjaxSelect2($("#product" + i), 'bom');


            $("#totrow").val(i);
        });


        $("#add_service").click(function(e){
            e.preventDefault();
            var i=$("#totrow").val();
            i++;

            var data="<tr id='row"+i+"'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td><div class='input-group'><select class='form-control product' onchange='get_service(this)' name='product[]' id='product"+i+"'> <option>select</option></select></div><div class='form-group'><label></label><textarea id='description"+i+"' name='description[]' class='form-control'></textarea></div></td>";
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="inner_diamitter[]"  class="form-control inner_diamitter" id="inner_diamitter'+i+'"></td>';
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="outer_diamitter[]"  class="form-control outer_diamitter" id="outer_diamitter'+i+'"></td>';
            // data +='<td style="vertical-align: top !important;text-align:center"><input type="text" name="thikness[]"  class="form-control thikness" id="thikness'+i+'"></td>';

            data +='<td style="vertical-align: top !important;"><input type="text" name="hsn[]" oninput="cal(this)" class="hsn form-control" id="hsn'+i+'"></td>';

            data +='<td style="vertical-align: top !important;"><input type="text" name="qty[]" oninput="cal(this)" class="qty form-control" id="qty'+i+'"></td>';
            data +='<td style="vertical-align: top !important;"><input type="text" name="price[]" class="price form-control" id="price'+i+'" oninput="cal(this)" ></td>';
            data +='<td style="vertical-align: top !important;"><input type="text" name="total_amount[]" class="total form-control" id="total_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;"><input type="text" name="discount_per[]" class="discount_per form-control" id="discount_per'+i+'" style="width:45px" oninput="cal(this)" ></td>';

            data +='<td style="vertical-align: top !important;"><input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount'+i+'"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_per[]" oninput="cal(this)" class="cgst_per form-control" id="cgst_per' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="cgst_amount[]" class="cgst_amount form-control" id="cgst_amount' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_per[]" oninput="cal(this)" class="sgst_per form-control" id="sgst_per' + i + '"></td>';

            data += '<td style="vertical-align: top !important;text-align:center"><input type="text" name="sgst_amount[]" class="sgst_amount form-control" id="sgst_amount' + i + '"></td>';


            data +='<td style="vertical-align: top !important;"><input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per'+i+'" style="width:45px" oninput="cal(this)" ></td>';

            data +='<td style="vertical-align: top !important;"><input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount'+i+'"></td>';

            data +='<td style="vertical-align: top !important;"><input type="text" name="net_price[]" class="netprice form-control" id="net_price'+i+'"></td>';
            data +='<td class="actions" style="vertical-align: top !important;"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';

            $("#caltable").append(data);

            initProductAjaxSelect2($("#product" + i), 'service');

            $("#totrow").val(i);
        });

        function add_customer()
        {
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


                $("#item_total").val(item_total);
                $("#gsttotal").val(gst_total);
                $("#cgsttotal").val(cgst_total);
                $("#sgsttotal").val(sgst_total);

                $("#grand_total").val(netpricestotal);
            } else {
                return false;
            }
        }

        $( "#quot_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });

        $( "#salaesorder_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });

        $( "#due_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });
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
            $( "#due_date" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });
        });


    </script>
@endsection
