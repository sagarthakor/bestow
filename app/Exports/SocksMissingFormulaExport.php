<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class SocksMissingFormulaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            $row->product_name,
            $row->item_code ?? '-',
            $row->uom ?? '-',
            $row->subcategory_name ?? '-',
        ];
    }
}
