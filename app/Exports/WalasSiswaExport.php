<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class WalasSiswaExport implements FromCollection, WithHeadings, WithEvents
    {
        public function collection()
        {
            // Template kosong dengan contoh dummy
            return collect([
        ['nama_lengkap' => 'Siswa W 1',  'email' => 'siswa1w@mail.com',  'nis' => '2000000001', 'nisn' => '1200000011'],
    ]);
    }

    public function headings(): array
    {
        return ['nama_lengkap', 'email', 'nis', 'nisn'];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();
    //             $headings = $this->headings();

    //             // Auto-size kolom & bold heading
    //             foreach (range(1, count($headings)) as $col) {
    //                 $colLetter = Coordinate::stringFromColumnIndex($col);
    //                 $sheet->getColumnDimension($colLetter)->setAutoSize(true);
    //             }
    //             $lastCol = Coordinate::stringFromColumnIndex(count($headings));
    //             $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);

    //             // Data validation untuk heading (agar tidak diubah)
    //             foreach ($headings as $index => $heading) {
    //                 $colLetter = Coordinate::stringFromColumnIndex($index + 1);
    //                 $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
    //                 $validation->setType(DataValidation::TYPE_LIST);
    //                 $validation->setErrorStyle(DataValidation::STYLE_STOP);
    //                 $validation->setAllowBlank(false);
    //                 $validation->setShowInputMessage(true);
    //                 $validation->setShowErrorMessage(true);
    //                 $validation->setShowDropDown(true);
    //                 $validation->setFormula1(sprintf('"%s"', $heading));
    //                 $validation->setErrorTitle('Input tidak valid');
    //                 $validation->setError('Heading ini tidak boleh diubah');
    //                 $validation->setPromptTitle('Info');
    //                 $validation->setPrompt('Heading ini hanya bisa bernilai persis: '.$heading);
    //             }
    //         },
    //     ];
    // }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headings = $this->headings();

                // Auto-size kolom & bold heading
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }
                $lastCol = Coordinate::stringFromColumnIndex(count($headings));
                $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);

                // Validasi NIS & NISN harus 10 digit angka
                $lastRow = 40; // jumlah baris template, bisa disesuaikan
                $columns = [
                    'C' => 'nis',
                    'D' => 'nisn'
                ];

                foreach ($columns as $colLetter => $label) {
                    for ($row = 2; $row <= $lastRow; $row++) {
                        $cell = $sheet->getCell("{$colLetter}{$row}");
                        $validation = $cell->getDataValidation();
                        $validation->setType(DataValidation::TYPE_CUSTOM);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(false);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setErrorTitle('Input tidak valid');
                        $validation->setError("{$label} harus 10 digit angka");
                        $validation->setFormula1('AND(ISNUMBER('.$colLetter.$row.'*1),LEN('.$colLetter.$row.')=10)');
                    }
                }
            },
        ];
    }
}
