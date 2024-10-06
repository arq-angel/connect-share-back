<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'address',
        'suburb',
        'state',
        'postal_code',
        'country',
        'email',
        'phone',
        'website',
        'industry',
        'size',
        'established_date',
    ];

    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function facilities() : HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function departments() : HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function jobTitles() : HasMany
    {
        return $this->hasMany(JobTitle::class);
    }

}
