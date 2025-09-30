<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataKelas;
use App\Models\DataKejuruan;
use App\Models\JadwalGuru;
use App\Models\JadwalPiket;
use App\Models\DataMapel;
use App\Models\jamMengajar;
use App\Models\User;

class DataGuru extends Model
{
    use HasFactory;

    protected $table = 'data_guru';
    protected $fillable = ['kode','user_id'];

    /**
     * Relasi ke User (setiap guru terhubung ke satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke kelas yang diawal waliKelas
     */
    public function kelas()
    {
        return $this->hasMany(DataKelas::class, 'walas', 'id');
        // walas = foreign key di tabel data_kelas, kode = local key
    }

    /**
     * Relasi ke kejuruan yang diawal sebagai kaprog
     */
    public function kejuruan()
    {
        return $this->hasMany(DataKejuruan::class, 'kaprog', 'kode');
        // kaprog = foreign key di tabel program_kejuruan, kode = local key
    }

    /**
     * Relasi ke JadwalPiket
     */
    public function jadwalPiket()
    {
        return $this->hasMany(JadwalPiket::class, 'petugas', 'id');
        // petugas = foreign key di tabel jadwal_piket, id = primary key data_guru
    }

    /**
     * Relasi ke JadwalGuru
     */
    public function jadwalGuru()
    {
        return $this->hasMany(JadwalGuru::class, 'guru_id', 'id');
    }

    /**
     * Relasi ke data_mapel
     */
    public function mapel()
    {
        return $this->hasMany(DataMapel::class, 'guru_id', 'id');
    }

    /**
     * Ambil jadwal piket guru untuk hari ini
     */
    public function piketHariIni()
    {
        $hariIni = \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->dayName;
        return $this->jadwalPiket()->where('hari', $hariIni);
    }

    public function jamMengajar()
    {
        return $this->hasMany(\App\Models\JamMengajar::class, 'guru_id', 'id');
    }

    public function hakAkses()
    {
        return $this->hasOne(HakAkses::class, 'guru_id');
    }

}
