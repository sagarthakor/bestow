<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{$quot->quotation_no}}_{{$quot->customer_name}}</title>
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
            padding: 2px;
        }
        table, th, td {
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 2px;
        }

        body{
            font-family:"Poppins", sans-serif;
            font-size: 12px;
            padding: 0px;
            margin:0px;
        }
    </style>

</head>
<body>

<div class="header">
    <h5>Page <span class="pagenum"></span></h5>
</div>
<div class="footer">

   <span style="text-align: center !important;font-weight: 600">
        <!--<img src="{{asset('public/footer/footer.jpg')}}" style="height:50px">-->
    Invoice Prepared By : {{$quot->first_name}} {{$quot->last_name}}
    </span>

</div>
<?php
if($discsum==0)
{
    $totcol=9;
    $totdivide=5;
    $totdivide1=4;

    $td=4;
    $td1=1;
    $td2=4;

}else{
    $totcol=11;
    $totdivide=6;
    $totdivide1=5;
}
?>
<table style="width: 100%;border: none;">
    <tr>
        <td style="border: none;">
            <table style="width: 100%;border: none">
                <tr>
                    <!--<td colspan="1" style="border-right:none;vertical-align: top; padding: 15px;"></td>-->
                    <td colspan="<?=$totcol?>" style="text-align: right;vertical-align: top;border: none;border-bottom: 1px solid #000">
                        <table style="border: none;width:100%">
                            <tr> 
                                <td style="border: none;width:60%;text-align: left;">
                                    <img src="{{asset('public/company_logo/'.$company->logo)}}" style="">
                                   
                                </td>
                                <td style="border: none;vertical-align: top;">
                                        <h2 style="text-transform: uppercase; margin-bottom:3px;margin-top:8px;font-size: 20px;text-align: left;color: #0c0a96" >{{$company->company_name}}</h2>
                                        <p style="margin:0px;line-height:15px;font-size:12px;text-align: left;" >{{$company->address}}<br>
                                          {{$company->address1}}<br>{{$company->address2}}<br>Phone: {{$company->phone}} , {{$company->mobile}}<br>Email: {{$company->email}}</p>
                                          <p style="margin-top:5px;line-height:15px;font-size:12px;text-align: left;font-weight:800" >MSME : {{$company->msme_no}} <br>GSTIN : {{$company->gst}}</p>
                                          
                                         
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>
                <tr>
                    <td colspan="<?=$totcol?>" style="text-align: right;border:none;font-size: 14px;color: blue">{{$company->email}}</td>
                </tr>
                <tr>
                    <td colspan="<?=$totcol?>" style="text-align: center;border:none;font-size: 16px;font-weight: 800">GST TAX INVOICE</td>
                </tr>
                <tr>
                    <td colspan="<?=$totcol?>" style="text-align: center;border:none">
                        <table style="border: none;">
                            <tr>
                                <td style="vertical-align: top;width: 40%;border: none;padding: 5px">
                                    <table style="border: none">
                                        <tr>
                                            <td style="padding: 5px;vertical-align: top;background: #ddd;border: none">Customer Address</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: top;border: none;padding-left: 5px">{{$quot->customer_name}}<br>
                                                {{strip_tags($quot->billing_address)}}<br>Mobile :- {{$quot->primary_phone}}, Email :- {{$quot->primary_email}}<br>GSTIN :- {{$quot->owner_gst}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 10%;border: none"></td>
                                <td style="width: 50%;vertical-align: top;border: none">
                                    <table style="border: none;padding:3px">
                                        <tr style="border: 2px solid #ddd;">
                                            <td style="border:2px solid #ddd;text-align: center;background: #ddd;padding: 3px">Invoice
                                            </td>
                                            <td style="border:2px solid #ddd;text-align: center;background: #ddd;padding: 3px">Invoice Date </td>
                                        </tr>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="border:2px solid #ddd;text-align: center;">{{$quot->invoice_number}} </td>
                                            <td style="border:2px solid #ddd;text-align: center;">{{date('d-m-Y',strtotime($quot->invoice_date))}}</td>
                                        </tr>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="text-align: center;border:2px solid #ddd;background: #ddd;padding:3px">Valid From </td>
                                            <td style="text-align: center;border:2px solid #ddd;background: #ddd;padding: 3px">Valid To </td>
                                        </tr>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="text-align: center;border:2px solid #ddd;">{{date('d-m-Y',strtotime($quot->invoice_date))}}</td>
                                            <td style="text-align: center;border:2px solid #ddd;">{{date('d-m-Y',strtotime($quot->invoice_duedate))}}</td>
                                        </tr>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="text-align: center;border:2px solid #ddd;background: #ddd;padding: 3px">Total</td>
                                            <td style="text-align: center;border:2px solid #ddd;background: #ddd;padding: 3px">Currency</td>
                                        </tr>
                                        <tr style="border: none;">
                                            <td style="text-align: center;border:2px solid #ddd;"><?=number_format($quot->grand_total,2,'.',',');?></td>
                                            <td style="text-align: center;border:2px solid #ddd;">INR</td>
                                        </tr>
                                    </table></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="<?=$totcol?>" style="text-align: center;border:none">
                        <br>
                        <table style="border: none;">
                            <td style="width: 40%;border: none">
                                <table style="border: none">
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;background: #ddd;border: none">Customer Contact</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;border: none">{{$quot->contactname}}<br>{{$quot->primary_phone}}<br>{{$quot->primary_email}}</td>
                                    </tr>
                                 
                                </table>
                            </td>
                            <td style="width: 10%;border: none"> <br></td>
                            <td style="width: 50%;border: none">

                                <table style="border: none">
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;background: #ddd;border: none">Your Contact</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;border: none">{{$company->company_name}}<br>{{$company->email}}<br>{!! $company->mobile !!}</td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="<?=$totcol?>" style="text-align: center;border:none">

                        <table style="border: none">
                            <td style="width: 40%;border: none">
                                <table style="border: none">
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;background: #ddd;border: none">Billing Address</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;border: none">{{$quot->customer_name}}<br>{{$quot->billing_address}}<br>{{$quot->billing_state}}, {{$quot->billing_country}}<br>{{$quot->billing_city}}, {{$quot->                                    billing_postalcode}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 10%;border: none"></td>
                            <td style="width: 50%;border: none">
                                <table style="border: none">
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;background: #ddd;border: none">Shipping Address</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;border: none">{{$quot->customer_name}}<br>{{$quot->shipping_address}}<br>{{$quot->shipping_state}}, {{$quot->shipping_country}}<br>{{$quot->shipping_city}}, {{$quot->shipping_postalcode}}</td>
                                    </tr>
                                </table>
                            </td>
                        </table>

                </tr>

                @if($quot->tax_preference=="false")

                    <tr>
                        <td colspan="<?=$totcol?>"><b>
                                (SUPPLY MEANT FOR EXPORT/SUPPLY TO SEZ UNIT OR SEZ DEVELOPER FOR AUTHORISED OPERATIONS UNDER
                                BOND OR LETTER OF UNDERTAKING WITHOUT PAYMENT OF IGST)</b>
                        </td>
                    </tr>
                @endif
                {{--    <tr>--}}
                {{--        <td colspan="<?=$totcol?>"><b>Sub :- </b>{{$quot->subject}}<br></td>--}}
                {{--    </tr>--}}
            </table>
            <table style="width: 100%;padding:2px;border: 1px solid #ddd">
                <thead>
                <tr>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">#</td>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">Material No / Description / HSN Code</td>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">Quantity / UOM</td>

                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">Unit Price</td>
                    @if($discsum==0)
                    @else
                        <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">Disc<br>(%)</td>
                        <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">Disc<br>Amt</td>
                    @endif
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">GST <Rate></Rate></td>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">IGST</td>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">SGST</td>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">CGST</td>
                    <td style="text-align: center;vertical-align:top;background: #ddd;border: 1px solid #ddd">Total Price</td>
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
                        <td style="border-left:1px solid #ddd;text-align: center;vertical-align:top;width: 5%;border: 1px solid #ddd">{{$srno}}</td>
                        <td style="vertical-align:top;width:30%;border: 1px solid #ddd">{{$item->item_code}} - <x-product-name :row="$item" print /><br>HSN Code: {{$item->hsn}}</td>
                        <td style="text-align: center;vertical-align:top;width: 10%;border: 1px solid #ddd">{{$item->qty}} {{$item->uom_name}}</td>
                        
                        <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{number_format($item->price,2,'.',',')}}</td>
                        @if($discsum==0)
                        @else
                            <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{$item->discount_per}}</td>
                            <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{$item->discount_amount}}</td>
                        @endif
                        <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{$item->gst_per+$item->cgst_per+$item->sgst_per}}</td>
                        <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{number_format($item->gst_amount,2,'.',',')}}</td>
                        <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{number_format($item->cgst_amount,2,'.',',')}}</td>
                        <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{number_format($item->sgst_amount,2,'.',',')}}</td>
                        <td style="text-align: center;vertical-align:top;border: 1px solid #ddd">{{number_format($item->total_amount,2,'.',',')}}</td>
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

            <table style="width: 100%" style="border:none;padding:5px !important;">
                <tr style="border: none">
                    <td style="width: 50%;border-top:1px solid #fff;border-bottom:1px solid #fff;border-left:1px solid #fff;border-right:1px solid #fff"></td>
                    <td style="padding:5px;width: 50%;border-top:1px solid #fff;border-bottom:1px solid #fff;border-left:1px solid #fff;border-right:1px solid #fff">
                        <table style="border:1px solid #ddd;padding:5px !important;">
                            <tr>
                                <!-- <td colspan="6" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
                                <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">SUB TOTAL (+)</td>
                                <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->item_total,2,'.',',');?></td>
                            </tr>
                            @if($discsum==0)
                            @else
                                <tr>
                                    <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
                                    <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">DISCOUNT TOTAL (-)</td>
                                    <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->discount_total,2,'.',',');?></td>
                                </tr>
                            @endif
                            @if($quot->cgsttotal > 0)
                                <tr>
                                    <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
                                    <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">CGST TOTAL (+)</td>
                                    <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->cgsttotal,2,'.',',');?></td>
                                </tr>
                                <tr>
                                    <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
                                    <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">SGST TOTAL (+)</td>
                                    <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->sgsttotal,2,'.',',');?></td>
                                </tr>
                            @endif
                            @if($quot->igsttotal > 0)
                                <tr>
                                    <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
                                    <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">IGST TOTAL (+)</td>
                                    <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->igsttotal,2,'.',',');?></td>
                                </tr>
                            @endif

                            @if($quot->adjustment !="")
                                <tr>
                                    <!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
                                    <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">ADJUSTMENT (+)</td>
                                    <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->adjustment,2,'.',',');?></td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="<?=$totcol-1;?>" style="text-align: left;border: none">GRAND TOTAL (+)</td>
                                <td colspan="1" style="text-align: right;border: none"><?=number_format($quot->grand_total,2,'.',',');?></td>
                            </tr>

                        </table>

                    </td>
                </tr>

            </table>

        </td>


    </tr>
    <tr>
        <td style="border: none;">
            <u>Terms & Conditions</u>
            <br>
            <div class="notices">

                <div class="notice">

                    <?php

                    echo $quot->term_condition;
                    ?>
                </div>
            </div>
            <br>
        </td>
    </tr>
  
</table>
</body>
</html>
