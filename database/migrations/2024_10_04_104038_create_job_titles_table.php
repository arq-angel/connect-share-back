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
        Schema::create('job_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->text('image')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('job_titles')->onDelete('cascade');
            $table->timestamps();
//            $table->softDeletes();

            // Indexes for performance
            $table->index('company_id'); // Frequently used for filtering by company
            $table->index('title'); // Indexing for faster lookups on job titles

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_titles');
    }
};
