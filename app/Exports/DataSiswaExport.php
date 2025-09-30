<?php

namespace App\Exports;

use App\Models\DataSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class DataSiswaExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        // Paksa NIS/NISN jadi string biar Excel tidak ubah ke format scientific
        if (is_numeric($value) && strlen($value) >= 9) {
            $cell->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        return DataSiswa::with(['kelas', 'kejuruan', 'user'])
            ->get()
            ->map(function ($s, $index) {
                return [
                    'No' => $index + 1,
                    'Nama Lengkap' => $s->nama_lengkap,
                    'NIS' => $s->nis,
                    'NISN' => $s->nisn,
                    'Kelas' => $s->kelas->kelas ?? '-',
                    'Kejuruan' => $s->kejuruan->nama_kejuruan ?? '-',
                    'Email' => $s->user->email ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Kelas',
            'Kejuruan',
            'Email',
        ];
    }
}
