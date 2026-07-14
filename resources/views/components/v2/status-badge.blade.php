@props(['status', 'map' => []])
@php
    $defaultMap = [
        'Created' => 'primary', 'Approved' => 'success', 'Delivered' => 'success',
        'Cancelled' => 'danger', 'Canceled' => 'danger', 'Accepted' => 'success',
        'Invoiced' => 'success', 'Pending' => 'warning', 'Active' => 'success',
        'Inactive' => 'neutral', 'Draft' => 'neutral',
    ];
    $variant = array_merge($defaultMap, $map)[$status] ?? 'neutral';
@endphp
<span {{ $attributes->merge(['class' => 'badge badge-soft-' . $variant]) }}>{{ $status ?? '-' }}</span>
