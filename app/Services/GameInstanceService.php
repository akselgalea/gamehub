<?php

namespace App\Services;

use App\Services\EncryptService;
use App\Models\{GameInstance, GameInstanceScore, GameInstanceTime, GameInstanceTimeCounter, GameInstanceParameter, GameInstanceExercise, Experiment, SurveyResponse, User, Game};
use Illuminate\Support\Facades\Auth;
use Exception;

class GameInstanceService
{
    private $encryptService;
    private $instance;
    private $instanceScore;
    private $instanceTime;
    private $instanceTimeCounter;
    private $instanceParameter;
    private $instanceExercise;
    const DEFAULT_TIME_PER_DAY = 90000;

    public function __construct(
        EncryptService $es,
        GameInstance $gi,
        GameInstanceScore $gis,
        GameInstanceTime $git,
        GameInstanceTimeCounter $gitc,
        GameInstanceParameter $gitp,
        GameInstanceExercise $gie,

    ) {
        $this->encryptService = $es;
        $this->instance = $gi;
        $this->instanceScore = $gis;
        $this->instanceTime = $git;
        $this->instanceTimeCounter = $gitc;
        $this->instanceParameter = $gitp;
        $this->instanceExercise = $gie;
    }

    public function get($slug) {
       return $this->instance->findBySlug($slug);
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            $this->instance->create($validated);

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
            $game_instance = $this->instance->findOrFail($req->id)->delete();
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

    public function updateParams($slug, $req) {
        try {
            $game_instance = $this->get($slug);

            if(!$game_instance)
                return $this->notFoundText();
            
            foreach($req->parameters as $param){
                if($param['value'] !== NULL)
                    $game_instance->gameInstanceParameters()->updateOrCreate(
                        ['parameter_id' => $param['id'], 'game_instance_id' => $game_instance->id],
                        ['value' => $param['value']]
                    );
                else
                    $this->instanceParameter->where(['parameter_id' => $param['id'], 'game_instance_id' => $game_instance->id])->delete();
            }

            return ['status' => 200, 'message' => 'Parámetros actualizados con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function updateGamification($req, $slug) {
        $validated = $req->validated();
        try {
            $game_instance = GameInstance::findOrFail($slug);
            $game_instance->update($validated);
            
            return ['status' => 200, 'message' => 'Gamificacion actualizada con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function initGameData($request)
    {
        $user = Auth::user();
        $data = $request->all();

        try {
            // Cargar instancia de juego        
            $game_instance_slug = $this->encryptService->decrypt(urldecode($data['t']));
            $game_instance = $this->instance->findBySlug($game_instance_slug);
    
            // Recupera puntaje máximo
            $max_score = $this->instanceScore->userMaxScore($user->id, $game_instance->id)->max_score ?? 0;
    
            // Recupera tiempo restante
            $instance_time = $this->instanceTime->timeLeft($user->id, $game_instance->id)->remaining_time ?? self::DEFAULT_TIME_PER_DAY;
    
            // Rescate de parámetros
            $parameters = $game_instance->gameInstanceParameters()->get();
            $parameters_json = $this->parametersToJson($parameters);
            $time_limit = $game_instance->experiment->time_limit;
    
            $time_counter = $this->instanceTimeCounter->userInstanceTimeCounter($user->id, $game_instance->id)->time_used ?? 0;
            # Carga de total de ejercicios
            $total_exercises_count = $this->instanceExercise->userTotalExercises($user->id, $game_instance->id);
            
            $res = $this->setJsonData($user->name, $max_score, $time_counter, $game_instance, $time_limit, $total_exercises_count, $parameters_json);
            return response()->json($res);
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveData(Request $request) {
        $user = Auth::user();
        $game_data = $request->input('game');
        $exercise_list = $request->input('exercises');

        # Comprueba si hay presencia de token
        if (isset($game_data['token'])) {
            $game_instance_slug = $this->encryptService->decrypt(urldecode($game_data['token']));
            $game_instance = $this->instance->findBySlug($game_instance_slug);

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

    private function setJsonData($user, $max_score, $time_counter, $game_instance, $time_limit, $total_exercises_count, $parameters_json) {
        $arr = [
            'u' => [
                # Parámetros de juego
                'name' => $user,                    # Nombre de usuario
                'fullname' => $user,                # Nombre completo del usuario
                'username' => $user,                # Nombre de usuario
                'highScore' => 100, #$max_score,          # Puntaje máximo (record) de juego

                # Parámetros de gamificación
                'currency' => 10, #$currencyAmount,  # Cantidad de monedas actual
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
            'int' => 'intval',
            'float' => 'floatval',
            'boolean' => 'boolval',
            'string' => 'strval'
        ];

        return call_user_func($types[$type], $value);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'No se ha encontrado la instancia de juego'];
    }

    public function surveyIsPending($experiment, $survey) {
        if(!$survey)
            return false;

        $response = $survey->getUserResponse(Auth()->user()->id);

        if(!$response || $response->status === 'in progress')
            return true;

        return false;
    }

    public function redirectToSurvey($experiment, $survey) {
        return redirect()->route('surveys.run', [$experiment, $survey]);
    }

    public function hasPendingSurvey($experiment) {
        $exp = Experiment::findOrFail($experiment);
        // Encuestas pre experimento
        $survey = $exp->surveys()->prePlay()->first();
        
        if($this->surveyIsPending($experiment, $survey))
            return $this->redirectToSurvey($experiment, $survey->id);
            
        // Encuestas programadas por fecha
        $survey = $exp->surveys()->activeByDate(now()->toDateTimeString())->first();

        if($this->surveyIsPending($experiment, $survey))
            return $this->redirectToSurvey($experiment, $survey->id);

        // Encuestas post experimento
        $survey = $exp->surveys()->postPlay()->first();

        if($this->surveyIsPending($experiment, $survey))
            return $this->redirectToSurvey($experiment, $survey->id);
        
        return false;
    }

    public function selectInstance($experiment) {
        $user = Auth::user();

        try {
            // $survey = $this->hasPendingSurvey($experiment);

            // if($survey)
            //     return $survey;

            $instance = $this->getUserGameInstance($experiment);

            if(!$instance)
                return redirect()->route('dashboard')->with('notification', ['status' => 404, 'message' => 'Este experimento no tiene instancias de juego']);

            return redirect()->route('game_instances.play', [
                $instance->slug,
                $instance->game->slug,
                't' => $this->encryptService->encrypt($instance->slug)
            ]);
        } catch(Exception $e) {
            return redirect()->back()->with('notification', ['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function getUserGameInstance($experiment, $userId = null) {
        if($userId)
            $user = User::findOrFail($userId);
        else
            $user = Auth()->user();
        $userInstance = $user->getInstanceByExperiment($experiment);

        if(!$userInstance)
            $instance = $this->instance->getInstanceWithLeastUsers($experiment);
            
        if(isset($instance) && $instance) {
            $experimentUser = $user->experimentUser()->updateOrCreate(['experiment_id' => $experiment], ['game_instance_id' => $instance->id]);
            $userInstance = $experimentUser->gameInstance;
        }
           
        return $userInstance;
    }

    public function play($request) {
        $instanceSlug = $request->segment(3);
        $token = $request->input('t');

        $instance = $this->get($instanceSlug);
        
        if(empty($instance))
            return $this->notFoundText();

        $game = $instance->game;

        if(empty($game)) 
            return ['status' => 404, 'message' => 'No se ha encontrado el juego'];
        
        $filename = json_decode($game->extra)->filename;
        
        $location = "/game-instances/$instanceSlug/$game->slug/$filename.js";
        
        return ['game' => $game, 'location' => $location];
    }
}