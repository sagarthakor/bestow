<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{$quot->quotation_no}}_{{$quot->customer_name}}</title>
    <style>
        @page {
            margin: 20px 30px;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;
            top: -15px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .header .pagenum:before {
            content: counter(page);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        thead {
            background-color: #f0f0f0;
            display: table-header-group;
        }

        .section-title {
            background: #ddd;
            font-weight: bold;
            padding: 5px;
        }

        .no-border {
            border: none !important;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: 800;
            text-align: center;
            margin-top: 5px;
            color: #0c0a96;
        }

        .address-table td {
            border: none;
            padding: 4px;
            vertical-align: top;
        }

        .totals-table td {
            border: none;
            padding: 4px;
        }

        .bank-details td {
            border: none;
            padding: 2px;
        }

        .notices {
            margin-top: 10px;
            font-size: 11px;
        }
    </style>
</head>
<body>

<div class="header">
    <h5>Page <span class="pagenum"></span></h5>
</div>

@php
    $cgst_amount = collect($quotitem)->sum('cgst_amount');
    $sgst_amount = collect($quotitem)->sum('sgst_amount');
    $gst_amount = collect($quotitem)->sum('gst_amount');
    $grand_total = $quot->grand_total + $cgst_amount + $sgst_amount + $gst_amount + $quot->adjustment;

    $totcol = ($discsum == 0) ? 9 : 11;
@endphp

    <!-- Header -->
<table class="no-border" style="margin-bottom: 10px;">
    <tr class="no-border">
        <td class="no-border" style="width: 60%;">
            <img src="{{public_path('/company_logo/'.$company->logo)}}" height="80">
        </td>
        <td class="no-border" style="text-align: left;">
            <h2 class="invoice-title">{{$company->company_name}}</h2>
            <p style="line-height: 1.4;">{{$company->address}}<br>{{$company->address1}}<br>{{$company->address2}}<br>
                Phone: {{$company->phone}}, {{$company->mobile}}<br>Email: {{$company->email}}<br>
                MSME: {{$company->msme_no}} | GSTIN: {{$company->gst}}</p>
        </td>
    </tr>
</table>

<div class="invoice-title">TAX INVOICE</div>

<!-- Customer & Invoice Info -->
<table class="no-border" style="margin-top: 5px;">
    <tr>
        <td class="no-border" style="width: 45%;">
            <div class="section-title">Customer Address</div>
            <p>{{$quot->customer_name}}<br>
                {!! nl2br(strip_tags($quot->billing_address)) !!}<br>
                Mobile: {{$quot->primary_phone}}<br>
                Email: {{$quot->primary_email}}<br>
                GSTIN: {{$quot->owner_gst}}</p>
        </td>
        <td class="no-border" style="width: 5%;"></td>
        <td class="no-border" style="width: 50%;">
            <table class="address-table">
                <tr><td class="section-title">Invoice Date</td></tr>
                <tr><td>{{date('d-m-Y', strtotime($quot->invoice_date))}}</td></tr>
                <tr><td class="section-title">Total</td></tr>
                <tr><td>INR {{number_format($grand_total, 2, '.', ',')}}</td></tr>
            </table>
        </td>
    </tr>
</table>

<!-- Optional GST Notice -->
@if($quot->tax_preference == "false")
    <p style="font-weight: bold; font-size: 11px; margin: 10px 0;">
        (SUPPLY MEANT FOR EXPORT / SUPPLY TO SEZ UNIT OR DEVELOPER WITHOUT PAYMENT OF IGST)
    </p>
@endif

<!-- Product Table -->
<table style="margin-top: 10px;">
    <thead>
    <tr>
        <th>#</th>
        <th>Product Name / HSN</th>
        <th>Qty / UOM</th>
        <th>Unit Price</th>
        @if($discsum != 0)
            <th>Disc (%)</th>
            <th>Disc Amt</th>
        @endif
        @if($quot->tax_preference == true)
            <th>GST %</th>
            @if($company->state != $quot->billing_state)
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
    @php $srno = 0; @endphp
    @foreach($quotitem as $item)
        @php
            $variantLabel = trim(($item->value1 ?? '') . ((($item->value1 ?? '') !== '' && ($item->value2 ?? '') !== '') ? ' / ' : '') . ($item->value2 ?? ''));
        @endphp
        <tr>
            <td class="text-center">{{ ++$srno }}</td>
            <td>{{ $item->item_code }} - {{ $item->product_name }}{{ $variantLabel !== '' ? ' ('.$variantLabel.')' : '' }}<br>HSN: {{ $item->hsn }}</td>
            <td class="text-center">{{ $item->qty }} {{ $item->uom_name }}</td>
            <td class="text-right">{{ number_format($item->price, 2, '.', ',') }}</td>
            @if($discsum != 0)
                <td class="text-center">{{ $item->discount_per }}</td>
                <td class="text-right">{{ number_format($item->discount_amount, 2, '.', ',') }}</td>
            @endif

            <td class="text-center">{{ $item->gst_per + $item->cgst_per + $item->sgst_per }}</td>
            @if($quot->tax_preference == true)
                @if($company->state != $quot->billing_state)
                    <td class="text-right">{{ number_format($item->gst_amount, 2, '.', ',') }}</td>
                @else
                    <td class="text-right">{{ number_format($item->cgst_amount, 2, '.', ',') }}</td>
                    <td class="text-right">{{ number_format($item->sgst_amount, 2, '.', ',') }}</td>
                @endif
            @endif
            <td class="text-right">{{ number_format($item->total_amount, 2, '.', ',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Totals & Bank Details -->
<table style="margin-top: 10px;">
    <tr>
        <td style="width: 50%;">
            <div class="section-title">Bank Details</div>
            <table class="bank-details">
                @if($company->bank_name)<tr><td>Bank Name</td><td>: {{$company->bank_name}}</td></tr>@endif
                @if($company->bank_branch)<tr><td>Branch</td><td>: {{$company->bank_branch}}</td></tr>@endif
                @if($company->account_no)<tr><td>Account No</td><td>: {{$company->account_no}}</td></tr>@endif
                @if($company->account_type)<tr><td>Account Type</td><td>: {{$company->account_type}}</td></tr>@endif
                @if($company->ifsc_code)<tr><td>IFSC</td><td>: {{$company->ifsc_code}}</td></tr>@endif
                @if($company->micr_code)<tr><td>MICR</td><td>: {{$company->micr_code}}</td></tr>@endif
            </table>
        </td>
        <td style="width: 50%;">
            <table class="totals-table" style="width: 100%;">
                <tr><td>Sub Total (+)</td><td class="text-right">{{ number_format($quot->item_total, 2, '.', ',') }}</td></tr>
                @if($discsum != 0)
                    <tr><td>Discount (-)</td><td class="text-right">{{ number_format($quot->discount_total, 2, '.', ',') }}</td></tr>
                @endif
                @if($quot->tax_preference == true)
                    @if($company->state != $quot->billing_state)
                         @if($quot->igsttotal > 0)<tr><td>IGST (+)</td><td class="text-right">{{ number_format($quot->igsttotal, 2, '.', ',') }}</td></tr>@endif
                    @else
                        @if($quot->cgsttotal > 0)<tr><td>CGST (+)</td><td class="text-right">{{ number_format($quot->cgsttotal, 2, '.', ',') }}</td></tr>@endif
                        @if($quot->sgsttotal > 0)<tr><td>SGST (+)</td><td class="text-right">{{ number_format($quot->sgsttotal, 2, '.', ',') }}</td></tr>@endif
                    @endif
                @endif
                    @if($quot->adjustment != 0)<tr><td>Adjustment (+)</td><td class="text-right">{{ number_format($quot->adjustment, 2, '.', ',') }}</td></tr>@endif
                <tr style="font-weight: bold;"><td>Grand Total</td><td class="text-right">{{ number_format($grand_total, 2, '.', ',') }}</td></tr>
            </table>
        </td>
    </tr>
</table>

<!-- Terms & Conditions -->
<div class="notices">
    <u>Terms & Conditions</u><br>
    {!! nl2br($quot->term_condition) !!}
</div>

</body>
</html>
