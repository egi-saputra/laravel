<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soal';

    protected $fillable = [
        'soal_id',
        'soal',
        'tipe_soal',
        'jenis_lampiran',
        'link_lampiran',
        'jawaban_benar',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'nilai',
    ];

    /**
     * Relasi ke Soal (Parent)
     */
    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
