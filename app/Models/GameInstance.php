<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{belongsToMany, BelongsTo, HasMany};
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class GameInstance extends Model
{
    use HasFactory;

    protected $table = 'game_instances';
    protected $fillable = [
        'name',
        'description',
        'game_id',
        'experiment_id',
        'enable_rewards',
        'enable_badges',
        'enable_performance_chart',
        'enable_leaderboard'
    ];

    protected $cast = [
        'enable_rewards' => 'boolean',
        'enable_badges' => 'boolean',
        'enable_performance_chart' => 'boolean',
        'enable_leaderboard' => 'boolean'
    ];

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }

    public function gameInstanceParameters(): HasMany {
        return $this->hasMany(GameInstanceParameter::class);
    }

    public function parameters(): BelongsToMany {
        return $this->belongsToMany(Parameter::class, 'game_instance_parameters', 'game_instance_id', 'parameter_id');
    }

    public function edit($req) {

        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Datos de la instancia actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function store($req) {
        
        $validated = $req->validated();
        try {
            $game_instance = GameInstance::create($validated);

            return ['status' => 200, 'message' => 'Instancia de juego creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function erase($req) {

        try {
            $game_instance = GameInstance::findOrFail($req->id);
            $game_instance->delete();

            return ['status' => 200, 'message' => 'Instancia de juego eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Ha ocurrido un error al eliminar la instancia.'];
        }
    }

    public function editParams($id) {
        try {
            $game_instance = GameInstance::findOrFail($id); // busca los datos de la instancia
            $gameParameters = $game_instance->game->parameters()->orderBy('id')->get();
            $instanceParameters = $game_instance->gameInstanceParameters()->orderBy('parameter_id')->get();
            
            foreach($gameParameters as $param) {
                $param['value'] =  $instanceParameters->firstWhere('parameter_id', $param['id'])->value ?? '';
            }

            return ['parameters' => $gameParameters, 'experiment_id' => $game_instance->experiment_id, 'instance_id' => $id];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function updateParams($req, $id) {
        // $validated = $req->validated();
        try {
            $game_instance = GameInstance::findOrFail($id);
        
            foreach($req->parameters as $param){
                if($param['value'] !== NULL)
                    $game_instance->gameInstanceParameters()->updateOrCreate(
                        ['parameter_id' => $param['id'], 'game_instance_id' => $id],
                        ['value' => $param['value']]
                    );
                else
                    GameInstanceParameter::where(['parameter_id' => $param['id'], 'game_instance_id' => $id])->delete();
            }

            return ['status' => 200, 'message' => 'Instancia de juego creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function updateGamification($req, $id) {
        $validated = $req->validated();
        try {
            $game_instance = GameInstance::findOrFail($id);
            $game_instance->update($validated);
            
            return ['status' => 200, 'message' => 'Gamificacion actualizada con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
