<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class quotationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Quot No',
            'Quot Date',
            'Customer',
            'Salesman',
            'Subject',
            'Stage',
            'Amount',
        ];
    }

    public function map($row): array
    {
        return [
            $row->quotation_no,
            $row->quot_date ? date('d-m-Y', strtotime($row->quot_date)) : '-',
            $row->customer_name ?? '-',
            $row->salesman_name ?? '-',
            $row->subject ?? '-',
            $row->quot_stage ?? '-',
            number_format($row->grand_total, 2),
        ];
    }
}
