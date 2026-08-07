<?php
$inr = "<span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>";
?>

<?php
if ($discsum == 0) {
    $totcol = 13;
    $totdivide = 7;
    $totdivide1 = 6;

} else {
    $totcol = 14;
    $totdivide = 7;
    $totdivide1 = 7;
}
?>
    <!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        * {
            /*box-sizing: border-box;*/
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            font-size: 10px;
        }

        section {

        }

        .bank td {
            text-align: left;
        }

        .quotation {

        }

        .amount td {
            text-align: left;
            vertical-align: top;

        }

        .total td {
            border: none;
            border-top: 1px solid black;
        }

        .column {
            float: left;

        }

        .middle .row {
            margin-bottom: 8px;

        }

        .middle, .right {

        }


        .left, .right, .middle {
            width: 33.33%;

        }

        .row:after {
            content: "";
            display: table;
            clear: both;

        }

        .subject th {
            background-color: white;
            padding: 10px;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th {
            background-color: #ccc;
        }

        td {
            text-align: center;

        }

        .middle table {
            width: 97%;
        }

        .right table {
            width: 80%;
        }

        .heading h1 {
            text-align: center;
            font-size: 35px;
            color: #3571b6;
        }

        .left-title p {
            /*text-align: justify;*/
            /*width: 66%;*/
            /*font-size: 14px;*/
        }

        .heading {
            margin-top: 0px;
        }

        .subject table {
            width: 100%;
            text-align: left;
        }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 0px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;}
        .header .pagenum:before { content: counter(page);}

        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

        textarea.form-control {
            min-height: 90px;
        }
        textarea.form-control {
            height: auto;
        }
        .form-control {
            resize: none;
            border: 0;
            border-bottom-color: currentcolor;
            border-bottom-style: none;
            border-bottom-width: 0px;
            background-color: transparent;
            border-bottom: 1px solid rgba(152, 152, 152, 0.8);
            border-radius: 0 !important;
            padding: 7px 12px 7px 0;
            height: 38px;
            max-width: 100%;
            -webkit-box-shadow: none;
            box-shadow: none;
            -webkit-transition: all 300ms linear;
            -moz-transition: all 300ms linear;
            -o-transition: all 300ms linear;
            transition: all 300ms linear;
        }
        .btn-group .btn, .btn-group .btn-group, .btn-group .btn:active, .btn-group .btn:focus, .btn-group-vertical .btn, .btn-group-vertical .btn-group, .btn-group-vertical .btn:active, .btn-group-vertical .btn:focus, .form-control, input:active, input:focus {
            box-shadow: none;
        }
        .form-control {
            display: block;
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }
        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }
        textarea {
            overflow: auto;
        }
        button, input, optgroup, select, textarea {
            margin: 0;
            font: inherit;
            font-size: inherit;
            line-height: inherit;
            font-family: inherit;
            color: inherit;
        }
        * {
            outline: none !important;
        }
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<div class="header">
    <h5>Page <span class="pagenum"></span></h5>
</div>
<div class="footer">

    <span style="text-align: left !important;font-weight: 600">Quote Prepared By : {{$quot->first_name}} {{$quot->last_name}}</span>

</div>

<div class="heading">
    <h1>QUOTATION</h1>
</div>

