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
        Schema::table('presensi_guru', function (Blueprint $table) {
            $table->foreign(['jadwal_id'])->references(['id'])->on('jadwal_guru')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_guru', function (Blueprint $table) {
            $table->dropForeign('presensi_guru_jadwal_id_foreign');
            $table->dropForeign('presensi_guru_user_id_foreign');
        });
    }
};
