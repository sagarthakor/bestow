{{--
    Product name with its variant stacked underneath:

        Product Name
        Size: 7 | Color: Red

    Usage (row can be an Eloquent product or a joined stdClass row):
        <x-product-name :row="$item" />
        <x-product-name :row="$item" code />     -- prefixes item_code
        <x-product-name :row="$item" print />    -- dompdf-safe sizing
        <x-product-name name="..." :color="$c" :size="$s" />

    Size or Color is dropped when empty; with both empty only the name renders.
    For <option>/export/JS text use \App\product::nameWithVariantInline() instead.
--}}
@props([
    'row'   => null,
    'name'  => null,
    'color' => null,
    'size'  => null,
    'code'  => false,
    'print' => false,
])
@php
    $label = $name ?? ($row->product_name ?? '');
    $itemCode = $row->item_code ?? null;
    if ($code && trim((string) $itemCode) !== '') {
        $label = trim($itemCode) . ' - ' . $label;
    }
    $c = $color ?? ($row->value1 ?? null);
    $s = $size  ?? ($row->value2 ?? null);
@endphp
{!! \App\product::nameWithVariant($label, $c, $s, (bool) $print) !!}
