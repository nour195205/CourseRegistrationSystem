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
        Schema::create('allowed_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->unique(['course_id', 'department_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowed_departments');
    }
};
