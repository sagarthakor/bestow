{{--
    Reusable table card (v2). Wraps any hand-written <table data-v2-datatable>
    with a card header (+ optional "Add New" button) and an optional filter
    toolbar slot. DataTables (sticky header, search, export) auto-attaches
    to the inner table via public/admin-v2/js/datatable-init.js — no extra
    wiring needed on the consuming page beyond the data-v2-datatable attribute.

    Usage:
        <x-v2.table-card title="Category List" :add-url="route('admin.category.add')">
            <x-slot name="toolbar">
                ... filter <form> fields ...
            </x-slot>

            <table data-v2-datatable data-page-length="10" class="table table-striped mb-0">
                <thead>...</thead>
                <tbody>...</tbody>
            </table>
        </x-v2.table-card>
--}}
@props(['title' => null, 'addUrl' => null, 'addLabel' => 'Add New'])

<div class="card table-card">
    @if($title || $addUrl)
        <div class="card-header">
            <span>{{ $title }}</span>
            @if($addUrl)
                <a href="{{ $addUrl }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i> {{ $addLabel }}</a>
            @endif
        </div>
    @endif

    @isset($toolbar)
        <div class="table-toolbar">
            {{ $toolbar }}
        </div>
    @endisset

    <div class="table-responsive">
        {{ $slot }}
    </div>
</div>
