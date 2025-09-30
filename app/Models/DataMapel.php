<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMapel extends Model
{
    use HasFactory;

    protected $table = 'data_mapel';
    protected $fillable = ['mapel', 'guru_id'];

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'guru_id', 'id');
    }

    // Relasi ke Mapel (anggap ada kolom mapel di data_mapel)
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id', 'id');
    }

}
