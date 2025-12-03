<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiSiswaExport implements 
    FromCollection, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles,
    WithEvents
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
        return [
            'Nama Siswa',
            'Hadir',
            'Sakit',
            'Izin',
            'Alpa',
        ];
    }

    /** Style heading (center) */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // row 1 (heading)
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center',
                ],
                'font' => ['bold' => true],
            ],
        ];
    }

    /** Style semua data setelah heading */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();

                // Kolom A = Nama Siswa → LEFT
                $sheet->getStyle("A2:A{$lastRow}")
                      ->getAlignment()->setHorizontal('left');

                // Kolom B-E = angka → CENTER
                $sheet->getStyle("B2:E{$lastRow}")
                      ->getAlignment()->setHorizontal('center');
            }
        ];
    }
}
