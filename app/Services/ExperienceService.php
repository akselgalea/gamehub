<?php
namespace App\Services;

use App\Models\{UserExperience};
use Exception;

class ExperienceService
{

    private $exp;

    public function __construct(UserExperience $exp) {
        $this->exp = $exp;
    }

    public function addUserExperience($user, $instance, $amount) {
        if ($amount == 0) return false;

        try {
            $userExperience = $this->exp->firstOrNew(['user_id' => $user, 'game_instance_id' => $instance]);
            $userExperience->amount += $amount;
            $userExperience->save();

            return true;
        } catch(Exception $e) {
            return false;
        }
    }
}