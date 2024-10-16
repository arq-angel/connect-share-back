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
        return JobTitle::where('department_id', $this->department_id) // Same department
        ->whereNull('manager_id') // No manager_id
        ->where('id', '!=', $this->id) // Exclude current job title
        ->get(); // Get the result
    }
}
