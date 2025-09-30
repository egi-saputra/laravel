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
        Schema::create('data_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('data_siswa_users_id_foreign');
            $table->string('status', 50)->nullable();
            $table->string('nama_lengkap', 191)->nullable();
            $table->string('tempat_tanggal_lahir')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('nis', 10)->nullable()->unique('siswa_nis_unique');
            $table->string('nisn', 50)->nullable()->unique('siswa_nisn_unique');
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('alamat')->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota_kabupaten')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable()->index('siswa_id_kelas_foreign');
            $table->unsignedBigInteger('kejuruan_id')->nullable()->index('data_siswa_id_kejuruan_foreign');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_siswa');
    }
};
