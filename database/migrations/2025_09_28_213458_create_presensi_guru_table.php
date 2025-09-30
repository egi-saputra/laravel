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
        Schema::create('presensi_guru', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('presensi_guru_user_id_foreign');
            $table->tinyInteger('tanggal');
            $table->tinyInteger('bulan');
            $table->smallInteger('tahun');
            $table->unsignedBigInteger('jadwal_id')->nullable()->index('presensi_guru_jadwal_id_foreign');
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->enum('keterangan', ['Hadir', 'Tidak Hadir', 'Sakit', 'Izin', 'Tanpa Keterangan'])->default('Tidak Hadir');
            $table->enum('apel', ['Apel', 'Tidak', 'Pembina Apel'])->default('Tidak');
            $table->enum('upacara', ['Upacara', 'Pembina Upacara', 'Tidak'])->default('Tidak');
            $table->timestamps();
            $table->boolean('presensi_selesai')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_guru');
    }
};
