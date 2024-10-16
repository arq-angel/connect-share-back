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

    public function jobTitles(): BelongsToMany
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
        // Get the facility IDs this department belongs to
        $facilityIds = $this->facilities()->pluck('facility_id')->toArray();

        // Find sibling departments that belong to the same facilities
        return Department::whereHas('facilities', function ($query) use ($facilityIds) {
            $query->whereIn('facility_id', $facilityIds); // Same facility_id
        })
            ->where(function ($query) {
                $query->whereNull('parent_id') // No parent_id
                ->orWhere('parent_id', $this->parent_id); // Same parent_id
            })
            ->where('id', '!=', $this->id) // Exclude current department
            ->select('id', 'name', 'short_name')
            ->get();
    }

    public function childrenDepartments()
    {
        // Retrieve all departments where this department is their parent
        return Department::where('parent_id', $this->id) // Look for departments where this department is the parent
        ->select('id', 'name', 'short_name') // Select only the necessary fields
        ->get();
    }
}
