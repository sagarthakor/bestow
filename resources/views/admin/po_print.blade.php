<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{$quot->purchase_no}}_{{$quot->vendor_name}}</title>
    <style>
        @page {
            margin: 20px 30px;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 10px;
            line-height: 30px;
            border-bottom: 1px solid #ccc;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 10px;
            line-height: 30px;
            border-top: 1px solid #ccc;
        }

        .pagenum:before {
            content: counter(page);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        thead {
            display: table-header-group;
            background: #eee;
        }

        tfoot {
            display: table-footer-group;
        }

        .no-border {
            border: none !important;
        }

        .section-title {
            background: #ddd;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }

        .signature-box img {
            height: 60px;
            margin-bottom: 5px;
        }

        .bank-table td, .summary-table td {
            padding: 4px;
        }

        .terms {
            font-size: 11px;
            line-height: 1.4;
        }
    </style>
</head>
<body>

<div class="header">
    Page <span class="pagenum"></span>
</div>

<div class="footer">
    PO Prepared By: {{$quot->first_name}} {{$quot->last_name}}
</div>

@php
    $totcol = $discsum == 0 ? 9 : 11;
@endphp

<table class="no-border" style="margin-top: 35px;">
    <tr>
        <td class="no-border text-left" colspan="{{$totcol}}">
            <table class="no-border">
                <tr>
                    <td class="no-border" style="width: 60%">
                        <img src="{{public_path('/company_logo/'.$company->logo)}}" height="80px">
                        <h2 style="margin: 5px 0; font-size: 18px;">{{$company->company_name}}</h2>
                        <p style="margin:0">{{$company->address}}<br>{{$company->address1}}, {{$company->address2}}<br>Phone: {{$company->phone}}, {{$company->mobile}}<br>Email: {{$company->email}}</p>
                        <p style="margin:0"><strong>@if($company->msme_no) MSME: {{$company->msme_no}} <br>@endif @if($company->gst) GSTIN: {{$company->gst}} @endif</strong></p>
                    </td>
                    <td class="no-border text-right" style="width: 40%">
                        <h3 style="margin-bottom: 5px;">Purchase Order</h3>
                        <table style="border: 1px solid #000">
                            <tr><td>PO No</td><td>{{$quot->purchase_no}}</td></tr>
                            <tr><td>SO No</td><td>{{$quot->salesorder_no}}</td></tr>
                            <tr><td>PO Date</td><td>{{date('d-m-Y', strtotime($quot->po_date))}}</td></tr>
                            <tr><td>Valid Until</td><td>{{date('d-m-Y', strtotime($quot->due_date))}}</td></tr>
                            <tr><td>Payment Terms</td><td>Net {{$quot->payment_terms}} Days</td></tr>
                            <tr><td>Total</td><td>{{number_format($quot->grand_total,2)}}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Vendor and Shipping -->
    <tr>
        <td class="no-border" colspan="{{$totcol}}">
            <table class="no-border">
                <tr>
                    <td style="width: 50%" class="no-border">
                        <div class="section-title">Vendor</div>
                        <p>{{$quot->vendor_name}}<br>{!! strip_tags($quot->billing_address) !!}<br>Mobile: {{$quot->primary_phone}}<br>Email: {{$quot->primary_email}}<br>GSTIN: {{$quot->owner_gst}}</p>
                    </td>
                    <td style="width: 50%" class="no-border">
                        <div class="section-title">Shipping</div>
                        <p>{{$quot->customer_name}}<br>{!! $quot->shipping_address !!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Optional GST Note -->
    @if($quot->tax_preference=="false")
        <tr><td class="no-border" colspan="{{$totcol}}">
                <strong>(SUPPLY MEANT FOR EXPORT/SUPPLY TO SEZ UNIT... WITHOUT PAYMENT OF IGST)</strong>
            </td></tr>
    @endif

    <!-- Subject -->
    <tr><td class="no-border" colspan="{{$totcol}}"><b>Subject:</b> {{$quot->subject}}</td></tr>

    <!-- Item Table -->
    <tr>
        <td class="no-border" colspan="{{$totcol}}">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    {{--<th>Photo</th>--}}
                    <th>Qty / UOM</th>
                    <th>Unit Price</th>
                    @if($discsum != 0)
                        <th>Disc %</th>
                        <th>Disc Amt</th>
                    @endif
                    @if($vendor->tax_preference == 'true')
                        <th>GST %</th>
                        <th>IGST</th>
                        <th>SGST</th>
                        <th>CGST</th>
                    @endif
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $srno=1;
                @endphp
                @foreach($quotitem as $item)
                    <tr>
                        <td class="text-center">{{$srno++}}</td>
                        <td>{{$item->product_name}}<br>HSN: {{$item->hsn}}</td>
                        {{-- <td class="text-center">
                             @if($item->product_image)
                                 <img src="{{public_path('/product_image/'.$item->product_image)}}" height="40">
                             @endif
                         </td>--}}
                        <td class="text-center">{{$item->qty}} {{$item->uom_name}}</td>
                        <td class="text-center">{{number_format($item->price, 2)}}</td>
                        @if($discsum != 0)
                            <td class="text-center">{{$item->discount_per}}</td>
                            <td class="text-center">{{$item->discount_amount}}</td>
                        @endif
                        @if($vendor->tax_preference == 'true')
                            <td class="text-center">{{$item->gst_per + $item->cgst_per + $item->sgst_per}}%</td>
                            <td class="text-center">{{number_format($item->gst_amount,2)}}</td>
                            <td class="text-center">{{number_format($item->sgst_amount,2)}}</td>
                            <td class="text-center">{{number_format($item->cgst_amount,2)}}</td>
                        @endif
                        <td class="text-center">{{number_format($item->total,2)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>

    <!-- Summary -->
    <tr>
        <td class="no-border" colspan="{{$totcol}}">
            <table>
                @php
                    $colspan = $totcol - 1;
                @endphp
                <tr><td colspan="{{$colspan}}">Subtotal</td><td class="text-right">{{number_format($quot->net_amount,2)}}</td></tr>
                @if($discsum != 0)
                    <tr><td colspan="{{$colspan}}">Discount</td><td class="text-right">{{number_format($quot->discount_total,2)}}</td></tr>
                @endif
                @if($vendor->tax_preference == true)
                    @if($quot->cgsttotal > 0)
                        <tr><td colspan="{{$colspan}}">CGST</td><td class="text-right">{{number_format($quot->cgsttotal,2)}}</td></tr>
                        <tr><td colspan="{{$colspan}}">SGST</td><td class="text-right">{{number_format($quot->sgsttotal,2)}}</td></tr>
                    @endif
                    @if($quot->gst_amount > 0)
                        <tr><td colspan="{{$colspan}}">IGST</td><td class="text-right">{{number_format($quot->gst_amount,2)}}</td></tr>
                    @endif
                @endif
                @if($quot->adjustment != 0)
                    <tr><td colspan="{{$colspan}}">Adjustment</td><td class="text-right">{{number_format($quot->adjustment,2)}}</td></tr>
                @endif
                <tr><td colspan="{{$colspan}}"><strong>Grand Total</strong></td><td class="text-right"><strong>{{number_format($quot->grand_total,2)}}</strong></td></tr>
            </table>
        </td>
    </tr>

    <!-- Bank Details -->
    @if($company->bank_name)
        <tr>
            <td class="no-border" colspan="{{$totcol}}">
                <h4>Bank Details</h4>
                <table class="bank-table">
                    <tr><td>Bank Name:</td><td>{{$company->bank_name}}</td></tr>
                    <tr><td>Branch:</td><td>{{$company->bank_branch}}</td></tr>
                    <tr><td>Account No:</td><td>{{$company->account_no}}</td></tr>
                    <tr><td>Account Name:</td><td>{{$company->account_type}}</td></tr>
                    <tr><td>IFSC:</td><td>{{$company->ifsc_code}}</td></tr>
                    @if($company->micr_code)
                        <tr><td>MICR:</td><td>{{$company->micr_code}}</td></tr>
                    @endif
                </table>
            </td>
        </tr>
    @endif

    <!-- Terms -->
    <tr>
        <td class="no-border" colspan="{{$totcol}}">
            <strong>Terms & Conditions</strong>
            <div class="terms">
                {!! $quot->term_condition !!}
            </div>
        </td>
    </tr>

    <!-- Signature -->
    <tr>
        <td class="no-border text-right" colspan="{{$totcol}}">
            @if($company->signature_image)
                <h4>For, {{$company->company_name}}</h4>
                <img src="{{public_path('/company_logo/'.$company->signature_image)}}" height="50"><br>
                <strong>Authorized Signature</strong>
            @endif
        </td>
    </tr>
</table>

</body>
</html>
