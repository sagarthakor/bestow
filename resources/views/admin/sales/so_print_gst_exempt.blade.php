<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{$quot->customer_name}}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /*@page { margin: 100px 50px; }*/
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 0px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;}
        .header .pagenum:before { content: counter(page);}

        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

        table
        {
            width: 100%;

            /*margin: 5px 0px;*/
        }
        table, th, td {
            border: 1px solid rgba(0,0,0,.1);
            border-collapse: collapse;
        }
        tr, td, th
        {

            color: #000;
            padding: 1px 1px;
            line-height: 15px;
            letter-spacing: 1px;
            font-size: 11px;
        }

        th, td p
        {
            letter-spacing: 1px;
            line-height: 15px;
            margin:0px auto;
        }
    </style>

    <style>
        /*       * {*/
        /*           font-family: Arial, Helvetica, sans-serif;*/
        /*       }*/

        /*       body {*/
        /*           font-size: 10px;*/
        /*       }*/



        /*       table, th, td {*/
        /*border: 1px solid black;*/
        /*           border-collapse: collapse;*/
        /*       }*/




        /*       .subject table {*/
        /*           width: 100%;*/

        /*       }*/
        /*       .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 0px; text-align: center; }*/
        /*       .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;}*/
        /*       .header .pagenum:before { content: counter(page);}*/

        /*       table { page-break-inside:auto }*/
        /*       tr    { page-break-inside:avoid; page-break-after:auto }*/
        /*       thead { display:table-header-group }*/
        /*       tfoot { display:table-footer-group }*/

        /*       textarea.form-control {*/
        /*           min-height: 90px;*/
        /*       }*/
        /*       textarea.form-control {*/
        /*           height: auto;*/
        /*       }*/
        /*       .form-control {*/
        /*           resize: none;*/
        /*           border: 0;*/
        /*           border-bottom-color: currentcolor;*/
        /*           border-bottom-style: none;*/
        /*           border-bottom-width: 0px;*/
        /*           background-color: transparent;*/
        /*           border-bottom: 1px solid rgba(152, 152, 152, 0.8);*/
        /*           border-radius: 0 !important;*/
        /*           padding: 7px 12px 7px 0;*/
        /*           height: 38px;*/
        /*           max-width: 100%;*/
        /*           -webkit-box-shadow: none;*/
        /*           box-shadow: none;*/
        /*           -webkit-transition: all 300ms linear;*/
        /*           -moz-transition: all 300ms linear;*/
        /*           -o-transition: all 300ms linear;*/
        /*           transition: all 300ms linear;*/
        /*       }*/
        /*       .btn-group .btn, .btn-group .btn-group, .btn-group .btn:active, .btn-group .btn:focus, .btn-group-vertical .btn, .btn-group-vertical .btn-group, .btn-group-vertical .btn:active, .btn-group-vertical .btn:focus, .form-control, input:active, input:focus {*/
        /*           box-shadow: none;*/
        /*       }*/
        /*       .form-control {*/
        /*           display: block;*/
        /*           width: 100%;*/
        /*           height: 34px;*/
        /*           padding: 6px 12px;*/
        /*           font-size: 14px;*/
        /*           line-height: 1.42857143;*/
        /*           color: #555;*/
        /*           background-color: #fff;*/
        /*           background-image: none;*/
        /*           border: 1px solid #ccc;*/
        /*           border-radius: 4px;*/
        /*           -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);*/
        /*           box-shadow: inset 0 1px 1px rgba(0,0,0,.075);*/
        /*           -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;*/
        /*           -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;*/
        /*           transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;*/
        /*       }*/
        /*       button, input, select, textarea {*/
        /*           font-family: inherit;*/
        /*           font-size: inherit;*/
        /*           line-height: inherit;*/
        /*       }*/
        /*       textarea {*/
        /*           overflow: auto;*/
        /*       }*/
        /*       button, input, optgroup, select, textarea {*/
        /*           margin: 0;*/
        /*           font: inherit;*/
        /*           font-size: inherit;*/
        /*           line-height: inherit;*/
        /*           font-family: inherit;*/
        /*           color: inherit;*/
        /*       }*/
        /*       * {*/
        /*           outline: none !important;*/
        /*       }*/
        /*       * {*/
        /*           -webkit-box-sizing: border-box;*/
        /*           -moz-box-sizing: border-box;*/
        /*           box-sizing: border-box;*/
        /*       }*/
    </style>
