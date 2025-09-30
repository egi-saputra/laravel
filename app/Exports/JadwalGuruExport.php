<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\DataGuru;
use App\Models\DataKelas;

class JadwalGuruExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Contoh data supaya user melihat format
        $guru = DataGuru::first();
        $kelas = DataKelas::first();

        return collect([
            // Format: hari, sesi, jam_mulai, jam_selesai, nama_guru, kelas
            ['Senin', 'Pertama', '07:00', '07:40', 'Budi Santoso', 'K001'],
            ['Senin', 'Pertama', '07:40', '08:20', 'Budi Santoso', 'K001'],
            ['Senin', 'Kedua', '08:20', '09:00', 'Budi Santoso', 'K002'],
            ['Senin', 'Kedua', '09:00', '09:40', 'Budi Santoso', 'K002'],
            ['Senin', 'Ketiga', '10:00', '10:40', 'Budi Santoso', 'K003'],
            ['Senin', 'Ketiga', '10:40', '11:20', 'Budi Santoso', 'K003'],
            ['Senin', 'Keempat', '11:20', '11:50', 'Budi Santoso', 'K004'],
            ['Senin', 'Keempat', '12:10', '12:40', 'Budi Santoso', 'K004'],
        ]);
    }

    public function headings(): array
    {
        return [
            'hari',
            'sesi',
            'jam_mulai',
            'jam_selesai',
            'nama_guru', // user bisa input kode/nama guru
            'kelas',     // user bisa input kode/kelas
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headings = $this->headings();

                // Auto-size semua kolom
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }

                // ✅ Validasi format jam (kolom C = jam_mulai, D = jam_selesai)
                foreach (['C', 'D'] as $colLetter) {
                    for ($row = 2; $row <= 100; $row++) { // misal batasi sampai 100 baris
                        $validation = $sheet->getCell("{$colLetter}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_CUSTOM);
                        // cek apakah nilai valid jam di Excel
                        $validation->setFormula1("ISNUMBER(TIMEVALUE({$colLetter}{$row}))");
                        $validation->setErrorTitle('Format salah');
                        $validation->setError('Harus dalam format jam (contoh: 07:00)');
                        $validation->setAllowBlank(false);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                    }
                }
            },
        ];
    }

}
