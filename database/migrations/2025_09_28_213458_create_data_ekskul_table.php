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
        Schema::create('data_ekskul', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_ekskul');
            $table->unsignedBigInteger('ekskul_id')->nullable()->index('data_ekskul_ekskul_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_ekskul');
    }
};
