<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class StockAvailableExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Product',
            'Item Code',
            'UOM',
            'Category',
            'Subcategory',
            'Available Stock Qty',
        ];
    }

    public function map($row): array
    {
        return [
            \App\product::nameWithVariantInline($row->product, $row->value1 ?? null, $row->value2 ?? null),
            $row->item_code ?? '-',
            $row->uom ?? '-',
            $row->category_name ?? '-',
            $row->subcategory_name ?? '-',
            number_format($row->stock_qty, 2),
        ];
    }
}
