{{--
    Product line items shown beneath a document row in the document-level
    reports (Quotation / Sales / Invoice / Challan / Sales Summary /
    Salesman Wise). Each product carries its Size/Color via <x-product-name>.

    A single full-width cell is used so the rows stay aligned whatever the
    host report's column count is.

        @include('admin.reports.partials.doc_items', [
            'items'   => $items[$data->quot_no] ?? null,
            'colspan' => 8,
        ])
--}}
@php
    $items = $items ?? null;
    $colspan = $colspan ?? 8;
@endphp
@if(!empty($items) && count($items))
    @foreach($items as $line)
        @php
            // The product row can be gone (deleted product) while the document
            // line survives - fall back to the line's own description so the
            // row never renders as a blank cell.
            $lineName = trim((string) ($line->product_name ?? ''));
            if ($lineName === '') {
                $lineName = trim((string) ($line->description ?? '')) ?: '(product removed)';
            }
        @endphp
        <tr class="rpt-item-row" style="background:#fcfcfc;">
            <td colspan="{{ $colspan }}" style="padding:4px 8px 4px 34px;border-top:0;">
                <span style="color:#bbb;margin-right:6px;">&#8627;</span>
                <x-product-name :name="($line->product_name ?? '') !== '' && $line->item_code ? $line->item_code.' - '.$lineName : $lineName"
                                :color="$line->value1 ?? null" :size="$line->value2 ?? null" />
                <span style="float:right;color:#666;white-space:nowrap;">
                    {{ rtrim(rtrim(number_format((float) $line->qty, 2, '.', ''), '0'), '.') ?: '0' }}
                    &times; {{ number_format((float) $line->price, 2) }}
                </span>
            </td>
        </tr>
    @endforeach
@endif
