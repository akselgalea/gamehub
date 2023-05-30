<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;

class Experiment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'experiments';

    protected $fillable = [
        'admin_id',
        'name',
        'description',
        'status', // Detenido, Activo
        'time_limit' // In minutes
    ];

    public function instances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function games(): HasManyThrough {
        return $this->hasManyThrough(Game::class, GameInstance::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'experiment_user' , 'user_id', 'experiment_id');
    }

    public function entrypoints(): HasMany {
        return $this->hasMany(EntryPoint::class);
    }

    public function store($req) {
        
        $validated = $req->validated();
        try {
            $experiment = Experiment::create($validated);

            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Edita la informacion general de un experimento //
    
    public function edit($req) {
        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Datos del experimento actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}