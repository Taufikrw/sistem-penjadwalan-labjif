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
        Schema::create('assistants', function (Blueprint $table) {
            $table->string('nim', 10)->unique()->primary();
            $table->string('name', 50);
            $table->string('prodi', 20);
            $table->year('angkatan');
            $table->year('tahun_masuk');
            $table->uuid('user_id');
            $table->string('foto')->nullable();
            $table->string('nomor_telp', 15)->nullable();
            $table->string('status', 10)->default('aktif');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistants');
    }
};
