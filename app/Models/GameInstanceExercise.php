<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInstanceExercise extends Model
{
    use HasFactory;

    public $table = 'game_instance_exercises';

    public $fillable = [
        'event',
        'round',
        'exercise',
        'user_response',
        'response',
        'score',
        'lives',
        'time_start',
        'time_end',
        'extra',
        'user_id',
        'game_instance_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'event' => 'integer',
        'round' => 'integer',
        'exercise' => 'string',
        'user_response' => 'string',
        'response' => 'string',
        'score'=> 'integer',
        'lives'=> 'integer',
    ];

    protected $dates = ['deleted_at'];

    public function getUserTotalExercises($user, $instance) {
        return GameInstanceExercise::where(['user_id' => $user, 'game_instance_id' => $instance])->count();
    }
}
