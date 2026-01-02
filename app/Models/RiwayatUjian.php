<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatUjian extends Model
{
    use HasFactory;

    protected $table = 'riwayat_ujian';

    protected $fillable = [
        'user_id',
        'soal_id',
        'quest_id',
        'jawaban',
        'benar',
        'nilai',
        'status',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel soal (ujian utama)
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    // Relasi ke detail soal di bank soal
    public function quest()
    {
        return $this->belongsTo(BankSoal::class, 'quest_id');
    }
}
