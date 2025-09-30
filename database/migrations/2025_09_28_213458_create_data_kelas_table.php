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
        Schema::create('data_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 5)->unique('kelas_kode_unique');
            $table->string('kelas');
            $table->unsignedBigInteger('walas_id')->nullable()->index('data_kelas_walas_id_foreign');
            $table->integer('jumlah_siswa')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kelas');
    }
};
