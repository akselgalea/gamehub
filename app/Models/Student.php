<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Student extends User
{
    use HasParent;

    /**
     * Get the grade that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    
    public function grade(): BelongsTo {
        return $this->belongsTo(Grade::class);
    }

    public function experiments(): BelongsToMany {
        return $this->belongsToMany(Experiment::class, 'experiment_user', 'user_id' , 'experiment_id');
    }

    public function school() {
        $grade = $this->grade;

        if($grade)
            return $grade->school;
        
        return null;
    }
}
