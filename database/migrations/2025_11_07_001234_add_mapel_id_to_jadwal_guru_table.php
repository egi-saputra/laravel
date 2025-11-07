<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('jadwal_guru', function (Blueprint $table) {
            // Tambahkan kolom mapel_id (nullable supaya kompatibel dengan data lama)
            $table->unsignedBigInteger('mapel_id')->nullable()->after('kelas_id');

            // Tambahkan relasi ke tabel data_mapel
            $table->foreign('mapel_id')
                  ->references('id')
                  ->on('data_mapel')
                  ->onDelete('set null');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('jadwal_guru', function (Blueprint $table) {
            $table->dropForeign(['mapel_id']);
            $table->dropColumn('mapel_id');
        });
    }
};
