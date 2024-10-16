<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
      'company_id',
      'name',
      'short_name',
      'image',
      'parent_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class);
    }

    public function jobTitles() : BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'department_job_title');  // we needed to explicitly mention the table name
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EmployeeAssignment::class);
    }

    // A department may have many sub-departments
    public function subDepartments() // children departments
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    // A department may belong to a parent department
    public function parentDepartment()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    // Method to retrieve sibling job titles
    public function siblingDepartments()
    {
        return JobTitle::where('facility_id', $this->facility_id) // Same department
        ->whereNull('parent_id') // No manager_id
        ->where('id', '!=', $this->id) // Exclude current job title
        ->get(); // Get the result
    }
}
