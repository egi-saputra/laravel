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
        Schema::table('data_mapel', function (Blueprint $table) {
            $table->foreign(['guru_id'])->references(['id'])->on('data_guru')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_mapel', function (Blueprint $table) {
            $table->dropForeign('data_mapel_guru_id_foreign');
        });
    }
};
