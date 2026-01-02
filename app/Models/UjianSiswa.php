<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianSiswa extends Model
{
    protected $table = 'ujian_siswa';
    protected $dates = ['waktu_mulai', 'waktu_selesai'];
    protected $casts = [
    'waktu_mulai' => 'datetime',
    'waktu_selesai' => 'datetime',
];

    protected $fillable = [
        'user_id',
        'soal_id',
        'waktu_mulai',
        'waktu_selesai',
        'token',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
