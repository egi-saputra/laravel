<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mapel_id');
            $table->foreign('mapel_id')->references('id')->on('data_mapel')->onDelete('cascade');

            $table->unsignedBigInteger('kelas_id');
            $table->foreign('kelas_id')->references('id')->on('data_kelas')->onDelete('cascade');

            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Tidak Aktif');
            $table->enum('tipe_soal', ['Berurutan', 'Acak'])->default('Berurutan');

            $table->integer('waktu')->comment('Waktu dalam menit atau detik');

            $table->string('token', 6)->unique(); // Token unik 6 digit

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
