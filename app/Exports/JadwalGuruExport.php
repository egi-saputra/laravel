<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\DataKelas;
use App\Models\DataMapel;

class JadwalGuruExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Contoh data supaya user melihat format
        return collect([
            ['Senin', 'Pertama', '07:00', '07:40', 'MTK', 'K001'],
            ['Senin', 'Pertama', '07:40', '08:20', 'MTK', 'K001'],
            ['Senin', 'Kedua', '08:20', '09:00', 'IPA', 'K002'],
            ['Senin', 'Kedua', '09:00', '09:40', 'IPA', 'K002'],
            ['Senin', 'Ketiga', '10:00', '10:40', 'IPS', 'K003'],
            ['Senin', 'Ketiga', '10:40', '11:20', 'IPS', 'K003'],
            ['Senin', 'Keempat', '11:20', '11:50', 'BIND', 'K004'],
            ['Senin', 'Keempat', '12:10', '12:40', 'BIND', 'K004'],
        ]);
    }

    public function headings(): array
    {
        return [
            'hari',
            'sesi',
            'jam_mulai',
            'jam_selesai',
            'mapel',
            'kelas',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headings = $this->headings();

                // ✅ Validasi heading agar tidak bisa diubah
                foreach ($headings as $index => $heading) {
                    $colLetter = Coordinate::stringFromColumnIndex($index + 1);

                    $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1(sprintf('"%s"', $heading));
                    $validation->setErrorTitle('Input tidak valid');
                    $validation->setError('Heading ini tidak boleh diubah');
                    $validation->setPromptTitle('Info');
                    $validation->setPrompt('Heading ini hanya bisa bernilai persis: ' . $heading);
                }

                // Auto-size semua kolom agar rapi
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }

                // Styling tambahan: tebalkan header dan center align
                $sheet->getStyle('A1:F1')->getFont()->setBold(true);
                $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

                // ✅ Validasi format jam (kolom C = jam_mulai, D = jam_selesai)
                foreach (['C', 'D'] as $colLetter) {
                    for ($row = 2; $row <= 100; $row++) {
                        $validation = $sheet->getCell("{$colLetter}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_CUSTOM);
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
