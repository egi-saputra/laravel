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
        // Contoh data awal: Kode Mapel, Nama Mapel, Pengampu (kode guru atau nama)
        return collect([
            ['MP001', 'Matematika', 'G0001'],
            ['MP002', 'Bahasa Indonesia', 'G0002'],
        ]);
    }

    public function headings(): array
    {
        // Tambahkan kolom kode mapel sebagai kolom pertama
        return ['kode', 'mapel', 'pengampu'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Pasang validasi agar heading tidak bisa diubah
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

                    // hanya boleh isi heading yang sama
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

                // Styling tambahan opsional: tebalkan header
                $sheet->getStyle('A1:C1')->getFont()->setBold(true);
                $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
