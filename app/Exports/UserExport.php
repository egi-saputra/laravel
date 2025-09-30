<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class UserExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return ['name', 'email', 'password', 'role'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headings = $this->headings();
                foreach ($headings as $index => $heading) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index+1);

                    $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);

                    // nilai valid hanya heading asli
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
