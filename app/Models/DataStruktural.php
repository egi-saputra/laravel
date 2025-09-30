<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataGuru;

class DataStruktural extends Model
{
    use HasFactory;

    protected $table = 'data_struktural';

    protected $fillable = [
        'jabatan',
        'nama_gtk', // kolom yang merujuk ke DataGuru.id
    ];

    /**
     * Relasi ke tabel DataGuru
     * kolom nama_gtk merujuk ke id di DataGuru
     */
    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'nama_gtk', 'id');
    }
}
