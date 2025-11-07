<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom 'kode' ke tabel data_mapel.
     */
    public function up(): void
    {
        Schema::table('data_mapel', function (Blueprint $table) {
            // Tambahkan kolom 'kode' di depan kolom 'mapel'
            $table->string('kode', 20)->unique()->after('id')->nullable();
        });
    }

    /**
     * Hapus kolom 'kode' jika migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('data_mapel', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
