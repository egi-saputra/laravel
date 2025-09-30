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
        Schema::table('data_kelas', function (Blueprint $table) {
            $table->foreign(['walas_id'])->references(['id'])->on('data_guru')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_kelas', function (Blueprint $table) {
            $table->dropForeign('data_kelas_walas_id_foreign');
        });
    }
};
