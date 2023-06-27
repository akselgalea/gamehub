<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GameInstanceTime extends Model
{
    use HasFactory;

    public $table = 'game_instance_times';

    // Recupera tiempo restante
    public function scopeUser(Builder $query, $user): void {
        $query->where('user_id', $user);
    }

    public function scopeInstance(Builder $query, $instance): void {
        $query->where('game_instance_id', $instance);
    }

    public function getUserInstanceTime($user, $instance) {
        return GameInstanceTime::where(['user_id' => $user, 'game_instance_id' => $instance])->first();
    }

    public function timeLeft($user, $instance) {
        return GameInstanceTime::where('user_id', $user)
            ->where('game_instance_id',  $instance)
            ->where('date', \Carbon\Carbon::now()->toDateString())
            ->first();
    }
}
