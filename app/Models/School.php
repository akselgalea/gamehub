<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Exception;

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

    public function add($req)
    {
        $validated = $req->validated();

        try {
            School::create($validated);
            return ['status' => 200, 'message' => 'Colegio añadido con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function erase($req) {
        try {
            $school = School::findOrFail($req->id);

            if($req->name == $school->name)
                $school->delete();
            else throw new Exception('El nombre no coincide'); 

            return ['status' => 200, 'message' => 'Colegio eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
