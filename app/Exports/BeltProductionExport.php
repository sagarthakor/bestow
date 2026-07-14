<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class BeltProductionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Batch No',
            'Product',
            'Customer',
            'Planned Qty',
            'Produced Qty',
            'Wastage Qty',
            'Pending Qty',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->batch_no,
            $row->product,
            $row->customer ?? '-',
            number_format($row->planned_qty, 2),
            number_format($row->total_production ?? 0, 2),
            number_format($row->total_wastage_nos ?? 0, 2),
            number_format($row->pending_qty, 2),
            $row->status == 'Y' ? 'Completed' : 'Pending',
        ];
    }
}
