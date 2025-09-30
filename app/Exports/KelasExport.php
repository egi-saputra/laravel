<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class KelasExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return collect([
            ['K001', 'X MP 1', '1'],
            ['K002', 'X MP 2', '2'],
            ['K003', 'X MP 3', '3'],
            ['K004', 'XI MPLB 1', '4'],
            ['K005', 'XI MPLB 2', '5'],
            ['K006', 'XII OTKP 1', '6'],
            ['K007', 'XII OTKP 2', '7'],
            ['K008', 'X PM 1', '8'],
            ['K009', 'X PM 2', '9'],
            ['K010', 'XI BR 1', '10'],
            ['K011', 'XI BR 2', '11'],
            ['K012', 'XII BR', '12']
        ]);
    }

    public function headings(): array
    {
        return ['kode', 'kelas', 'walas'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Pasang Data Validation di baris heading
                $headings = $this->headings();
                foreach ($headings as $index => $heading) {
                    $colLetter = Coordinate::stringFromColumnIndex($index + 1);

                    $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);

                    // hanya boleh isi persis heading yang sudah ditentukan
                    $validation->setFormula1(sprintf('"%s"', $heading));

                    $validation->setErrorTitle('Input tidak valid');
                    $validation->setError('Heading ini tidak boleh diubah');
                    $validation->setPromptTitle('Info');
                    $validation->setPrompt('Heading ini hanya bisa bernilai persis: '.$heading);
                }

                // Auto-size semua kolom sesuai jumlah heading
                foreach (range(1, count($headings)) as $col) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }
            },
        ];
    }
}
