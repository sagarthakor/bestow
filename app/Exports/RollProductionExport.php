<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class RollProductionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Date',
            'Batch No',
            'Semi Product',
            'Niwar Code',
            'Formula Version',
            'Customer',
            'Roll Plan',
            'Planned Mtr',
            'Produced Mtr',
            'Wastage Mtr',
            'Efficiency %',
            'Rolls Made',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at ? date('d-m-Y', strtotime($row->created_at)) : '-',
            $row->batch_no,
            $row->product ?? '-',
            trim(($row->niwar_type ?? '') . '/' . ($row->niwar_code ?? '')),
            $row->roll_formula_version ? 'V' . $row->roll_formula_version : '-',
            $row->customer ?? '-',
            $row->no_of_rolls . ' x ' . $row->roll_length_mtr . ' mtr',
            number_format($row->planned_mtr, 2),
            number_format($row->produced_mtr ?? 0, 2),
            number_format($row->wastage_mtr ?? 0, 2),
            $row->status == 'Y' && $row->planned_mtr > 0
                ? number_format($row->produced_mtr / $row->planned_mtr * 100, 2)
                : '-',
            $row->rolls_made ?? 0,
            ['N' => 'Pending', 'Y' => 'Completed', 'C' => 'Cancelled'][$row->status] ?? $row->status,
        ];
    }
}
