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
        Schema::create('department_facility', function (Blueprint $table) {
            $table->id();

            // Foreign key to facilities table
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');

            // Foreign key to departments table
            $table->foreignId('department_id')->constrained()->onDelete('cascade');

            // Timestamps for tracking creation and updates
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_facility');
    }
};
