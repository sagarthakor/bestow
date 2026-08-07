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

class ProductWiseSalesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Category',
            'SO No',
            'Order Date',
            'Customer',
            'Qty',
            'Price',
            'Amount',
        ];
    }

    public function map($row): array
    {
        return [
            \App\product::nameWithVariantInline($row->product, $row->value1 ?? null, $row->value2 ?? null),
            $row->category_name ?? '-',
            $row->order_no,
            Carbon::parse($row->order_date)->format('d-m-Y'),
            $row->customer,
            $row->qty,
            number_format($row->price, 2),
            number_format($row->amount, 2),
        ];
    }
}
