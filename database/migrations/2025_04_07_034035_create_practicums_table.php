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
        Schema::create('practicums', function (Blueprint $table) {
            $table->string('kode_praktikum', 20)->unique()->primary();
            $table->string('name', 20);
            $table->string('for_prodi', 20);
            $table->integer('semester');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practicums');
    }
};
