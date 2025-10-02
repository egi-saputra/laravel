<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_siswa', function (Blueprint $table) {
            $table->enum('jabatan_siswa', ['Tidak Ada', 'Sekretaris', 'Bendahara'])
                  ->default('Tidak Ada')
                  ->after('kejuruan_id');
        });
    }

    public function down(): void
    {
        Schema::table('data_siswa', function (Blueprint $table) {
            $table->dropColumn('jabatan');
        });
    }
};
