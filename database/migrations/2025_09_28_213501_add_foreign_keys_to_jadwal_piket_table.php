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
        Schema::table('jadwal_piket', function (Blueprint $table) {
            $table->foreign(['petugas'])->references(['id'])->on('data_guru')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_piket', function (Blueprint $table) {
            $table->dropForeign('jadwal_piket_petugas_foreign');
        });
    }
};
