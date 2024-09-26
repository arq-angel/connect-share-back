<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "firstName",
        "middleName",
        "lastName",
        "image",
        "email",
        "phone",
        "jobTitle",
        "department",
        "designation",
        "company",
        "dateOfBirth",
        "gender",
        "address",
        "suburb",
        "state",
        "postCode",
    ];

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
