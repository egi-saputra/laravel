<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MapelExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return collect([
            ['Matematika', 'G000'],
            ['Bahasa Indonesia', 'G001'],
            ['Bahasa Inggris', 'G002'],
            ['Sejarah Indonesia', 'G003'],
            ['Pendidikan Pancasila', 'G004'],
            ['IPAS', 'G005'],
            ['PKWU', 'G006'],
            ['Pendidikan Agama Islam', 'G007'],
            ['Informatika', 'G008'],
            ['PJOK', 'G009'],
            ['Seni Rupa', 'G010'],
            ['Bimbingan Konseling', 'G011'],
            ['Muatan Lokal Potensi Daerah', 'G012'],
            ['Muatan Lokal Bahasa Daerah', 'G013'],
            ['Kecerdasan Artifisial', 'G014'],
            ['BTQ', 'G015']
        ]);
    }

    public function headings(): array
    {
        return ['mapel', 'pengampu']; // heading wajib
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Pasang Data Validation agar heading tidak bisa diedit
                $headings = $this->headings();
                foreach ($headings as $index => $heading) {
                    $colLetter = Coordinate::stringFromColumnIndex($index+1);

                    $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);

                    // hanya boleh isi heading yang sama
                    $validation->setFormula1(sprintf('"%s"', $heading));

                    $validation->setErrorTitle('Input tidak valid');
                    $validation->setError('Heading ini tidak boleh diubah');
                    $validation->setPromptTitle('Info');
                    $validation->setPrompt('Heading ini hanya bisa bernilai persis: '.$heading);
                }

                // Auto-size semua kolom biar rapi
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }
            },
        ];
    }
}
