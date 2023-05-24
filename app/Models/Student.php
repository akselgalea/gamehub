<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Student extends User
{
    use HasParent;

    /**
     * Get the grade that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }
}
