<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('presensi_siswa', function (Blueprint $table) {
            $table->foreign(['siswa_id'], 'fk_presensi_siswa')->references(['id'])->on('data_siswa')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_presensi_user')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_siswa', function (Blueprint $table) {
            $table->dropForeign('fk_presensi_siswa');
            $table->dropForeign('fk_presensi_user');
        });
    }
};
