<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping
};

class invoiceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
            'Invoice No',
            'Invoice Date',
            'Customer',
            'Salesman',
            'Subject',
            'Status',
            'Amount',
        ];
    }

    public function map($row): array
    {
        return [
            $row->invoice_number,
            $row->invoice_date ? date('d-m-Y', strtotime($row->invoice_date)) : '-',
            $row->customer_name ?? '-',
            $row->salesman_name ?? '-',
            $row->subject ?? '-',
            $row->status ?? '-',
            number_format($row->grand_total, 2),
        ];
    }
}
