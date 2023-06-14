<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInstanceScore extends Model
{
    use HasFactory;

    public $table = 'game_instance_scores';
    
    public function userMaxScore($user, $instance) {
        return GameInstanceScore::firstWhere(['user_id' => $user, 'game_instance_id' => $instance]);
    }
}
