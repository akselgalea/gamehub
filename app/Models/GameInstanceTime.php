<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInstanceTime extends Model
{
    use HasFactory;

    public $table = 'game_instance_times';

    // Recupera tiempo restante
    public function timeLeft($user, $instance) {
        return GameInstanceTime::where('user_id', $user)
            ->where('game_instance_id',  $instance)
            ->where('date', \Carbon\Carbon::now()->toDateString())
            ->first();
    }
}
