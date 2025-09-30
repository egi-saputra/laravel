<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update jumlah siswa di setiap kelas
        DB::statement('
            UPDATE data_kelas dk
            SET dk.jumlah_siswa = (
                SELECT COUNT(*)
                FROM data_siswa ds
                WHERE ds.kelas_id = dk.id
            )
        ');

        // Update jumlah siswa di setiap program kejuruan
        DB::statement('
            UPDATE program_kejuruan pk
            SET pk.jumlah_siswa = (
                SELECT COUNT(*)
                FROM data_siswa ds
                WHERE ds.kejuruan_id = pk.id
            )
        ');
    }

    public function down(): void
    {
        // Reset jumlah siswa ke 0
        DB::table('data_kelas')->update(['jumlah_siswa' => 0]);
        DB::table('program_kejuruan')->update(['jumlah_siswa' => 0]);
    }
};
