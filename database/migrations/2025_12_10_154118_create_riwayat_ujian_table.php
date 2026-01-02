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
        Schema::create('riwayat_ujian', function (Blueprint $table) {
            $table->id();

            // Siswa yang mengerjakan
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Mengambil ujian dari tabel soal
            $table->unsignedBigInteger('soal_id');
            $table->foreign('soal_id')
                ->references('id')
                ->on('soal')
                ->onDelete('cascade');

            // Soal per item dari tabel bank_soal
            $table->unsignedBigInteger('quest_id');
            $table->foreign('quest_id')
                ->references('id')
                ->on('bank_soal')
                ->onDelete('cascade');

            // Jawaban siswa
            $table->string('jawaban')->nullable();

            // Apakah jawaban benar?
            $table->boolean('benar')->default(false);

            // Nilai untuk soal tersebut
            $table->integer('nilai')->default(0);

            // Status pengerjaan
            $table->enum('status', [
                'Belum Dikerjakan',
                'Sedang Dikerjakan',
                'Selesai'
            ])->default('Belum Dikerjakan');

            // Waktu siswa menjawab item soal ini
            $table->dateTime('waktu_pengerjaan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_ujian');
    }
};
