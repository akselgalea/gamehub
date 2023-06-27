<?php
namespace App\Services;

use App\Models\{GameInstanceTime};
use Exception;

class InstanceTimeService
{
    private $time;

    public function __construct(GameInstanceTime $time) {
        $this->time = $time;
    }

    public function getTimeUsed($user, $instance) {
        return $this->time->getUserInstanceTime($user, $instance);
    }

    public function updateTime($user, $instance, $amount) {
        try {
            $instanceTime = $this->time->getUserInstanceTime($user, $instance);
    
            if (!empty($instanceTime)) {
                // Edita puntaje de instancia de tiempo existente
                $instanceTime->remaining_time = $amount;
                $instanceTime->save();
            } else {
                // Crea primera instancia de tiempo
                $instanceTime = $this->time->create([
                    'date' => now(),
                    'remaining_time' => $amount,
                    'game_instance_id' => $instance,
                    'user_id' => $user
                ]);
            }
    
            return $instanceTime;
        } catch (Exception $e) {
            return false;
        }
    }
}