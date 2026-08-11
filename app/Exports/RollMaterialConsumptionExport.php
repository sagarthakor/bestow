<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class RollMaterialConsumptionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Category',
            'Raw Material',
            'Unit',
            'Batches',
            'Planned Qty',
            'Actual Qty',
            'Variance',
            'Variance %',
        ];
    }

    public function map($row): array
    {
        return [
            $row->category_name ?? '-',
            $row->material_name ?? '-',
            $row->unit,
            $row->batches,
            number_format($row->planned_qty, 2),
            number_format($row->actual_qty, 2),
            number_format($row->variance, 2),
            $row->planned_qty > 0 ? number_format($row->variance / $row->planned_qty * 100, 2) : '-',
        ];
    }
}
