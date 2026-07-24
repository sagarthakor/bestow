<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Order - {{$quot->salaesorder_no}}</title>
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

        h2 {
            text-align: center;
            margin: 0;
            padding: 10px 0 5px 0;
            font-size: 18px;
        }

        .company-block, .customer-block {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            font-size: 12px;
            line-height: 1.5;
        }

        .company-block {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: top;
        }

        th {
            background: #f0f0f0;
        }

        .totals td {
            font-weight: bold;
            text-align: right;
        }

        .no-border {
            border: none;
        }

        .section {
            margin-top: 10px;
        }

        .footer {
            margin-top: 20px;
            text-align: left;
        }
    </style>
</head>
<body>
<h2>Sales Order</h2>
<div class="company-block">
    <img src="{{ public_path('/company_logo/' . $company->logo) }}" height="80"><br>
    {{$company->company_name}}<br>
    {{$company->address}}<br>
    {{$company->address1}}, {{$company->address2}}<br>
    Phone: {{$company->phone}}, {{$company->mobile}}<br>
    Email: {{$company->email}}<br>
    GSTIN: {{$company->gst}}
</div>

<div class="customer-block" style="text-align:right;">
    <strong>Sales Order No:</strong> {{$quot->salaesorder_no}}<br>
    <strong>Date:</strong> {{date('d-m-Y', strtotime($quot->salaesorder_date))}}<br>
    {{--<strong>Valid Until:</strong> {{date('d-m-Y', strtotime($quot->due_date))}}<br>--}}
    <strong>Payment Terms:</strong> Net {{$quot->payment_terms}} Days<br>
    <strong>Total:</strong> {{number_format($quot->grand_total, 2)}}
</div>

<div style="margin-top: 10px;">
    <table>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <strong>Customer Address</strong><br>
                {{$quot->customer_name}}<br>
                {{strip_tags($quot->billing_address)}}<br>
                Mobile: {{$quot->primphone}}<br>
                Email: {{$quot->primary_email}}<br>
                GSTIN: {{$quot->owner_gst}}
            </td>
            <td style="width: 50%; vertical-align: top;">
                <strong>Shipping Address</strong><br>
                {{$quot->customer_name}}<br>
                {{$quot->shipping_address}}
            </td>
        </tr>
    </table>
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Description / HSN Code</th>
        @if(isset($isInternalPrint) && $isInternalPrint == 'yes')
        <th>Photo</th>
        @endif
        <th>Quantity / UOM</th>
        <th>Unit Price</th>
        @if($quot->tax_preference == true)
            <th>GST %</th>
            @if($company->state != $quot->state_id)
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
    @php $srno = 1; @endphp
    @foreach($quotitem as $item)
        <tr>
            <td>{{$srno++}}</td>
            @php
                $itemVariantLabel = trim(($item->value1 ?? '') . ((($item->value1 ?? '') !== '' && ($item->value2 ?? '') !== '') ? ' / ' : '') . ($item->value2 ?? ''));
            @endphp
            <td>{{$item->item_code}}<br>{{$item->product_name}}{{ $itemVariantLabel !== '' ? ' ('.$itemVariantLabel.')' : '' }}<br>HSN: {{$item->hsn}}</td>
            @if(isset($isInternalPrint) && $isInternalPrint == 'yes')
            <td><img src="{{asset('public/product_image/'.$item->product_image)}}" style="height: 65px" width="65px"></td>
            @endif
            <td>{{$item->qty}} {{$item->uom_name}}</td>
            <td>{{number_format($item->price, 2)}}</td>
            @if($quot->tax_preference == true)
                <td>{{$item->gst_per + $item->cgst_per + $item->sgst_per}}%</td>
                @if($company->state != $quot->state_id)
                    <td>{{number_format($item->gst_amount, 2)}}</td>
                @else
                    <td>{{number_format($item->sgst_amount, 2)}}</td>
                    <td>{{number_format($item->cgst_amount, 2)}}</td>
                @endif
            @endif
            <td>{{number_format($item->total, 2)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="section">
    <tr class="totals">
        <td class="no-border" style="width: 80%;">SUB TOTAL (+)</td>
        <td>{{number_format($quot->net_amount, 2)}}</td>
    </tr>
    @if($quot->cgstamount > 0)
        <tr class="totals">
            <td class="no-border">CGST TOTAL (+)</td>
            <td>{{number_format($quot->cgstamount, 2)}}</td>
        </tr>
        <tr class="totals">
            <td class="no-border">SGST TOTAL (+)</td>
            <td>{{number_format($quot->sgstamount, 2)}}</td>
        </tr>
    @endif
    @if($quot->gst_amount > 0)
        <tr class="totals">
            <td class="no-border">IGST TOTAL (+)</td>
            <td>{{number_format($quot->gst_amount, 2)}}</td>
        </tr>
    @endif
    @if($quot->adjustment != '')
        <tr class="totals">
            <td class="no-border">ADJUSTMENT (+)</td>
            <td>{{number_format($quot->adjustment, 2)}}</td>
        </tr>
    @endif
    <tr class="totals">
        <td class="no-border">GRAND TOTAL</td>
        <td>{{number_format($quot->grand_total, 2)}}</td>
    </tr>
</table>

<div class="section">
    <strong>Terms & Conditions</strong><br>
    {!! $quot->term_condition !!}
</div>

{{--<div class="footer">
    <p><strong>Sales Order Prepared By:</strong> {{$quot->first_name}} {{$quot->last_name}}</p>
</div>--}}

</body>
</html>
