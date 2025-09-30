<?php

namespace App\Observers;

use App\Models\DataSiswa;

class DataSiswaObserver
{
    public function created(DataSiswa $dataSiswa)
    {
        if ($dataSiswa->user) {
            $dataSiswa->user()->update([
                'name' => $dataSiswa->nama_lengkap,
            ]);
        }
    }

    public function updated(DataSiswa $dataSiswa)
    {
        if ($dataSiswa->wasChanged('nama_lengkap') && $dataSiswa->user->name !== $dataSiswa->nama_lengkap) {
            $dataSiswa->user()->update([
                'name' => $dataSiswa->nama_lengkap,
            ]);
        }
    }
}
