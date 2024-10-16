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
        $statuses = getStatuses(request: 'status')['keys'];  // here request: 'status' is redundant because it is the default in the function
        $defaultStatus = getStatuses(request: 'status')['default'];

        Schema::create('job_titles', function (Blueprint $table) use ($statuses, $defaultStatus) { // need to add $statuses because it's a closure and it has limited scope
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->text('image')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('job_titles')->onDelete('cascade');
            $table->enum('status', $statuses)->default($defaultStatus);
            $table->boolean('directory_flag')->default(false);
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
