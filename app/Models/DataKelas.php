<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataGuru;
use App\Models\JadwalGuru;
use App\Models\DataSiswa;

class DataKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'kelas',
        'walas_id',
        'jumlah_siswa'
    ];

    /**
     * Relasi ke wali kelas (satu guru jadi walas dari kelas ini)
     */
    public function waliKelas()
    {
        return $this->belongsTo(DataGuru::class, 'walas_id');
    }

    /**
     * Relasi ke JadwalGuru
     * Satu kelas punya banyak jadwal
     */
    public function jadwalGuru()
    {
        return $this->hasMany(JadwalGuru::class, 'kelas_id');
    }

    /**
     * Relasi ke DataSiswa
     * Satu kelas punya banyak siswa
     */
    public function siswa()
    {
        return $this->hasMany(DataSiswa::class, 'kelas_id', 'id');
    }

}
