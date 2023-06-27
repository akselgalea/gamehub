<?php
namespace App\Services;

use App\Models\{GameInstanceScore};
use Exception;

class ScoreService
{
    private $score;

    public function __construct(GameInstanceScore $score) {
        $this->score = $score;
    }

    public function getUserMaxScore($user, $instance) {
        return $this->score->getUserMaxScore($user, $instance);
    }

    public function updateMaxScore($user, $instance, $amount) {
        try {
            $maxScore = $this->score->getUserMaxScore($user, $instance);
    
            if (!empty($maxScore)) {
                if($maxScore->max_score < $amount)
                    $maxScore->update(['max_score' => $amount]);
            } else {
                $maxScore = $this->score->create([
                    'max_score' => $amount,
                    'game_instance_id' => $instance,
                    'user_id' => $user
                ]);
            }
    
            return $maxScore;
        } catch (Exception $e) {
            return false;
        }
    }
}