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
        // Mapping data supaya sesuai kolom Excel
        return $this->data->map(function ($item) {
            return [
                'Nama Siswa' => $item->siswa->nama_lengkap ?? '-',
                'Hadir'      => $item->keterangan == 'Hadir' ? 1 : 0,
                'Sakit'      => $item->keterangan == 'Sakit' ? 1 : 0,
                'Izin'       => $item->keterangan == 'Izin' ? 1 : 0,
                'Alpa'       => $item->keterangan == 'Alpa' ? 1 : 0,
                'Tanggal'    => $item->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Hadir', 'Sakit', 'Izin', 'Alpa', 'Tanggal'];
    }
}
