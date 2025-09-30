<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataGuru;
use App\Models\DataSiswa;
use App\Models\User;

class DataKejuruan extends Model
{
    use HasFactory;

    protected $table = 'program_kejuruan';
    protected $fillable = ['kode', 'nama_kejuruan', 'kaprog_id'];

    /**
     * Relasi ke DataGuru sebagai Kepala Program
     * kaprog_id -> data_guru.id
     */
    public function kepalaProgram()
    {
        return $this->belongsTo(DataGuru::class, 'kaprog_id', 'id');
    }

    /**
     * Relasi ke User dari kepala program
     */
    public function kaprogUser()
    {
        return $this->hasOneThrough(
            User::class,
            DataGuru::class,
            'id',       // foreign key DataGuru.id
            'id',       // foreign key User.id
            'kaprog_id',// local key DataKejuruan.kaprog_id
            'user_id'   // local key DataGuru.user_id
        );
    }

    /**
     * Relasi ke DataSiswa
     */
    public function siswa()
    {
        return $this->hasMany(DataSiswa::class, 'kejuruan_id', 'id');
    }
}
