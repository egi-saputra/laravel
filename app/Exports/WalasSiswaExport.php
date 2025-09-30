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
        ['nama_lengkap' => 'Siswa W 2',  'email' => 'siswa2w@mail.com',  'nis' => '2000000002', 'nisn' => '1200000012'],
        ['nama_lengkap' => 'Siswa W 3',  'email' => 'siswa3w@mail.com',  'nis' => '2000000003', 'nisn' => '1200000013'],
        ['nama_lengkap' => 'Siswa W 4',  'email' => 'siswa4w@mail.com',  'nis' => '2000000004', 'nisn' => '1200000014'],
        ['nama_lengkap' => 'Siswa W 5',  'email' => 'siswa5w@mail.com',  'nis' => '2000000005', 'nisn' => '1200000015'],
        ['nama_lengkap' => 'Siswa W 6',  'email' => 'siswa6w@mail.com',  'nis' => '2000000006', 'nisn' => '1200000016'],
        ['nama_lengkap' => 'Siswa W 7',  'email' => 'siswa7w@mail.com',  'nis' => '2000000007', 'nisn' => '1200000017'],
        ['nama_lengkap' => 'Siswa W 8',  'email' => 'siswa8w@mail.com',  'nis' => '2000000008', 'nisn' => '1200000018'],
        ['nama_lengkap' => 'Siswa W 9',  'email' => 'siswa9w@mail.com',  'nis' => '2000000009', 'nisn' => '1200000019'],
        ['nama_lengkap' => 'Siswa W 10', 'email' => 'siswa10w@mail.com', 'nis' => '2000000010', 'nisn' => '1200000020'],
        ['nama_lengkap' => 'Siswa W 11', 'email' => 'siswa11w@mail.com', 'nis' => '2000000011', 'nisn' => '1200000021'],
        ['nama_lengkap' => 'Siswa W 12', 'email' => 'siswa12w@mail.com', 'nis' => '2000000012', 'nisn' => '1200000022'],
        ['nama_lengkap' => 'Siswa W 13', 'email' => 'siswa13w@mail.com', 'nis' => '2000000013', 'nisn' => '1200000023'],
        ['nama_lengkap' => 'Siswa W 14', 'email' => 'siswa14w@mail.com', 'nis' => '2000000014', 'nisn' => '1200000024'],
        ['nama_lengkap' => 'Siswa W 15', 'email' => 'siswa15w@mail.com', 'nis' => '2000000015', 'nisn' => '1200000025'],
        ['nama_lengkap' => 'Siswa W 16', 'email' => 'siswa16w@mail.com', 'nis' => '2000000016', 'nisn' => '1200000026'],
        ['nama_lengkap' => 'Siswa W 17', 'email' => 'siswa17w@mail.com', 'nis' => '2000000017', 'nisn' => '1200000027'],
        ['nama_lengkap' => 'Siswa W 18', 'email' => 'siswa18w@mail.com', 'nis' => '2000000018', 'nisn' => '1200000028'],
        ['nama_lengkap' => 'Siswa W 19', 'email' => 'siswa19w@mail.com', 'nis' => '2000000019', 'nisn' => '1200000029'],
        ['nama_lengkap' => 'Siswa W 20', 'email' => 'siswa20w@mail.com', 'nis' => '2000000020', 'nisn' => '1200000030'],
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
