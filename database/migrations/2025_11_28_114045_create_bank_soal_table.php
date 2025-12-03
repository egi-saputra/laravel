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
        Schema::create('bank_soal', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel soal (paket ujian)
            $table->foreignId('soal_id')
                  ->constrained('soal')
                  ->onDelete('cascade');

            $table->text('soal');

            $table->enum('tipe_soal', ['PG', 'Essay'])->default('PG');

            $table->enum('jenis_lampiran', ['Tanpa Lampiran', 'Gambar', 'Video'])
                  ->default('Tanpa Lampiran');

            $table->string('link_lampiran')->nullable();

            $table->text('jawaban_benar')->nullable();

            $table->text('opsi_a')->nullable();
            $table->text('opsi_b')->nullable();
            $table->text('opsi_c')->nullable();
            $table->text('opsi_d')->nullable();
            $table->text('opsi_e')->nullable();

            $table->integer('nilai')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal');
    }
};
