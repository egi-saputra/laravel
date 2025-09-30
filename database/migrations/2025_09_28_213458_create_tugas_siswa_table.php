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
        Schema::create('tugas_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('idx_user_id');
            $table->string('judul')->nullable();
            $table->string('nama')->nullable();
            $table->string('kelompok')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable()->index('idx_kelas_id');
            $table->unsignedBigInteger('mapel_id')->nullable()->index('idx_mapel_id');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_siswa');
    }
};
