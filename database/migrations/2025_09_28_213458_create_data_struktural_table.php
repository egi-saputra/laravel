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
        Schema::create('data_struktural', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('jabatan');
            $table->unsignedBigInteger('nama_gtk')->nullable()->index('data_struktural_nama_gtk_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_struktural');
    }
};
