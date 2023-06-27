<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInstanceScore extends Model
{
    use HasFactory;

    public $table = 'game_instance_scores';

    public $fillable = [
        'max_score',
        'user_id',
        'game_instance_id'
    ];

    public function getUserMaxScore($user, $instance) {
        return GameInstanceScore::firstWhere(['user_id' => $user, 'game_instance_id' => $instance]);
    }
}
