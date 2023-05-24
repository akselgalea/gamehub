<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    
    protected $fillable = [
        'name'
    ];

    /**
     * Get all of the grades for the School
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get all of the students for the School
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Grade::class);
    }
}
