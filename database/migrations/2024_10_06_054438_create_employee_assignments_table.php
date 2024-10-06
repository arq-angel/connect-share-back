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
        Schema::create('employee_assignments', function (Blueprint $table) {
            $table->id();

            // Foreign key relationships
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_title_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null'); // Nullable department

            // Additional columns
            $table->date('hire_date')->nullable();
            $table->date('start_date')->nullable(); // Start date of the current role
            $table->date('end_date')->nullable();   // End date of the current role
            $table->string('system_user_id')->unique(); // Unique system user ID for tracking

            // Tracking salary and contract type
//            $table->decimal('salary', 8, 2)->nullable(); // Optional salary field
            $table->enum('contract_type', ['permanent', 'contract'])->default('permanent'); // Contract type

            // Status of the employee
            $table->enum('status', ['active', 'on_leave', 'terminated'])->default('active');

            // Created and updated by user tracking (for auditing purposes)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            // Timestamps and soft deletes
            $table->timestamps();
//            $table->softDeletes();

            // Optional: Composite unique constraint for employee, facility, job title, department
            $table->unique(['employee_id', 'facility_id', 'job_title_id', 'department_id'], 'emp_facility_job_dept_unique');

            // Index for better query performance
            $table->index(['facility_id', 'department_id', 'job_title_id'], 'facility_dept_job_idx');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assignments');
    }
};
