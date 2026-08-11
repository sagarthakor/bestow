<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class BeltCuttingExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Cutting No',
            'Roll No',
            'Batch No',
            'Belt (size-wise)',
            'Size',
            'Pieces Cut',
            'Rejected',
            'Good',
            'Mtr / Piece',
            'Consumed Mtr',
            'Trim Wastage Mtr',
            'Roll Balance Mtr',
            'Customer',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at ? date('d-m-Y', strtotime($row->created_at)) : '-',
            $row->cutting_no,
            $row->roll_no ?? '-',
            $row->batch_no ?? '-',
            \App\product::nameWithVariantInline($row->product ?? '-', $row->value1 ?? null, $row->value2 ?? null),
            $row->size ?? '-',
            $row->pieces,
            $row->rejected_pieces ?? 0,
            $row->pieces - ($row->rejected_pieces ?? 0),
            $row->meter_per_piece,
            number_format($row->total_meter, 2),
            number_format($row->wastage_mtr ?? 0, 2),
            number_format($row->balance_mtr ?? 0, 2),
            $row->customer ?? '-',
            $row->status == 'C' ? 'Cancelled' : 'Completed',
        ];
    }
}