</head>
<body>

<div class="header">
    <h5>Page <span class="pagenum"></span></h5>
</div>
<div class="footer">

    <span style="text-align: left !important;font-weight: 600">Sales Order Prepared By : {{$quot->first_name}} {{$quot->last_name}}</span>

</div>
<?php
if($discsum==0)
{
    $totcol=12;
    $totdivide=6;
    $totdivide1=6;

}else{
    $totcol=11;
    $totdivide=6;
    $totdivide1=5;
}
?>
<table style="width: 100%">
    <tr>
        <!--<td colspan="1" style="border-right:none;vertical-align: top; padding: 15px;"></td>-->
        <td colspan="<?=$totcol?>" style="text-align: right;vertical-align: top;border-left:1px solid rgba(0,0,0,.1)">
            <table style="border: none;width:100%">
                <tr> <td style="border: none;width:38%;text-align: left;border-left:1px solid rgba(0,0,0,.1)">  <img src="{{asset('public/company_logo/'.$company->logo)}}" style="height: 135px"></td>
                    <td style="border: none;vertical-align: top;padding-left: 18px">
                        <h2 style="text-transform: uppercase; margin-bottom:3px;margin-top:8px;font-size: 20px;text-align: right;color: #0c0a96" >{{$company->company_name}}</h2>
                        <p style="margin:0px;line-height:15px;font-size:12px;text-align: right;" >
                            PLOT NO.:- 282/A1/8, NR.FLEXICAN BELLOWS<br>
                            GIDC INDUSTRIAL ESTATE &nbsp;MAKARPURA,<br>
                            VADODARA - 390 010,<br>
                            Mobile :- +91 9327936709, +91 9824344509, +91 6352581404,<br>
                            Email :- sales@yashhydraulic.com;
                            <?php
                            //						echo $company->address
                            ?></p>
                        <p style="margin:6px 0px;font-size:13px;font-weight: 800;margin-top:12px;text-align: right;"> <span>GSTIN :- {{$company->gst}}</span></p></td></tr>

            </table>

        </td>
    </tr>

    <tr>
        <td colspan="<?=$totcol?>" style="text-align: center;"><h4>Sales Order</h4></td>
    </tr>

    <tr>
        <td colspan="<?=$totdivide?>" style="border: none;vertical-align: top;">
            <table style="border: none;">
                <tr>
                    <td style="border: none;padding:3px"><b>To,<br><span style="font-size: 10px;margin-top: 5px">M/s. {{$quot->customer_name}}</span></b><br>{{strip_tags($quot->billing_address)}}<br>Mobile :- {{$quot->primary_phone}}, Email :- {{$quot->primary_email}}<br>GSTIN :- {{$quot->owner_gst}}</td>
                </tr>

            </table>
        </td>

        <td colspan="<?=$totdivide1;?>" style="vertical-align: top;font-size: 10px !important">
            <table style="border: none;padding:3px">
                <tr style="border: none;">
                    <td style="border: none;font-weight: bold">Sale Order No. </td>
                    <td style="border: none;">: {{$quot->salaesorder_no}}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;font-weight: bold">Sale Order Date </td>
                    <td style="border: none;">: {{date('d-m-Y',strtotime($quot->salaesorder_date))}}</td>
                </tr>
{{--                <tr style="border: none;">--}}
{{--                    <td style="border: none;font-weight: bold">Quotation Valid </td>--}}
{{--                    <td style="border: none;">: {{date('d-m-Y',strtotime($quot->due_date))}}</td>--}}
{{--                </tr>--}}
                <tr style="border: none;">
                    <td style="border: none;font-weight: bold">Contact Name</td>
                    <td style="border: none;">: {{$quot->contact_name}}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;font-weight: bold">Contact Mobile</td>
                    <td style="border: none;">: {{$quot->primary_phone}}</td>
                </tr>
            </table>
        </td>
    </tr>


    <tr>
        <td colspan="<?=$totcol?>"><b>Sub :- </b>{{$quot->subject}}<br></td>
    </tr>
    <thead>
    <tr>
        <th style="text-align: center;vertical-align:top;">SR.</th>
        <th style="text-align: center;vertical-align:top;">Product Specification</th>

        <th style="text-align: center;vertical-align:top;">ID</th>
        <th style="text-align: center;vertical-align:top;">OD</th>
        <th style="text-align: center;vertical-align:top;">THK</th>
        <th style="text-align: center;vertical-align:top;">Material</th>
        <th style="text-align: center;vertical-align:top;">HSN</th>

        {{--        <th style="text-align: center;vertical-align:top;">Image</th>--}}

        <th style="text-align: center;vertical-align:top;">Qty</th>
        <th style="text-align: center;vertical-align:top;">UOM</th>
        <th style="text-align: center;vertical-align:top;">Price</th>

        @if($discsum==0)
        @else
            <th style="text-align: center;vertical-align:top;">Disc<br>(%)</th>
        @endif
{{--        <th style="text-align: center;vertical-align:top;">Tax<br>(%)</th>--}}

        <th style="text-align: center;vertical-align:top;">Sub Total</th>
        <!-- <th style="text-align: center;vertical-align:top;">Total</th> -->
    </tr>
    </thead>

    <tbody>

    <?php
    $srno=$total=$gsttotal=$grand=$discount=0;
    ?>
    @foreach($quotitem as $item)
        <?php
        $srno++;
        ?>
        <tr>
            <td style="text-align: center;vertical-align:top;">{{$srno}}</td>
            <td style="text-align: left;vertical-align:top;width:30%">{{$item->product_name}}
                <br><span style="font-size: 8px">{{$item->description}}</span>
            </td>
            <td style="text-align: center;vertical-align:top;">{{$item->inner_diameter}}</td>
            <td style="text-align: center;vertical-align:top;">{{$item->outer_diameter}}</td>
            <td style="text-align: center;vertical-align:top;">{{$item->thikness}}</td>
            <td style="text-align: center;vertical-align:top;">{{$item->material_name}}</td>
            <td style="text-align: center;vertical-align:top;">{{$item->hsn}}</td>

            {{--            <td style="text-align: center;vertical-align:top;">--}}
            {{--                @if($item->catname=="AS PER DRAWING")--}}
            {{--                    <img src="{{asset('public/product_image/'.$item->product_image)}}" style="height: 55px" width="55px">--}}
            {{--                @endif--}}
            {{--                @if(empty($item->category_image))--}}
            {{--                @else--}}
            {{--                    <img src="{{asset('public/product_category/'.$item->category_image)}}" style="height: 55px" width="55px">--}}
            {{--                @endif--}}
            {{--            </td>--}}
            <td style="text-align: center;vertical-align:top;">{{$item->qty}}</td>
            <td style="text-align: center;vertical-align:top;">{{$item->uom_name}}</td>
            <td style="text-align: right;vertical-align:top;">{{number_format($item->price,2,'.',',')}}</td>

            @if($discsum==0)
            @else
                <td style="text-align: right;vertical-align:top;">{{$item->discount_per}}</td>
            @endif
{{--            <td style="text-align: right;vertical-align:top;">{{$item->gst_per}}</td>--}}
            <td style="text-align: right;vertical-align:top;">{{number_format($item->total,2,'.',',')}}</td>
        <!-- <td style="text-align: right;vertical-align:top;">{{$item->grand_total}}</td> -->


            <?php
            $total=round($total+$item->amount);
            $discount=round($discount+$item->discount_amount);
            $gsttotal=round($gsttotal+$item->gst_amount);
            $grand=round($grand+$item->grand_total);
            ?>
        </tr>
    @endforeach
    </tbody>
