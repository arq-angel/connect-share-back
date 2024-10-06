<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAssignment extends Model
{
    use HasFactory;

    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function facility() : BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle() : BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }
}
