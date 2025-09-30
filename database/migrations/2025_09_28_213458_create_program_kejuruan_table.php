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
        Schema::create('program_kejuruan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 10)->unique();
            $table->string('nama_kejuruan', 100);
            $table->unsignedBigInteger('kaprog_id')->nullable()->index('program_kejuruan_kaprog_id_foreign');
            $table->timestamps();
            $table->unsignedInteger('jumlah_siswa')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kejuruan');
    }
};
