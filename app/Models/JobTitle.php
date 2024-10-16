<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobTitle extends Model
{
    use HasFactory;

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function departments() : BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_job_title'); // we needed to explicitly mention the table name
    }

    public function assignments() : HasMany
    {
        return $this->hasMany(EmployeeAssignment::class);
    }

    // A job title may have a direct manager
    public function manager()
    {
        return $this->belongsTo(JobTitle::class, 'manager_id');
    }

    // A job title may have many subordinates (those where this job title is their manager)
    public function subordinates()
    {
        return $this->hasMany(JobTitle::class, 'manager_id');
    }

    // Method to retrieve sibling job titles
    public function siblingJobTitles()
    {
        // Get the department IDs this job title belongs to
        $departmentIds = $this->departments()->pluck('department_id')->toArray();

        // Find sibling job titles that belong to the same departments
        return JobTitle::whereHas('departments', function($query) use ($departmentIds) {
            $query->whereIn('department_id', $departmentIds); // Same department_id via pivot
        })
            ->where('id', '!=', $this->id) // Exclude current job title
            ->where(function($query) {
                $query->whereNull('manager_id') // No manager_id
                ->orWhere('manager_id', $this->manager_id); // Same manager_id
            })
            ->select('id', 'title', 'short_title')
            ->get();
    }

    public function childrenJobTitles()
    {
        // Retrieve all job titles where this job title is their manager
        return JobTitle::where('manager_id', $this->id) // Look for job titles where this job is the manager
        ->select('id', 'title', 'short_title') // Select only the necessary fields
        ->get();
    }
}
