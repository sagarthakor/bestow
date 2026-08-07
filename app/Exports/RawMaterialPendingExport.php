<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class RawMaterialPendingExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Raw Material',
            'UOM',
            'Batch No',
            'Finish Product',
            'Customer',
            'Required Qty',
            'Available Stock',
            'Need To Order Qty',
        ];
    }

    public function map($row): array
    {
        $isPurchaseRequest = ($row->source ?? null) === 'purchase_request';

        return [
            \App\product::nameWithVariantInline($row->raw_material, $row->value1 ?? null, $row->value2 ?? null),
            $row->uom ?? '-',
            $row->batch_no,
            $row->finish_product ?? '-',
            $row->customer ?? '-',
            $isPurchaseRequest ? '-' : number_format($row->required_qty, 2),
            $isPurchaseRequest ? '-' : number_format($row->avalible_stock, 2),
            number_format($row->need_to_order_stock, 2),
        ];
    }
}
