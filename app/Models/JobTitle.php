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
}
