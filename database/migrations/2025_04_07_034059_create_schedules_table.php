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
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 20);
            $table->string('kode_praktikum', 20);
            $table->uuid('laboratorium_id');
            $table->string('dosen', 50);
            $table->string('tahun_ajar', 4);
            $table->string('day', 10);
            $table->time('start_time');
            $table->time('end_time');

            $table->foreign('kode_praktikum')->references('kode_praktikum')->on('practicums')->onDelete('cascade');
            $table->foreign('laboratorium_id')->references('id')->on('laboratoriums')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
