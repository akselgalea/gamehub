<?php

namespace App\Services;
use App\Services\EncryptService;
use App\Models\{GameInstance, GameInstanceScore, GameInstanceTime, GameInstanceTimeCounter, GameInstanceParameter, GameInstanceExercise};

class GameInstanceService
{
    private $encryptService;
    private $gameInstance;
    private $gameInstanceScore;
    private $gameInstanceTime;
    private $gameInstanceTimeCounter;
    private $gameInstanceParameter;
    private $gameInstanceExercise;
    const DEFAULT_TIME_PER_DAY = 90000;

    public function __construct(
        EncryptService $es,
        GameInstance $gi,
        GameInstanceScore $gis,
        GameInstanceTime $git,
        GameInstanceTimeCounter $gitc,
        GameInstanceParameter $gitp,
        GameInstanceExercise $gie
    ) {
        $this->encryptService = $es;
        $this->gameInstance = $gi;
        $this->gameInstanceScore = $gis;
        $this->gameInstanceTime = $git;
        $this->gameInstanceTimeCounter = $gitc;
        $this->gameInstanceParameter = $gitp;
        $this->gameInstanceExercise = $gie;
    }

    public function get($slug) {
       return $this->gameInstance->findBySlug($slug);
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            $this->gameInstance->create($validated);

            return ['status' => 200, 'message' => 'Instancia de juego creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($slug, $req) {
        $validated = $req->validated();

        try {
            $instance = $this->get($slug);
            
            if(!$instance)
                return $this->notFoundText();
            
            $instance->update($validated);

            return ['slug' => $instance->slug, 'status' => 200, 'message' => 'Datos de la instancia actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function delete($req) {
        try {
            $game_instance = GameInstance::findOrFail($req->id)->delete();
            return ['status' => 200, 'message' => 'Instancia de juego eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Ha ocurrido un error al eliminar la instancia.'];
        }
    }

    public function getParams($slug) {
        try {
            $instance = $this->get($slug); // busca los datos de la instancia
            
            if(!$instance)
                return $this->notFoundText();

            $gameParameters = $instance->game->parameters()->orderBy('id')->get();
            $instanceParameters = $instance->gameInstanceParameters()->orderBy('parameter_id')->get();
            
            foreach($gameParameters as $param) {
                $param['value'] =  $instanceParameters->firstWhere('parameter_id', $param['id'])->value ?? '';
            }

            return ['parameters' => $gameParameters, 'instance_id' => $instance->id];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function updateParameters($id, $req) {
        try {
            $game_instance = $this->gameInstance->findOrFail($id);
        
            foreach($req->parameters as $param){
                if($param['value'] !== NULL)
                    $game_instance->gameInstanceParameters()->updateOrCreate(
                        ['parameter_id' => $param['id'], 'game_instance_id' => $id],
                        ['value' => $param['value']]
                    );
                else
                    GameInstanceParameter::where(['parameter_id' => $param['id'], 'game_instance_id' => $id])->delete();
            }

            return ['status' => 200, 'message' => 'Instancia de juego creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function initGameData($request)
    {
        $user = Auth::user();
        $data = $request->all();

        // Cargar instancia de juego        
        $game_instance_slug = $this->encryptService->decrypt(urldecode($data['t']));
        $game_instance = $this->gameInstance->findBySlug($game_instance_slug);

        // Recupera puntaje máximo
        $instance_score = $this->gameInstanceScore->userMaxScore($user->id, $game_instance->id)->max_score ?? 0;

        // Recupera tiempo restante
        $instance_time = $this->gameInstanceTime->timeLeft($user->id, $game_instance->id)->remaining_time ?? self::DEFAULT_TIME_PER_DAY;

        // Rescate de parámetros
        $parameters = $game_instance->gameInstanceParameters()->get();
        $parameters_json = $this->parametersToJson($parameters);
        $time_limit = $game_instance->experiment->time_limit;

        # Carga de tags de tests
        /*  $tests = TestExercise::where('$user->id', '=', $user->id)
                ->where('event', 2)
                ->select(DB::raw('DISTINCT(test) as test, (SELECT label FROM test_exercises WHERE $user->id = ' . $user->id . ' ORDER BY created_at DESC, time_start DESC, id DESC LIMIT 1) as last_label'))
                ->get();

        # Carga medallas adquiridas por el usuario
        $badgeAcquired = UserGameBadge::leftjoin('game_badges', 'user_game_badges.game_badge_id', 'game_badges.id')
            ->where('user_game_badges.$user->id', $user->id)
            ->where('user_game_badges.game_id', $game_instance->game->id)
            ->select(DB::raw('game_badges.code as code'))
            ->distinct('code')
            ->get();

        $badge_list = [];
        foreach ($badgeAcquired as $badgeAcquiredItem) {
            $badgeItem = [];
            $badgeItem['name'] = $badgeAcquiredItem->code;
            $badge_list[] = $badgeItem;
        }
        $arr['u']['badges'] = $badge_list;
        */

        # Carga de monedas actual
        #$currencyAmount = $currencyService->getUserAmount($user->id, $game_instance->id);
        $time_counter = $this->gameInstanceTimeCounter->userInstanceTimeCounter($user->id, $game_instance->id)->time_used ?? 0;
        # Carga de total de ejercicios
        $total_exercises_count = $this->gameInstanceExercise->userTotalExercises($user->id, $game_instance->id);

        return $this->setJsonData($user, $max_score, $time_counter, $game_instance, $time_limit);
    }

    public function saveData(Request $request) {
        $user = Auth::user();
        $game_data = $request->input('game');
        $exercise_list = $request->input('exercises');

        # Comprueba si hay presencia de token
        if (isset($game_data['token'])) {
            $game_instance_slug = $this->encryptService->decrypt(urldecode($game_data['token']));
            $game_instance = GameInstance::findBySlug($game_instance_slug);
           
            $user_experiment = UserExperiment::where('game_instance_id', $game_instance->id)
                ->where('user_id', $user->id)
                ->first();
            $expected_advance = Experiment::find($user_experiment->experiment_id)->surveys
                ->whereBetween('type', [3, 4])     // Tipo de encuesta programada por ambos
                ->where('responses_expected', '>=', $user_experiment->actual_responses)
                ->sortBy('responses_expected')
                ->first();

            // Agrega experiencia al usuario
            if (isset($game_data['experience'])) {
                $user_experience = $experienceService->addUserAmount($user->id, $game_instance->id, $game_data['experience']);
            }

            // Agrega registro de tiempo
            if (isset($game_data['time_used'])) {

                // Recupera instancia de tiempo
                $instance_time = GameInstanceTimeCounter::where('user_id', $user->id)
                    ->where('game_instance_id', $game_instance->id)
                    ->where('date', \Carbon\Carbon::now()->toDateString())
                    ->first();

                if (!empty($instance_time)) {
                    // Edita puntaje de instancia de tiempo existente
                    $instance_time->time_used = $instance_time->time_used + $game_data['time_used'];
                } else {
                    // Crea primera instancia de tiempo
                    $instance_time = new GameInstanceTimeCounter();
                    $instance_time->date = \Carbon\Carbon::now();
                    $instance_time->time_used = $game_data['time_used'];
                    $instance_time->game_instance_id = $game_instance->id;
                    $instance_time->user_id = $user->id;
                }
                $instance_time->save();
            }

            // Agregar puntaje máximo
            if (isset($game_data['max_score'])) {
                $instance_score = GameInstanceScore::where('user_id', $user->id)
                    ->where('game_instance_id', $game_instance->id)
                    ->first();

                if ($instance_score) {
                    // Edita puntaje de instancia de puntaje existente
                    // Solo si el puntaje actual es mayor al anterior
                    if ($instance_score->max_score < $game_data['max_score']) {
                        $instance_score->max_score = $game_data['max_score'];
                        $instance_score->save();
                    }
                } else {
                    // Crea primera instancia de puntaje
                    $instance_score = new GameInstanceScore();
                    $instance_score->max_score = $game_data['max_score'];
                    $instance_score->game_instance_id = $game_instance->id;
                    $instance_score->user_id = $user->id;
                    $instance_score->save();
                }
            }

            // Almacena monedas, si es test, tiene currency, tiene un evento 3
            // ** Utilizado para entregar monedas al terminar un test (in-game) **
            if (isset($game_data['add_currency'])) {
                // Realiza transaccion de monto
                $user_currency = $currencyService->addUserAmount($user->id, $game_instance->id, $game_data['add_currency']);
            }

            # Si no definió test, graba ejercicio
            if (!isset($game_data['test'])) {
				
                foreach ($exercise_list as $exercise_item) {
                    # [FIX] Corrige entrada de eventos de modo string
                    if (is_string($exercise_item)) {
                        $exercise_item = json_decode($exercise_item, true);
                    }
                    # Registra evento de ejercicio
                    $this->record_game_exercise($game_instance, $user->id, $exercise_item, $game_data);
                }
            } else {

                # Si definió test, graba en ejercicio de test
                foreach ($exercise_list as $exercise_item) {

                    # [FIX] Corrige entrada de eventos de modo string
                    if (is_string($exercise_item)) {
                        $exercise_item = json_decode($exercise_item, true);
                    }

                    # Registra evento de test
                    $this->record_test_exercise($game_instance, $user->id, $exercise_item, $game_data);
                }
            }




            $badge_list = $request->input('badges');

            if (isset($badge_list)) {

                foreach ($badge_list as $badge_item) {

                    # [FIX] Corrige entrada de eventos de modo string
                    if (is_string($badge_item)) {
                        $badge_item = json_decode($badge_item, true);
                    }

                    # Registra evento de medalla
                    $this->record_badge($game_instance->game->id, $user->id, $badge_list);
                }
            }


	
			$user_experiment = UserExperiment::where('game_instance_id', $game_instance->id)
                ->where('user_id', $user->id)
                ->first();
            $json = [
                'result' => 0
            ];
            if (!empty($expected_advance)) {
                $upped_value = $user_experiment->actual_responses >= $expected_advance->responses_expected;
            } else {
                $upped_value = false;
            }
            if (isset($game_data['time_used'])) {
                $json['game'] = [
                    'time_used' => $instance_time->time_used,
                    'timeout' => ($instance_time->time_used >= $game_instance->experiment->time_limit)
                ];
            } 
            
            $json['game']['complete'] = $upped_value;

            return response()->json($json);
        } else {
            return response()->json([
                'result' => -1,
                'message' => 'Invalid token'
            ]);
        }
    }

    private function setJsonData($user, $max_score, $time_counter, $game_instance, $time_limit) {
        $arr = [
            'u' => [
                # Parámetros de juego
                'name' => $user->name,              # Nombre de usuario
                'fullname' => $user->name,          # Nombre completo del usuario
                'username' => $user->name,          # Nombre de usuario
                'max_score' => $max_score,          # Puntaje máximo (record) de juego

                # Parámetros de gamificación
                'currency' => 0, #$currencyAmount,  # Cantidad de monedas actual
                'badges' => [],                     # Arreglo de medallas de usuario

                # Parámetros de tiempo
                'time_used' => $time_counter,      # Tiempo usado
                'time_limit' => $time_limit,       # Tiempo límite del día
                'time_left' => (3600 * 24),        # (deprecated) Tiempo restante
                'total_exercises' => $total_exercises_count,
                # Parámetros de test
                #'tests' => $tests->toArray(),      # Arreglo de tests realizados
            ],
            'p' => $parameters_json
        ];
        # FIX: Corrige retorno de arreglo cuando corresponde a diccionario
        # PHP considera arreglo vació como arreglo, no hay forma de definir diccionaro (arr. asosciativo)
        if (count($arr['p']) == 0) {
            $arr['p'] = json_decode("{}");
        }

        return $arr;
    }

    private function parametersToJson($parameters) {
        $json = array();
        
        foreach($parameters as $param) {
            $json[$param->name] = $this->castedTypeValue($param->type, $param->value);
        }

        return $json;
    }

    private function castedTypeValue($type, $value) {
        $types = [
            'integer' => 'intval',
            'float' => 'floatval',
            'boolean' => 'boolval',
            'string' => 'strval'
        ];

        return call_user_func($types[$param->type], $value);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'No se ha encontrado la instancia de juego'];
    }
}