</table>

<table style="width: 100%">
    <tr>
        <!-- <td colspan="6" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
        <td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>SUB TOTAL (+)</b></td>
        <td colspan="1" style="text-align: right;"><b><?=number_format($quot->net_amount,2,'.',',');?></b></td>
    </tr>
    @if($discsum==0)
    @else
        <tr>
            <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
            <td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>DISCOUNT TOTAL (-)</b></td>
            <td colspan="1" style="text-align: right;"><b><?=number_format($discount,2,'.',',');?></b></td>
        </tr>
    @endif
{{--    <tr>--}}
{{--        <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->--}}
{{--        <td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>GST TOTAL (+)</b></td>--}}
{{--        <td colspan="1" style="text-align: right;"><b><?=number_format($gsttotal,2,'.',',');?></b></td>--}}
{{--    </tr>--}}

    <tr>
        <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
        <td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>ADJUSTMENT (+)</b></td>
        <td colspan="1" style="text-align: right;"><b><?=number_format($quot->adjustment,2,'.',',');?></b></td>
    </tr>

    <tr>
        <td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>GRAND TOTAL (+)</b></td>
        <td colspan="1" style="text-align: right;"><b><?=number_format($quot->grand_total,2,'.',',');?></b></td>
    </tr>


    <tr>
        <td colspan="<?=$totcol;?>" style="text-align:left;">
            <?php
            $no = round($grand);
            $decimal = round($grand - ($no = floor($grand)), 2) * 100;
            $digits_length = strlen($no);
            $i = 0;
            $str = array();
            $words = array(
                0 => '',
                1 => 'One',
                2 => 'Two',
                3 => 'Three',
                4 => 'Four',
                5 => 'Five',
                6 => 'Six',
                7 => 'Seven',
                8 => 'Eight',
                9 => 'Nine',
                10 => 'Ten',
                11 => 'Eleven',
                12 => 'Twelve',
                13 => 'Thirteen',
                14 => 'Fourteen',
                15 => 'Fifteen',
                16 => 'Sixteen',
                17 => 'Seventeen',
                18 => 'Eighteen',
                19 => 'Nineteen',
                20 => 'Twenty',
                30 => 'Thirty',
                40 => 'Forty',
                50 => 'Fifty',
                60 => 'Sixty',
                70 => 'Seventy',
                80 => 'Eighty',
                90 => 'Ninety');
            $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
            while ($i < $digits_length) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                    $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
                } else {
                    $str [] = null;
                }
            }

            $Rupees = implode(' ', array_reverse($str));
            $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
            echo ($Rupees ? '<span style="font-weight:800">In Words :- Rs. ' . $Rupees : '') . $paise . " Only.</span>";



            ?>
        </td>
    </tr>
    <tr>
        <td colspan="<?=$totcol?>">
            <table style="width: 100%;border: none !important;">
                <tr> <th colspan="2" style="text-align: left !important;margin-bottom: 6px !important;margin-top: 6px !important;border: none !important;">Bank Details</th></tr>
                <tr><td style="border: none !important;">Bank Name </td><td style="border: none !important;">: {{$company->bank_name}}</td><td style="border: none !important;">Account Number</td><td style="border: none !important;">: {{$company->account_no}}</td></tr>
                <tr><td style="border: none !important;">Bank Branch </td><td style="border: none !important;">: {{$company->bank_branch}}</td><td style="border: none !important;">IFSC Code </td><td style="border: none !important;">: {{$company->ifsc_code}}</td></tr>

                <tr><td style="border: none !important;">MICR Code </td><td style="border: none !important;">: {{$company->micr_code}}</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="<?=$totdivide1;?>" style='vertical-align: top;'>
            <table style="border: none;">
                <tbody>
                <tr style="border: none;">
                    <th style="border: none;vertical-align: top;">Terms & Conditions :</th>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">
                        <?php
                        echo $quot->term_condition;
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td colspan="<?=$totdivide;?>" style='vertical-align: top;'>
            <table style="border: none;">
                <tbody>
                <tr style="border: none;vertical-align: top"><th style="border: none; text-align: right;font-size: 12px;vertical-align: top;font-weight:1000">FOR {{$company->company_name}}</th> </tr>
                <tr style="border: none;"><td style="border: none; color: #fff">0</td> </tr>
                <tr style="border: none;"><td style="border: none; color: #fff">0</td></tr>
                <tr style="border: none;"><td style="border: none; color: #fff">0</td></tr>
                <tr style="border: none;"><th style="border: none; text-align: right;font-size: 12px">AUTHORISED SIGNATORY</th></tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
