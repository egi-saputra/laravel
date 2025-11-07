<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMapel extends Model
{
    use HasFactory;

    protected $table = 'data_mapel';

    // Tambahkan 'kode' ke fillable agar bisa diisi mass-assignment
    protected $fillable = ['kode', 'mapel', 'guru_id'];

    /**
     * Relasi ke DataGuru (guru pengampu)
     */
    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'guru_id', 'id');
    }
}
