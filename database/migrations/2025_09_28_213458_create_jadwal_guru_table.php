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
        Schema::create('jadwal_guru', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hari');
            $table->string('sesi')->nullable();
            $table->string('jam_mulai', 10);
            $table->string('jam_selesai', 10);
            $table->unsignedBigInteger('guru_id')->nullable()->index('jadwal_guru_guru_id_foreign');
            $table->unsignedBigInteger('kelas_id')->nullable()->index('jadwal_guru_kelas_id_foreign');
            $table->integer('jumlah_jam')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_guru');
    }
};
