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
        $statuses = getStatuses(request: 'status')['keys'];
        $defaultStatus = getStatuses(request: 'status')['default'];

        Schema::create('departments', function (Blueprint $table) use ($statuses, $defaultStatus) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('image')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->enum('status', $statuses)->default($defaultStatus);
            $table->boolean('directory_flag')->default(true);
            $table->timestamps();
//            $table->softDeletes();

            // Indexes for performance
            $table->index('company_id'); // Index for filtering by company
            $table->index('name'); // Index for faster lookups on department names
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
