<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentUser extends Model
{
    use HasFactory;

    protected $table = 'experiment_user';

    protected $fillable = [
        'user_id',
        'experiment_id',
        'game_instance_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }

    public function gameInstance(): BelongsTo {
        return $this->belongsTo(GameInstance::class);
    }

    public function games() {
        return $this->hasManyThrough(Game::class, GameInstance::class);
    }
}
