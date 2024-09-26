<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "middle_name",
        "last_name",
        "image",
        "email",
        "phone",
        "job_title",
        "department",
        "designation",
        "company",
        "date_of_birth",
        "gender",
        "address",
        "suburb",
        "state",
        "postal_code",
    ];

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
