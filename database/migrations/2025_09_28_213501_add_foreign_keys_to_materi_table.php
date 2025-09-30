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
        Schema::table('materi', function (Blueprint $table) {
            $table->foreign(['kelas_id'], 'fk_materi_kelas')->references(['id'])->on('data_kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['mapel_id'], 'fk_materi_mapel')->references(['id'])->on('data_mapel')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_materi_user')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropForeign('fk_materi_kelas');
            $table->dropForeign('fk_materi_mapel');
            $table->dropForeign('fk_materi_user');
        });
    }
};
