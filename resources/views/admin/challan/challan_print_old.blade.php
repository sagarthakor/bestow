<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{$quot->quotation_no}}_{{$quot->customer_name}}</title>
    <style>
        @page {
            margin: 10px 20px;
        }

        .header {
            position: fixed;
            left: 0px;
            top: -80px;
            right: 0px;
            height: 0px;
            text-align: center;
        }

        .footer {
            position: fixed;
            left: 0px;
            bottom: -50px;
            right: 0px;
            height: 50px;
        }

        .header .pagenum:before {
            content: counter(page);
        }

        table {
            width: 100%;
            page-break-inside: auto;
            border-collapse: collapse;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 2px;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-size: 10px;
            padding: 0px;
            margin: 0px;
        }
    </style>
</head>
<body>

<div class="header">
    <h5>Page <span class="pagenum"></span></h5>
</div>

<div class="footer">
    <span style="text-align: center !important;font-weight: 600">
        DC. Prepared By : {{$quot->first_name}} {{$quot->last_name}}
    </span>
</div>

<?php
if ($discsum == 0) {
    $totcol = 8;
} else {
    $totcol = 10;
}
?>

    <!-- Main Table -->
<table style="border: none;">
    <tr>
        <td style="border: none;">
            <!-- Company & Customer Info Table -->
            <table style="border: none;">
                <tr>
                    <td colspan="{{$totcol}}" style="text-align: right; border: none; border-bottom: 1px solid #000">
                        <table style="border: none; width: 100%;">
                            <tr>
                                <td style="border: none; width: 60%; text-align: left;">
                                    <img src="{{public_path('/company_logo/'.$company->logo)}}">
                                </td>
                                <td style="border: none;">
                                    <h2 style="text-transform: uppercase; margin: 5px 0; font-size: 18px; color: #0c0a96">{{$company->company_name}}</h2>
                                    <p style="margin: 0px; line-height: 14px;">{{$company->address}}<br>
                                        {{$company->address1}}<br>{{$company->address2}}<br>
                                        Phone: {{$company->phone}}, {{$company->mobile}}<br>
                                        Email: {{$company->email}}</p>
                                    <p style="margin-top: 5px; font-weight: bold;">MSME: {{$company->msme_no}}
                                        <br>GSTIN: {{$company->gst}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="{{$totcol}}"
                        style="text-align: center; font-weight: bold; font-size: 14px; border: none;">Delivery Challan
                    </td>
                </tr>

                <!-- Customer Address and Challan Info -->
                <tr>
                    <td colspan="{{$totcol}}" style="border: none;">
                        <table style="border: none; width: 100%;">
                            <tr>
                                <td style="width: 50%; vertical-align: top; border: none;">
                                    <b>Customer Address:</b><br>
                                    {{$quot->customer_name}}<br>
                                    {{strip_tags($quot->billing_address)}}<br>
                                    Mobile: {{$quot->primary_phone}}<br>
                                    Email: {{$quot->primary_email}}<br>
                                    GSTIN: {{$quot->owner_gst}}
                                </td>
                                <td style="width: 50%; border: none;">
                                    <table style="width: 100%; border: 1px solid #000;">
                                        <tr>
                                            <th>Delivery Challan</th>
                                            <th>Date</th>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">{{$quot->challan_number}}</td>
                                            <td style="text-align: center;">{{date('d-m-Y',strtotime($quot->invoice_date))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Valid From</th>
                                            <th>Valid To</th>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">{{date('d-m-Y',strtotime($quot->invoice_date))}}</td>
                                            <td style="text-align: center;">{{date('d-m-Y',strtotime($quot->invoice_duedate))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <th>Currency</th>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">{{number_format($quot->grand_total,2,'.',',')}}</td>
                                            <td style="text-align: center;">INR</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @if($quot->tax_preference=="false")
                    <tr>
                        <td colspan="{{$totcol}}">
                            <b>(SUPPLY MEANT FOR EXPORT/SUPPLY TO SEZ UNIT OR SEZ DEVELOPER FOR AUTHORISED OPERATIONS
                                UNDER BOND OR LETTER OF UNDERTAKING WITHOUT PAYMENT OF IGST)</b>
                        </td>
                    </tr>
                @endif
            </table>
            <!-- Product Listing -->
            <table style="margin-top: 10px;">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name / HSN</th>
                    <th>Quantity / UOM</th>
                    <th>Unit Price</th>
                    @if($discsum != 0)
                        <th>Disc (%)</th>
                        <th>Disc Amt</th>
                    @endif
                    @if($quot->tax_preference == true)
                        <th>GST %</th>
                        @if($company->state != $state->id)
                            <th>IGST</th>
                        @else
                            <th>SGST</th>
                            <th>CGST</th>
                        @endif
                    @endif
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                <?php $srno = 0; ?>
                @foreach($quotitem as $item)
                        <?php $srno++; ?>
                    @php
                        $itemVariant = trim(($item->value1 ?? '') . ((($item->value1 ?? '') !== '' && ($item->value2 ?? '') !== '') ? ' / ' : '') . ($item->value2 ?? ''));
                        $itemVariantLabel = $itemVariant !== '' ? ' ('.$itemVariant.')' : '';
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{$srno}}</td>
                        <td>{{$item->product_name}}{{$itemVariantLabel}}<br>HSN: {{$item->hsn}}</td>
                        <td style="text-align: center;">{{$item->qty}} {{$item->uom_name}}</td>
                        <td style="text-align: center;">{{number_format($item->price,2,'.',',')}}</td>
                        @if($discsum != 0)
                            <td style="text-align: center;">{{$item->discount_per}}</td>
                            <td style="text-align: center;">{{$item->discount_amount}}</td>
                        @endif
                        @if($quot->tax_preference == true)
                            <td style="text-align: center;">{{$item->gst_per + $item->cgst_per + $item->sgst_per}}</td>
                            @if($company->state != $state->id)
                                <td style="text-align: center;">{{number_format($item->gst_amount,2,'.',',')}}</td>
                            @else
                                <td style="text-align: center;">{{number_format($item->cgst_amount,2,'.',',')}}</td>
                                <td style="text-align: center;">{{number_format($item->sgst_amount,2,'.',',')}}</td>
                            @endif
                        @endif
                        <td style="text-align: center;">{{number_format($item->total_amount,2,'.',',')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <table style="margin-top: 10px;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <b>Bank Details</b><br>
                        @if($company->bank_name)
                            Bank: {{$company->bank_name}}<br>
                        @endif
                        @if($company->bank_branch)
                            Branch: {{$company->bank_branch}}<br>
                        @endif
                        @if($company->account_no)
                            A/C No: {{$company->account_no}}<br>
                        @endif
                        @if($company->account_type)
                            A/C Type: {{$company->account_type}}<br>
                        @endif
                        @if($company->ifsc_code)
                            IFSC: {{$company->ifsc_code}}<br>
                        @endif
                        @if($company->micr_code)
                            MICR: {{$company->micr_code}}<br>
                        @endif
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <table style="width: 100%;">
                            <tr>
                                <td>SUB TOTAL</td>
                                <td style="text-align:right;">{{number_format($quot->item_total,2,'.',',')}}</td>
                            </tr>
                            @if($discsum != 0)
                                <tr>
                                    <td>DISCOUNT TOTAL</td>
                                    <td style="text-align:right;">{{number_format($quot->discount_total,2,'.',',')}}</td>
                                </tr>
                            @endif
                            @if($quot->cgsttotal > 0)
                                <tr>
                                    <td>CGST</td>
                                    <td style="text-align:right;">{{number_format($quot->cgsttotal,2,'.',',')}}</td>
                                </tr>
                                <tr>
                                    <td>SGST</td>
                                    <td style="text-align:right;">{{number_format($quot->sgsttotal,2,'.',',')}}</td>
                                </tr>
                            @endif
                            @if($quot->igsttotal > 0)
                                <tr>
                                    <td>IGST</td>
                                    <td style="text-align:right;">{{number_format($quot->igsttotal,2,'.',',')}}</td>
                                </tr>
                            @endif
                            @if($quot->adjustment != "")
                                <tr>
                                    <td>ADJUSTMENT</td>
                                    <td style="text-align:right;">{{number_format($quot->adjustment,2,'.',',')}}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><b>GRAND TOTAL</b></td>
                                <td style="text-align:right;"><b>{{number_format($quot->grand_total,2,'.',',')}}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Terms -->
            <br>
            <u>Terms & Conditions</u>
            <div>
                {!! $quot->term_condition !!}
            </div>
        </td>
    </tr>
</table>

</body>
</html>
