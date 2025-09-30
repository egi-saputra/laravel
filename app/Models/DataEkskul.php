<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataGuru;
use App\Models\DataSiswa;
use App\Models\User;

class DataEkskul extends Model
{
    use HasFactory;

    protected $table = 'data_ekskul';
    protected $fillable = ['nama_ekskul', 'ekskul_id'];

    /**
     * Relasi ke DataGuru (pembina ekskul)
     */
    public function pembina()
    {
        return $this->belongsTo(DataGuru::class, 'ekskul_id', 'id');
    }

    /**
     * Ambil user dari DataGuru
     */
    public function userPembina()
    {
        return $this->hasOneThrough(
            User::class,
            DataGuru::class,
            'id',      // DataGuru.id (foreign key di data_ekskul)
            'id',      // User.id
            'ekskul_id', // DataEkskul.ekskul_id
            'user_id'  // DataGuru.user_id
        );
    }

    /**
     * Hitung jumlah siswa yang ikut ekskul ini
     */
    public function getJumlahSiswaAttribute()
    {
        return DataSiswa::where('ekskul', $this->nama_ekskul)->count();
    }
}

