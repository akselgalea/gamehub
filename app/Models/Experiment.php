<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasManyThrough, BelongsToMany, BelongsTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;
use App\Http\Requests\Experiments\Users\{UserAssociateRequest, UserDisassociateRequest};

class Experiment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'experiments';

    protected $fillable = [
        'name',
        'description',
        'status', // Detenido, Activo
        'time_limit', // In minutes
        'admin_id',
    ];

    public function creator(): BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function instances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function games(): HasManyThrough {
        return $this->hasManyThrough(Game::class, GameInstance::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'experiment_user' , 'experiment_id', 'user_id');
    }

    public function students(): BelongsToMany {
        return $this->belongsToMany(Student::class, 'experiment_user' , 'experiment_id', 'user_id');
    }

    public function entrypoints(): HasMany {
        return $this->hasMany(EntryPoint::class);
    }

    public function gameInstances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function surveys(): HasMany {
        return $this->hasMany(Survey::class);
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

    // Actualiza la informacion general de un experimento //
    public function edit($req) {
        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Datos del experimento actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Permite crear una asociacion entre un usuario y un experimento //
    public function userAssociateExperiment($req) {

        $validated = $req->validated();

        try {
            
            $user = User::find($validated['user_id']);
            $experiment = Experiment::find($validated['experiment_id']);

            $experiment->students()->attach($user);
            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Permite la desvinculacion de un usuario y un experimento //
    public function userDisassociateExperiment($req) {

        $validated = $req->validated();
 
        try {

            $user = User::find($validated['user_id']);
            $experiment = Experiment::find($validated['experiment_id']);

            $experiment->students()->detach($user);

            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}