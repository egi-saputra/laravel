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
        Schema::table('data_siswa', function (Blueprint $table) {
            $table->foreign(['kejuruan_id'], 'data_siswa_id_kejuruan_foreign')->references(['id'])->on('program_kejuruan')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['user_id'], 'data_siswa_users_id_foreign')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['kelas_id'], 'siswa_id_kelas_foreign')->references(['id'])->on('data_kelas')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_siswa', function (Blueprint $table) {
            $table->dropForeign('data_siswa_id_kejuruan_foreign');
            $table->dropForeign('data_siswa_users_id_foreign');
            $table->dropForeign('siswa_id_kelas_foreign');
        });
    }
};
