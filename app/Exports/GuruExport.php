<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class GuruExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Contoh data template tanpa kolom password
        // return collect([
        //     ['G001', 'Budi Santoso', 'budi@mail.com'],
        //     ['G002', 'Siti Aminah', 'siti@mail.com'],
        // ]);
        return collect([
            ['G001', 'Budi Santoso', 'budi@mail.com'],
            ['G002', 'Siti Aminah', 'siti@mail.com'],
            ['G003', 'Andi Pratama', 'andi@mail.com'],
            ['G004', 'Dewi Lestari', 'dewi@mail.com'],
            ['G005', 'Rizky Hidayat', 'rizky@mail.com'],
            ['G006', 'Fitri Handayani', 'fitri@mail.com'],
            ['G007', 'Agus Setiawan', 'agus@mail.com'],
            ['G008', 'Nurul Aini', 'nurul@mail.com'],
            ['G009', 'Hendra Gunawan', 'hendra@mail.com'],
            ['G010', 'Putri Maharani', 'putri@mail.com'],
            ['G011', 'Rudi Hartono', 'rudi@mail.com'],
            ['G012', 'Intan Permata', 'intan@mail.com'],
            ['G013', 'Fajar Ramadhan', 'fajar@mail.com'],
            ['G014', 'Lina Marlina', 'lina@mail.com'],
            ['G015', 'Doni Saputra', 'doni@mail.com']
        ]);
    }

    public function headings(): array
    {
        // Hanya kode, nama, email
        return ['kode', 'nama', 'email'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $headings = $this->headings();

                // Pasang Data Validation agar heading tidak bisa diubah
                foreach ($headings as $index => $heading) {
                    $colLetter = Coordinate::stringFromColumnIndex($index + 1);

                    $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);

                    // hanya boleh persis sama dengan heading
                    $validation->setFormula1(sprintf('"%s"', $heading));

                    $validation->setErrorTitle('Input tidak valid');
                    $validation->setError('Heading ini tidak boleh diubah');
                    $validation->setPromptTitle('Info');
                    $validation->setPrompt('Heading ini hanya bisa bernilai persis: '.$heading);
                }

                // Auto-size semua kolom
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }

                // Bold heading biar lebih jelas
                $lastCol = Coordinate::stringFromColumnIndex(count($headings));
                $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
            },
        ];
    }
}