<table style="border: none">
    <tr>
        <td style="width:25%;vertical-align: top;border: none;text-align: left">
            <!-- -----left column----- -->
            <table align="left" style="width: 100%;border: none">
                <tr>
                    <td style=" vertical-align: top;text-align: left;border: none">
                        <img height="85px" src="{{asset('public/company_logo/'.$company->logo)}}">
                        <br>
                        <span style="font-size: 14px;font-weight: 800">{{$company->company_name}}</span>
                       <br> {!! $company->address !!}<br>
                        {!! $company->address1 !!}<br>
                        {!! $company->address2 !!}<br>
                        {{$company->state_name}} , {{$company->pincode}}<br>
                       Mobile :- {!! $company->mobile !!}<br>
                       GST No. :- {!! $company->gst !!}<br>
                    </td>

                </tr>

            </table>
        </td>
        <td style="width: 40%;vertical-align: top;border: none;padding: 15px">

            <table align="center" style="width: 100%">
                <tr>
                    <th>Company</th>
                </tr>
                <tr>
                    <td> {{$quot->customer_name}}</td>
                </tr>
            </table>

            <table align="center" style="width: 100%;padding-top: 10px">
                <tr>
                    <th>Billing Address</th>
                </tr>
                <tr>
                    <td style="text-align: left">{{strip_tags($quot->billing_address)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left"><b>Phone :</b>{{$quot->primary_phone}}</td>
                </tr>
                <tr>
                    <td style="text-align: left"><b>Email :</b> {{$quot->primary_email}}</td>
                </tr>
            </table>

            <table align="center" style="width: 100%;padding-top: 10px">
                <tr>
                    <th colspan="2">Customer Contact</th>
                </tr>
                <tr>
                    <td>{{$quot->contact_name}}</td>
                    <td>{{$quot->primary_phone}}</td>
                </tr>
            </table>

            <table align="center" style="width: 100%;padding-top: 10px">
                <tr>
                    <th>GST No</th>
                </tr>
                <tr>
                    <td>{{$quot->owner_gst}} </td>
                </tr>
            </table>

        </td>
        <td style="width: 35%;vertical-align: top;border: none;padding: 15px">

            <table align="center" style="width: 100%">
                <tr>
                    <th colspan="2">Quotes : {{$quot->quotation_no}}</th>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none;width: 40%"><b>Issued Date</b> </td>
                    <td style="border-left: none;text-align: left;width: 60%"> : {{date('d-m-Y',strtotime($quot->quot_date))}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none"><b>Valid Date</b> </td><td style="border-left: none;text-align: left"> : {{date('d-m-Y',strtotime($quot->valid_date))}}</td>
                </tr>
            </table>

            <table align="center" style="width: 100%;padding-top: 40px">
                <tr>
                    <th colspan="2">Bank Details</th>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none"><b>Bank Name</b></td>
                    <td style="text-align: left;border-left: none"> : {{$company->bank_name}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none"><b>Bank Branch</b></td>
                    <td style="text-align: left;border-left: none"> : {{$company->bank_branch}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none"><b>MICR Code</b></td>
                    <td style="text-align: left;border-left: none"> : {{$company->micr_code}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none"><b>Account No</b></td>
                    <td style="text-align: left;border-left: none"> : {{$company->account_no}}</td>
                </tr>
                <tr>
                    <td style="text-align: left;border-right: none"><b>IFSC Code</b></td>
                    <td style="text-align: left;border-left: none"> : {{$company->ifsc_code}}</td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<div class="subject">
    <table>
        <tr>
            <th>Sub : Quotation for Antivirus Escan Total Security for Business renewal</th>
        </tr>

    </table>
</div>
<table style="border-collapse: collapse;padding-top: 10px;width: 100%">
    <tr>
        <th >Sr.</th>
        <th style="">Description</th>
{{--        <th>ID</th>--}}
{{--        <th>OD</th>--}}
{{--        <th>THK</th>--}}
{{--        <th>Material</th>--}}
        <th>HSN</th>
{{--        <th>Photo</th>--}}
        <th style="" colspan="2">Qty</th>
        <th style="">Rate</th>
        <th style="">Sub<br> Total</th>
        <th style="">Disc<br>(%)</th>
        <th style="">Disc<br> AMT</th>

        <th style="">Tax<br>(%)</th>
{{--        <th style="">Tax<br>(INR)</th>--}}
        <th style="">Total</th>
    </tr>

    <?php
    $srno = $total = $gsttotal = $grand = $discount = $totcgst=$totsgst=0;
    ?>
    @foreach($quotitem as $item)

        <?php
        $srno++;
        $totgstperc=$gst_total=0;
        ?>
        <tr>
            <td style="text-align: center;vertical-align:top;width: 5%">{{$srno}}</td>
            <td style="text-align: left;vertical-align:top;"><x-product-name :row="$item" print />
                <br><span style="font-size: 8px">{{$item->description}}</span>
            </td>
{{--            <td style="text-align: center;vertical-align:top;width: 3%">{{$item->inner_diameter}}</td>--}}
{{--            <td style="text-align: center;vertical-align:top;width: 3%">{{$item->outer_diameter}}</td>--}}
{{--            <td style="text-align: center;vertical-align:top;width: 3%">{{$item->thikness}}</td>--}}
{{--            <td style="text-align: left;vertical-align:top;width: 3%;">{{$item->material_name}}</td>--}}
            <td style="text-align: center;vertical-align:top;width: 3%">{{$item->hsn}}</td>

{{--            <td style="text-align: center;vertical-align:top;width: 3%">--}}
{{--                @if($item->catname=="AS PER DRAWING")--}}
{{--                    <img src="{{asset('public/product_image/'.$item->product_image)}}" style="height: 45px"--}}
{{--                         width="45px">--}}
{{--                @endif--}}
{{--                @if(empty($item->category_image))--}}
{{--                @else--}}
{{--                    <img src="{{asset('public/product_category/'.$item->category_image)}}" style="height: 45px"--}}
{{--                         width="45px">--}}
{{--                @endif--}}
{{--            </td>--}}
            <td style="text-align: center;vertical-align:top;width: 3%">{{$item->qty}}</td>
            <td style="text-align: center;vertical-align:top;width: 3%">{{$item->uom_name}}</td>
            <td style="text-align: right;vertical-align:top;width: 3%">{{number_format($item->price,2,'.',',')}}</td>
            <td style="text-align: right;vertical-align:top;width: 3%">{{number_format($item->amount,2,'.',',')}}</td>
            <td style="text-align: right;vertical-align:top;width: 3%">{{$item->discount_per}}</td>
            <td style="text-align: right;vertical-align:top;width: 3%">{{$item->discount_amount}}</td>

            @if($item->gst_per==0)
                <?php
                $sgsttotal=$item->sgst_amount;
                $cgsttotal=$item->cgst_amount;
                $gst_total=$sgsttotal+$cgsttotal;
                $totgstperc=$item->cgst_per+$item->sgst_per;
                $gsttotal =$gsttotal+ $gst_total;
                $totcgst=$totcgst+$item->cgst_amount;
                $totsgst=$totsgst+$item->sgst_amount;
                ?>
                <td style="text-align: right;vertical-align:top;width: 3%">{{$totgstperc}}</td>
{{--                <td style="text-align: right;vertical-align:top;width: 3%">{{number_format($gsttotal,2,'.',',')}}</td>--}}
            @else
            <?php
                $gsttotal =$gsttotal+ $item->gst_amount;
            ?>
                <td style="text-align: right;vertical-align:top;width: 3%">{{$item->gst_per}}</td>
{{--                <td style="text-align: right;vertical-align:top;width: 3%">{{$item->gst_amount}}</td>--}}
            @endif

            <td style="text-align: right;vertical-align:top;width: 3%">{{number_format($item->grand_total,2,'.',',')}}</td>


            <?php
            $total = $total + $item->amount;
            $discount = $discount + $item->discount_amount;

            $grand = $grand + $item->grand_total;
            ?>
        </tr>
        <tr><td colspan="11"></td></tr>
    @endforeach
        <tr>
            <td style="text-align: left" colspan="6">Subtotals</td>

            <td>{{number_format($total,2,'.',',')}}</td>
            <td></td>
            <td>{{number_format($discount,2,'.',',')}}</td>

            <td></td>
            <td>{{number_format($grand,2,'.',',')}}</td>
        </tr>
        <tr>
            <td style="text-align: left" colspan="10">Discount</td>

            <td style="text-align: right">{{number_format($discount,2,'.',',')}}</td>
        </tr>
        @if($totcgst !="" || $totcgst != 0)
        <tr>
        <td style="text-align: left" colspan="10">CGST</td>

        <td style="text-align: right">{{number_format($totcgst,2,'.',',')}}</td>
        </tr>

        <tr>
        <td style="text-align: left" colspan="10">SGST</td>

        <td style="text-align: right">{{number_format($totsgst,2,'.',',')}}</td>
    </tr>
    @else
        <tr>
            <td style="text-align: left" colspan="10">IGST</td>

            <td style="text-align: right">{{number_format($gsttotal,2,'.',',')}}</td>
        </tr>
    @endif
        <?php
         echo $round=round($grand);
         ?>
    <tr>
        <td style="text-align: left" colspan="10">Rounf Off</td>
        <?php
             $round=round($grand);
             $diff=$round-$grand;
             $gtot=$grand+$diff;
        ?>
        <td style="text-align: right">{{number_format($diff,2,'.',',')}}</td>
    </tr>
    <tr>
        <td style="text-align: left;font-weight: 800" colspan="10">Grand Total</td>

        <td style="text-align: right;font-weight: 800">{{number_format($round,2,'.',',')}}</td>
    </tr>
    <tr class="total" style="  background-color: #ccc;">
        <td style="text-align: left;font-weight: 800;padding-top: 12px;padding-bottom: 12px" colspan="11">
            <b>In Words :-
        <?php
            $f = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );

           echo $word = $f->format($round);
        ?>
            </b>
        </td>
    </tr>

    <tr>
        <th colspan="11" style="text-align: left;">Terms & Conditions</th>
    </tr>
    <tr>
        <td colspan="11" style="text-align: left;">
            <p>
                <?php
                echo $quot->term_condition;
                ?>
            </p>
        </td>
    </tr>

</table>

</body>
</html>
