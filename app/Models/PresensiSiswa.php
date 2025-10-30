<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    use HasFactory;

    protected $table = 'presensi_siswa';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'keterangan',
        'is_selesai',
    ];

    protected $casts = [
        'is_selesai' => 'boolean',
    ];

    // Relasi ke user (guru/walas/sekretaris yang melakukan presensi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 Relasi ke data_siswa
    public function dataSiswa()
    {
        return $this->belongsTo(DataSiswa::class, 'siswa_id');
    }
}
