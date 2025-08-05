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
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 20);
            $table->string('course', 50);
            $table->string('day', 10);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('owner', 10);
            $table->string('tahun_ajar', 4);
            $table->boolean('is_final')->default(false);

            $table->foreign('owner')->references('nim')->on('assistants')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
