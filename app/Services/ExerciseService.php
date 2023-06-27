<?php
namespace App\Services;

use App\Models\{GameInstanceExercise};
use Exception;
use Carbon\Carbon;

class ExerciseService
{
    private $exercise;

    public function __construct(GameInstanceExercise $exercise) {
        $this->exercise = $exercise;
    }

    public function getUserTotalExercises($user, $instance) {
        return $this->exercise->getUserTotalExercises($user, $instance);
    }

    public function saveGameExercises($user, $instance, $list) {
        foreach ($list as $item) {
            if (is_string($item)) {
                $item = json_decode($item, true);
            }
            # Registra evento de ejercicio
            if(!$this->recordGameExercise($instance, $user, $item))
                return false;
        }

        return true;
    }

    public function recordGameExercise($game_instance, $user_id, $item)
    {
        try {
            $gexercise = new GameInstanceExercise();
            $gexercise->game_instance_id = $game_instance;
            $gexercise->user_id = $user_id;
    
            if ($item->event == 1) {
                // (1): Evento de inicio de partida
                $gexercise->event = 1;
                $gexercise->round = $item->round;
                $gexercise->time_start = $item->timeStart;
            } else if ($item->event == 2) {
                $add_up_userexp = 
                // (2): Evento de ejercicio
                $gexercise->event = 2;
                $gexercise->round = $item->round;
                $gexercise->exercise = $item->exercise;
                $gexercise->response = $item->response;
                $gexercise->user_response = $item->userResponse;
            } else if ($item->event == 3) {
    
                // (3): Evento de término de partida
                $gexercise->event = 3;
                $gexercise->round = $item->round;
            } else if ($item->event == 4) {
    
                // (4): Evento de salida inesperada del navegador
                $gexercise->event = 4;
                $gexercise->round = $item->round;
            }
    
            # Si define 'score' lo almacena
            $gexercise->score = $item->score ?? null;
    
            # Si define 'lives' lo almacena
            $gexercise->lives = $item->lives ?? null;
    
    
            # Si define 'timeStart' con formato correcto lo almacena
            if (isset($item->timeStart) && strtotime(str_replace('/', '-', $item->timeStart)) != false) {
                $gexercise->time_start = Carbon::createFromFormat('d/m/Y H:i:s', $item->timeStart)->toDateTimeString();
            }
    
            # Si define 'timeEnd' con formato correcto, sino almacena 'timeStart'
            if (isset($item->timeEnd) && strtotime(str_replace('/', '-', $item->timeEnd)) != false) {
                $gexercise->time_end = Carbon::createFromFormat('d/m/Y H:i:s', $item->timeEnd)->toDateTimeString();
            } else {
                $gexercise->time_end = Carbon::createFromFormat('d/m/Y H:i:s', $item->timeStart)->toDateTimeString();
            }
    
            #Si define 'extras' con formato correcto lo almacena
            if(isset($item->extras))
                $gexercise->extra = json_encode($item->extras);
    
            $gexercise->save();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}