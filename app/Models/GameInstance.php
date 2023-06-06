<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;

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
}
