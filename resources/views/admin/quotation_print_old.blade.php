<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $quot->quotation_no }}_{{ $quot->customer_name }}</title>
    <style>
        @page {
            margin: 10px 50px; /* reduced top/bottom from 100px to 10px */
        }

        body {
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            font-weight: 600;
            margin: 5px 0; /* reduced header/footer margin */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: top;
        }

        .no-border {
            border: none !important;
        }

        .no-padding {
            padding: 0 !important;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 0;
        }

        .section-title {
            background-color: #ddd;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .notice {
            padding: 5px;
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <h3>{{ $company->company_name }}</h3>
</div>

{{-- Footer --}}
<div class="footer">
    Quote Prepared By: {{ $quot->created_user_name }}
</div>

{{-- Main Content --}}
@php
    $totcol = ($discsum == 0) ? 9 : 11;
    $srno = $total = $gsttotal = $grand = $discount = 0;
@endphp

{{-- Company + Customer Information --}}
<table class="no-border">
    <tr class="no-border">
        <td class="no-border">
            @if(isset($preiview))
                <img src="/company_logo/{{$company->logo }}" height="80"><br>
            @else
                <img src="{{ public_path('/company_logo/' . $company->logo) }}" height="80"><br>
            @endif
            <strong>{{ $company->company_name }}</strong><br>
            {{ $company->address }}<br>
            {{ $company->address1 }}<br>
            {{ $company->address2 }}<br>
            Phone: {{ $company->phone }}, {{ $company->mobile }}<br>
            Email: {{ $company->email }}<br>
            @if ($company->msme_no)
                MSME: {{ $company->msme_no }}<br>
            @endif
            @if ($company->gst)
                GSTIN: {{ $company->gst }}<br>
            @endif
        </td>
        <td class="no-border text-right">
            <strong>Quotation / Proforma Invoice</strong><br><br>
            <table>
                <tr>
                    <td>Quote:</td>
                    <td>{{ $quot->quotation_no }}</td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td>{{ date('d-m-Y', strtotime($quot->quot_date)) }}</td>
                </tr>
                <tr>
                    <td>Valid Until:</td>
                    <td>{{ date('d-m-Y', strtotime($quot->valid_until)) }}</td>
                </tr>
                <tr>
                    <td>Payment Terms:</td>
                    <td>Net {{ $quot->payment_terms }} Days</td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td>{{ number_format($quot->grand_total, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Addresses --}}
<table class="no-border">
    <tr>
        <td class="no-border">
            <div class="section-title">Billing Address</div>
            {{ $quot->customer_name }}<br>
            {!! $quot->billing_address !!}<br>
            Phone: {{ $quot->primary_phone }}<br>
            Email: {{ $quot->primary_email }}<br>
            GSTIN: {{ $quot->owner_gst }}
        </td>
        <td class="no-border">
            <div class="section-title">Shipping Address</div>
            {{ $quot->customer_name }}<br>
            {!! $quot->shipping_address !!}
        </td>
    </tr>
</table>

{{-- Tax Note --}}
@if ($quot->tax_preference === "false")
    <p><strong>(SUPPLY MEANT FOR EXPORT/SUPPLY TO SEZ UNIT... WITHOUT PAYMENT OF IGST)</strong></p>
@endif

<p><strong>Subject:</strong> {{ $quot->subject }}</p>

{{-- Quotation Items Table --}}
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Description / HSN</th>
        @if(isset($isInternalPrint) && $isInternalPrint == 'yes')
            <td>Photo</td>
        @endif
        <th>Qty / UOM</th>
        <th>Unit Price</th>
        @if ($discsum != 0)
            <th>Disc (%)</th>
            <th>Disc Amt</th>
        @endif
        @if($quot->tax_preference == true)
            <th>GST Rate</th>
            @if($company->state != $stateId)
                <th>IGST</th>
            @else
                <th>SGST</th>
                <th>CGST</th>
            @endif
        @endif

        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($quotitem as $item)
        @php
            $srno++;
            $total += $item->amount;
            $discount += $item->discount_amount;
            $gsttotal += $item->gst_amount;
            $grand += $item->grand_total;
        @endphp
        <tr>
            <td class="text-center">{{ $srno }}</td>
            <td>{{ $item->product_name }}<br>HSN: {{ $item->hsn }}</td>
            @if(isset($isInternalPrint) && $isInternalPrint == 'yes')
                <td class="text-center">
                    @if($item->product_image)
                        <img height="60px" src="{{public_path('/product_image/'.$item->product_image)}}">
                    @endif
                </td>
            @endif
            <td class="text-center">{{ $item->qty }} {{ $item->uom_name }}</td>
            <td class="text-right">{{ number_format($item->price, 2) }}</td>
            @if ($discsum != 0)
                <td class="text-right">{{ $item->discount_per }}</td>
                <td class="text-right">{{ number_format($item->discount_amount, 2) }}</td>
            @endif
            @if($quot->tax_preference == true)
                <td class="text-right">{{ $item->gst_per + $item->cgst_per + $item->sgst_per }}%</td>
                @if($company->state != $stateId)
                    <td class="text-right">{{ number_format($item->gst_amount, 2) }}</td>
                @else
                    <td class="text-right">{{ number_format($item->cgst_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($item->sgst_amount, 2) }}</td>
                @endif
            @endif
            <td class="text-right">{{ number_format($item->amount, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- Totals --}}

<table class="no-border">
    <tr>
        <td class="no-border" style="width: 60%">
            @if(!empty($company->bank_name))
                <strong>Bank Details</strong><br>
                @if($company->bank_name)
                    Bank: {{ $company->bank_name }}<br>
                @endif
                @if($company->bank_branch)
                    Branch: {{ $company->bank_branch }}<br>
                @endif
                @if($company->account_no)
                    A/C No: {{ $company->account_no }}<br>
                @endif
                @if($company->account_type)
                    A/C Type: {{ $company->account_type }}<br>
                @endif
                @if($company->ifsc_code)
                    IFSC: {{ $company->ifsc_code }}<br>
                @endif
                @if($company->micr_code)
                    MICR: {{ $company->micr_code }}<br>
                @endif
            @endif
        </td>
        <td class="no-border" style="width: 40%">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">{{ number_format($quot->net_amount, 2) }}</td>
                </tr>
                @if($discsum != 0)
                    <tr>
                        <td>Discount:</td>
                        <td class="text-right">{{ number_format($quot->discount_total, 2) }}</td>
                    </tr>
                @endif
                @if($quot->tax_preference == true)

                    @if($company->state != $stateId)
                        <tr>
                            <td>IGST:</td>
                            <td class="text-right">{{ number_format($quot->gst_amount, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>CGST:</td>
                            <td class="text-right">{{ number_format($quot->cgsttotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td>SGST:</td>
                            <td class="text-right">{{ number_format($quot->sgsttotal, 2) }}</td>
                        </tr>
                    @endif
                @endif
                @if($quot->adjustment != 0)
                    <tr>
                        <td>Adjustment:</td>
                        <td class="text-right">{{ number_format($quot->adjustment, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td><strong>Grand Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($quot->grand_total, 2) }}</strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>


{{-- Terms --}}
<h4>Terms & Conditions</h4>
<div class="notice">
    {!! $quot->term_condition !!}
</div>

{{-- Signature --}}
<div class="text-right">
    <strong>For, {{ $company->company_name }}</strong><br><br>
    @if($company->signature_image)
        <img src="{{ public_path('/company_logo/' . $company->signature_image) }}" height="50"><br>
    @endif
    <strong>Authorized Signatory</strong>
</div>
</body>
</html>
