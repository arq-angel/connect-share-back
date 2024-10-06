<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobTitle extends Model
{
    use HasFactory;

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function assignments() : HasMany
    {
        return $this->hasMany(EmployeeAssignment::class);
    }
}
