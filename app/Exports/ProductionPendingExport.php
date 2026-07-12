<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class ProductionPendingExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;
    protected $stageLabel;

    public function __construct($data, $stageLabel)
    {
        $this->data = $data;
        $this->stageLabel = $stageLabel;
    }

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Stage',
            'Product',
            'Customer',
            'Machine',
            'Nos',
            'Size',
            'Total Material',
        ];
    }

    public function map($row): array
    {
        return [
            $row->batch_no,
            $this->stageLabel,
            $row->product_name ?? '-',
            $row->customer_name ?? '-',
            $row->machine_name ?? '-',
            $row->nos,
            $row->size,
            $row->total_material,
        ];
    }
}
