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
        Schema::create('preferences', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('nim', 10);
            $table->string('kode_praktikum', 20);

            $table->foreign('nim')->references('nim')->on('assistants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kode_praktikum')->references('kode_praktikum')->on('practicums')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
