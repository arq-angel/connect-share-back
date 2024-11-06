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
    public function getSiblingDepartments()
    {
        $facilityIds = $this->facilities()->pluck('facility_id')->toArray();

        return Department::whereHas('facilities', function ($query) use ($facilityIds) {
            $query->whereIn('facility_id', $facilityIds);
        })
            ->where(function ($query) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', $this->parent_id);
            })
            ->where('id', '!=', $this->id)
            ->select('id', 'name', 'short_name')
            ->get();
    }

    public function childrenJobTitles(): HasMany
    {
        return $this->hasMany(JobTitle::class, 'manager_id')
            ->where('directory_flag', true);
    }
}
