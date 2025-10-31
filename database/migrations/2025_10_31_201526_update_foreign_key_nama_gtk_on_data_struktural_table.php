<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: ubah relasi nama_gtk ke tabel users.
     */
    public function up(): void
    {
        Schema::table('data_struktural', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['nama_gtk']);

            // Tambah foreign key baru ke tabel users
            $table->foreign('nama_gtk')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Balikkan perubahan (rollback): relasi ke data_guru lagi.
     */
    public function down(): void
    {
        Schema::table('data_struktural', function (Blueprint $table) {
            // Hapus foreign key ke users
            $table->dropForeign(['nama_gtk']);

            // Kembalikan foreign key ke data_guru
            $table->foreign('nama_gtk')
                  ->references('id')
                  ->on('data_guru')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }
};
