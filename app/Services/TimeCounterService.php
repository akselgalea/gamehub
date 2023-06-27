<?php
namespace App\Services;

use App\Models\{GameInstanceTimeCounter};
use Exception;

class TimeCounterService
{
    private $counter;

    public function __construct(GameInstanceTimeCounter $counter) {
        $this->counter = $counter;
    }

    public function getTimeUsed($user, $instance) {
        return $this->counter->getUserInstanceTimeCounter($user, $instance);
    }

    public function updateTimeCounter($user, $instance, $amount) {
        try {
            $instanceTime = $this->counter->getUserInstanceTimeCounter($user, $instance);
    
            if (!empty($instanceTime)) {
                // Edita puntaje de instancia de tiempo existente
                $instanceTime->time_used += $amount;
                $instanceTime->save();
            } else {
                // Crea primera instancia de tiempo
                $instanceTime = $this->counter->create([
                    'date' => now(),
                    'time_used' => $amount,
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