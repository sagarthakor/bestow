<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class BeltMissingFormulaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Subcategory',
        ];
    }

    public function map($row): array
    {
        return [
            \App\product::nameWithVariantInline($row->product_name, $row->value1 ?? null, $row->value2 ?? null),
            $row->item_code ?? '-',
            $row->uom ?? '-',
            $row->subcategory_name ?? '-',
        ];
    }
}
