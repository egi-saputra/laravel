<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    use HasFactory;

    protected $table = 'presensi_guru';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'guru_id',
        'tanggal',
        'bulan',
        'tahun',
        'keterangan',
        'apel',
        'upacara',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalGuru::class, 'jadwal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
