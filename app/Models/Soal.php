<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'mapel_id',
        'kelas_id',
        'status',
        'tipe_soal',
        'waktu',
        'token',
    ];

    // Generate token 6 digit angka unik otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->token) {
                do {
                    $token = random_int(100000, 999999); // Token numerik 6 digit
                } while (self::where('token', $token)->exists());

                $model->token = $token;
            }
        });
    }

    /**
     * Relasi ke Mapel (Many to One)
     */
    public function mapel()
    {
        return $this->belongsTo(DataMapel::class, 'mapel_id');
    }

    /**
     * Relasi ke Kelas (Many to One)
     */
    public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'kelas_id');
    }

    /**
     * Relasi ke Bank Soal (One to Many)
     */
    public function bankSoal()
    {
        return $this->hasMany(BankSoal::class, 'soal_id');
    }
}
