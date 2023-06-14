<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInstanceExercise extends Model
{
    use HasFactory;

    public $table = 'game_instance_exercises';

    public function userTotalExercises($user, $instance) {
        return GameInstanceExercise::where(['user_id' => $user, 'game_instance_id' => $instance])->count();
    }
}
