<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'enable_rewards' => boolean,
        'enable_badges' => boolean,
        'enable_performance_chart' => boolean,
        'enable_leaderboard' => boolean
    ];

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }
}
