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
        Schema::table('program_kejuruan', function (Blueprint $table) {
            $table->foreign(['kaprog_id'])->references(['id'])->on('data_guru')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kejuruan', function (Blueprint $table) {
            $table->dropForeign('program_kejuruan_kaprog_id_foreign');
        });
    }
};
