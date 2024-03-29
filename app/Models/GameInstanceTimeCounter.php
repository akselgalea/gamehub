<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class GameInstanceTimeCounter extends Model
{
    use HasFactory;

    protected $table = 'game_instance_time_counter';

    protected $fillable = [
        'date',
        'time_used',
        'user_id',
        'game_instance_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function gameInstance(): BelongsTo {
        return $this->belongsTo(GameInstance::class);
    }

    public function getUserInstanceTimeCounter($user, $instance) {
        return GameInstanceTimeCounter::where('game_instance_id', $instance)
            ->where('user_id', $user)
            ->where('date', \Carbon\Carbon::now()->toDateString())
            ->orderBy('date', 'DESC')
            ->first();
    }
}
