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

class SalesmanWiseSalesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;
    protected $period;

    public function __construct($data, $period)
    {
        $this->data = $data;
        $this->period = $period;
    }

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Salesman',
            'SO No',
            'Order Date',
            $this->period === 'monthly' ? 'Month' : 'Day',
            'Customer',
            'Status',
            'Grand Total',
        ];
    }

    public function map($row): array
    {
        return [
            $row->salesman_name,
            $row->salaesorder_no,
            Carbon::parse($row->salaesorder_date)->format('d-m-Y'),
            $this->period === 'monthly'
                ? Carbon::parse($row->salaesorder_date)->format('F Y')
                : Carbon::parse($row->salaesorder_date)->format('d M Y'),
            $row->customer_name,
            $row->status,
            number_format($row->grand_total, 2),
        ];
    }
}
