<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShiftExport implements FromCollection, WithHeadings
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'Produk' => $item->nama,
                'Qty' => $item->qty,
                'Subtotal' => $item->subtotal,
            ];
        });
    }

    public function headings(): array
    {
        return ['Produk', 'Qty', 'Subtotal'];
    }
}
