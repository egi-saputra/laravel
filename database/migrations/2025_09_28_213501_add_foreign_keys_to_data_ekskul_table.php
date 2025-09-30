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
        Schema::table('data_ekskul', function (Blueprint $table) {
            $table->foreign(['ekskul_id'])->references(['id'])->on('data_guru')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_ekskul', function (Blueprint $table) {
            $table->dropForeign('data_ekskul_ekskul_id_foreign');
        });
    }
};
