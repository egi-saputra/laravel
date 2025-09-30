<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SiswaExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Template dummy untuk import siswa
        return collect([
            [
                'nama_lengkap'     => 'Siswa 1',
                'email'            => 'siswa1@mail.com',
                'nis'              => '23001',
                'nisn'             => '1000000001',
                'kelas'            => 'K001',
                'program_kejuruan' => 'Perkantoran',
            ],
            [
                'nama_lengkap'     => 'Siswa 2',
                'email'            => 'siswa2@mail.com',
                'nis'              => '23002',
                'nisn'             => '1000000002',
                'kelas'            => 'K002',
                'program_kejuruan' => 'Pemasaran',
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Nama Lengkap', 'Email', 'NIS', 'NISN', 'Kelas', 'Program Kejuruan'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headings = $this->headings();

                // Heading bold & auto-size
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }

                $lastCol = Coordinate::stringFromColumnIndex(count($headings));
                $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);

                // Lock heading dengan data validation
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
                    $validation->setPrompt('Heading ini hanya bisa bernilai persis: '.$heading);
                }
            },
        ];
    }
}
