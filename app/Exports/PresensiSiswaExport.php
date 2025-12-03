<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiSiswaExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Nama Siswa' => $item->nama_lengkap,
                'Hadir'      => $item->hadir_count,
                'Sakit'      => $item->sakit_count,
                'Izin'       => $item->izin_count,
                'Alpa'       => $item->alpa_count,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Hadir', 'Sakit', 'Izin', 'Alpa'];
    }
}
