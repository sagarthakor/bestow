<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class OutOfStockExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    // Data
    public function collection(): Collection
    {
        return collect($this->data);
    }

    // ✅ Heading
    public function headings(): array
    {
        return [
            'SO No',
            'Order Date',
            'Customer',
            'Category',
            'Subcategory',
            'Product',
            'Sold Qty',
            'Stock Qty',
            'Need To Purchase Qty',
        ];
    }

    // ✅ Mapping (important for formatting)
    public function map($row): array
    {
        return [
            $row->order_no,
            Carbon::parse($row->order_date)->format('d-m-Y'),
            $row->customer,
            $row->category_name ?? '-',
            $row->subcategory_name ?? '-',
            \App\product::nameWithVariantInline($row->product, $row->value1 ?? null, $row->value2 ?? null),
            number_format($row->sold_qty, 2),
            number_format($row->stock_qty, 2),
            number_format($row->need_to_purchase_qty, 2),
        ];
    }
}
