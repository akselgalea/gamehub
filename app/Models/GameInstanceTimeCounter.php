<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInstanceTimeCounter extends Model
{
    use HasFactory;

    public function userInstanceTimeCounter($user, $instance) {
        return GameInstanceTimeCounter::where('game_instance_id', $instance)
            ->where('date', \Carbon\Carbon::now()->toDateString())
            ->where('user_id', $user)
            ->orderBy('date', 'DESC')
            ->first();
    }
}
