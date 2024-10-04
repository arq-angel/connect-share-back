<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function facilities() : HasMany
    {
        return $this->hasMany(Facility::class);
    }
}
