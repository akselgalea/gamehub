<?php

namespace App\Services;

use App\Services\{EncryptService, ExperienceService, TimeCounterService, ScoreService, ExerciseService};
use App\Models\{GameInstance, GameInstanceScore, GameInstanceTime, GameInstanceTimeCounter, GameInstanceParameter, GameInstanceExercise, Experiment, ExperimentUser, SurveyResponse, User, Game};
use Illuminate\Support\Facades\Auth;
use Exception;

class GameInstanceService
{
    private $encryptService;
    private $instance;
    private $instanceParameter;
    private $expService;
    private $timeCounterService;
    private $scoreService;
    private $exerService;
    const DEFAULT_TIME_PER_DAY = 90000;

    public function __construct(
        EncryptService $es,
        GameInstance $gi,
        GameInstanceTime $git,
        GameInstanceParameter $gitp,
        ExperienceService $expService,
        TimeCounterService $timeCounterService,
        ScoreService $scoreService,
        ExerciseService $exerService
    ) {
        $this->encryptService = $es;
        $this->instance = $gi;
        $this->instanceTime = $git;
        $this->instanceParameter = $gitp;
        $this->expService = $expService;
        $this->timeCounterService = $timeCounterService;
        $this->scoreService = $scoreService;
        $this->exerService = $exerService;
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

    public function selectInstance($experiment) {
        $user = Auth::user();

        try {
            $survey = $this->hasPendingSurvey($experiment);

            if($survey)
                return $survey;

            $instance = $this->getUserGameInstance($experiment);

            if(!$instance)
                return redirect()->route('dashboard')->with('notification', ['status' => 404, 'message' => 'Este experimento no tiene instancias de juego']);

            return redirect()->route('game_instances.play', [
                $instance->game->slug,
                $instance->slug,
                't' => $this->encryptService->encrypt($instance->slug)
            ]);
        } catch(Exception $e) {
            return redirect()->back()->with('notification', ['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function hasPendingSurvey($experiment) {
        $exp = Experiment::findOrFail($experiment);
        // Encuestas pre experimento
        $surveys = $exp->surveys()->prePlay()->get();

        foreach($surveys as $s)
            if($this->surveyIsPending($experiment, $s))
                return $s->type == 'test' ? $this->redirectToTest($experiment, $s->id) : $this->redirectToSurvey($experiment, $s->id);
        
            
        // Encuestas programadas por fecha
        $surveys = $exp->surveys()->activeByDate(now()->toDateTimeString())->get();

        foreach($surveys as $s)
            if($this->surveyIsPending($experiment, $s))
                return $s->type == 'test' ? $this->redirectToTest($experiment, $s->id) : $this->redirectToSurvey($experiment, $s->id);

        // Encuestas post experimento
        $surveys = $exp->surveys()->postPlay()->get();

        foreach($surveys as $s)
            if($this->surveyIsPending($experiment, $s))
                return $s->type == 'test' ? $this->redirectToTest($experiment, $s->id) : $this->redirectToSurvey($experiment, $s->id);
        
        return false;
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

    public function redirectToTest($experiment, $survey) {
        return redirect()->route('surveys.tests.run', [$experiment, $survey]);
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
        $instanceSlug = $request->instance;
        $instance = $this->get($instanceSlug);
        
        if(empty($instance))
            return $this->notFoundText();

        $game = $instance->game;
        

        if(empty($game)) 
            return ['status' => 404, 'message' => 'No se ha encontrado el juego'];
        
        $filename = json_decode($game->extra)->filename;
        
        $location = "/game-instances/$game->slug/$instanceSlug/$filename.js";
        
        return ['game' => $game, 'location' => $location, 't' => $this->encryptService->encrypt($instanceSlug)];
    }

    public function initGameData($request)
    {
        #Temporalmente se envían los datos por defecto
        #return response()->json($this->defaultParameters());
      
        try {
            $user = Auth::user();
            $data = $request->all();

            // Cargar instancia de juego        
            $game_instance_slug = $this->encryptService->decrypt(urldecode($data['t']));
            $game_instance = $this->instance->findBySlug($game_instance_slug);
    
            // Rescate de parámetros
            $parameters = $game_instance->gameInstanceParameters()->get();
            $parameters_json = $this->parametersToJson($parameters);
            
            $res = $this->setJsonData($user, $game_instance, $parameters_json);


            return response()->json($res);
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveData($request) {
        $user = Auth::user();
        $game_data = $request->input('game');
        $exercise_list = $request->input('exercises');
        $exercise_list = is_string($exercise_list) ? json_decode($exercise_list) : $exercise_list;

        # Comprueba si hay presencia de token
        if(!isset($game_data['token']))
            return $this->saveDataErrorResponse('Invalid token');

        $game_instance_slug = $this->encryptService->decrypt(urldecode($game_data['token']));
        $game_instance = $this->instance->findBySlug($game_instance_slug);

        if(!$game_instance)
            return $this->saveDataErrorResponse('Invalid token');

        $response = [
            'result' => 0 #Resultado exitoso
        ];

        // Agrega experiencia al usuario
        if (isset($game_data['experience'])) {
            if(! $this->expService->addUserExperience($user->id, $game_instance->id, $game_data['experience'])) {
                return $this->saveDataErrorResponse('Error al guardar la experiencia del usuario');
            }
        }
        
        // Agrega registro de tiempo
        if (isset($game_data['time_used'])) {
            // Recupera instancia de tiempo
            $instanceTime = $this->timeCounterService->updateTimeCounter($user->id, $game_instance->id, $game_data['time_used']);

            if(!$instanceTime)
                return $this->saveDataErrorResponse('Error al actualizar el tiempo utilizado');

            $timeout = $instanceTime->time_used >= ($game_instance->experiment->time_limit * 60);
            
            $response['game'] = [
                'time_used' => $instanceTime->time_used,
                'timeout' =>  $timeout,
                'complete' => $timeout ?? false
            ];
        }

        // Agregar puntaje máximo
        if (isset($game_data['max_score'])) {
            if(!$this->scoreService->updateMaxScore($user->id, $game_instance->id, $game_data['max_score']))
                return $this->saveDataErrorResponse('Error al actualizar la puntuación máxima');
        }

        if (isset($exercise_list)) {
            if(!$this->exerService->saveGameExercises($user->id, $game_instance->id, $exercise_list))
                return $this->saveDataErrorResponse('Error al guardar los ejercicios');
        }

        return response()->json($response); 
    }

    private function defaultParameters(): array {
        return [
            'u' => [
                'name' => 'Demo 2.0.1',
                'fullname' => 'Demo 2.0.1',
                'username' => 'Demo 2.0.1',
                'max_score' => 500,
                'values' => [
                    'ataques' => 12,
                    'redes' => 0,
                    'derribos' => 5,
                    'capturas' => 0
                ],
                'time_left' => 90000,
                'time_user' => 0,
                'time_limit' => 10000
            ],
            'p' => []
        ];
    }

    private function setJsonData($user, $game_instance, $parameters_json) {
        // Recupera puntaje máximo
        $max_score = $this->scoreService->getUserMaxScore($user->id, $game_instance->id)->max_score ?? 0;
    
        // Recupera tiempo restante
        $time_limit = $game_instance->experiment->time_limit * 60;
        $instance_time = $this->instanceTime->timeLeft($user->id, $game_instance->id)->remaining_time ?? self::DEFAULT_TIME_PER_DAY;
    
        $time_counter = $this->timeCounterService->getTimeUsed($user->id, $game_instance->id) ?? 0;
        # Carga de total de ejercicios
        $total_exercises_count = $this->exerService->getUserTotalExercises($user->id, $game_instance->id);
        
        $arr = [
            'u' => [
                # Parámetros de juego
                'name' => $user->name,                    # Nombre de usuario
                'fullname' => $user->name,                # Nombre completo del usuario
                'username' => $user->name,                # Nombre de usuario
                'highScore' => $max_score,                # Puntaje máximo (record) de juego

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
    
    public function saveDataErrorResponse($message) {
        return response()->json([
            'result' => -1,
            'message' => $message
        ]);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'No se ha encontrado la instancia de juego'];
    }
}