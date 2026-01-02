<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BankSoalExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return collect([
            [
                '',                // nomor
                '',                // soal
                'Pilihan Ganda',   // tipe_soal
                'Tanpa Lampiran',  // jenis_lampiran
                '',                // link_lampiran
                '',                // jawaban_benar
                '', '', '', '', '' // opsi A–E
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'No',
            'Soal',
            'Tipe Soal',
            'Jenis Lampiran',
            'Link Lampiran',
            'Jawaban Benar',
            'Opsi A',
            'Opsi B',
            'Opsi C',
            'Opsi D',
            'Opsi E',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet     = $event->sheet->getDelegate();
                $headings  = $this->headings();
                $lastCol   = Coordinate::stringFromColumnIndex(count($headings));

                /** ----------------------------------------------------
                 * 1. Proteksi heading (dropdown berisi 1 pilihan)
                 * ---------------------------------------------------- */
                foreach ($headings as $index => $heading) {
                    $colLetter = Coordinate::stringFromColumnIndex($index + 1);
                    $validation = $sheet->getCell("{$colLetter}1")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1(sprintf('"%s"', $heading));
                }

                /** ----------------------------------------------------
                 * 2. Lebar kolom rapi
                 * ---------------------------------------------------- */
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(50);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setWidth(40);
                $sheet->getColumnDimension('F')->setAutoSize(true);

                foreach (['G','H','I','J','K'] as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                /** ----------------------------------------------------
                 * 3. Tinggi baris
                 * ---------------------------------------------------- */
                $sheet->getRowDimension(1)->setRowHeight(25); 
                $sheet->getRowDimension(2)->setRowHeight(30); 

                /** ----------------------------------------------------
                 * 4. Heading → Center + Middle + Bold
                 * ---------------------------------------------------- */
                $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /** ----------------------------------------------------
                 * 5. Isi (row 2) → Center + Middle untuk semua,
                 *    kecuali kolom Soal (B) → Left + Middle
                 * ---------------------------------------------------- */

                // Semua kolom center (A2:K2)
                $sheet->getStyle("A2:{$lastCol}2")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                      ->setVertical(Alignment::VERTICAL_CENTER);

                // Kolom B (Soal) → Left + Middle + WrapText
                $sheet->getStyle("B2")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                      ->setVertical(Alignment::VERTICAL_CENTER)
                      ->setWrapText(true);

            }
        ];
    }
}
