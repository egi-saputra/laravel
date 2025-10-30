<?php

namespace App\Exports;

use App\Models\PresensiSiswa;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiHariIniExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $today = Carbon::today();

        return PresensiSiswa::with(['user', 'dataSiswa.kelas'])
            ->whereDate('created_at', $today)
            ->get()
            ->map(fn($p) => [
                'Nama Siswa' => $p->user->name ?? '-',
                'Kelas' => $p->dataSiswa->kelas->kelas ?? '-',
                'Keterangan' => $p->keterangan ?? '-',
                'Jam Presensi' => $p->created_at->format('H:i'),
            ]);
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Kelas', 'Keterangan', 'Jam Presensi'];
    }
}
