<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function facilityEmployees() : HasMany
    {
        return $this->hasMany(FacilityEmployee::class);
    }
}
