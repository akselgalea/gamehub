<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\GameInstances\{GameInstanceCreateRequest, GameInstanceUpdateRequest, GameInstanceDeleteRequest};
use App\Http\Requests\Experiments\GameInstances\Gamification\{GamificationUpdateRequest};
use App\Models\{ GameInstance, Game, GameInstanceParameter, Parameter };
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\GameInstanceService;

class GameInstanceController extends Controller
{
    private $game_instance;
    private $gis;
    
    public function __construct(GameInstance $game_instance, GameInstanceService $gis) {
        $this->game_instance = $game_instance;
        $this->gis = $gis;
    }

    public function show($id)
    {
        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Edit',
            ['games_instances' => GameInstance::all()->toArray(), 'games' => Game::all()->toArray(), 'experiment_id' => $id]
        );
    }

    public function create($id)
    {
        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Create',
            ['games' => Game::all()->toArray(), 'experiment_id' => $id]
        );
    }

    public function store(GameInstanceCreateRequest $request)
    {
        $res = $this->gis->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function edit($experimento, $slug)
    {
        $instance = $this->gis->get($slug);

        if(!$instance)
            return redirect()->back()->with('notification', $this->gis->notFoundText());

        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Partials/UpdateGameInstanceForm', 
            ['game_instance' => $instance, 'games' => Game::all()->toArray(), 'experiment_id' => $experimento]
        );
    }

    public function update($experiment, $slug, GameInstanceUpdateRequest $request)
    {
        $res = $this->gis->update($slug, $request);
        return redirect()->route('game_instances.edit', ['id' => $experiment, 'slug' => $res['slug'] ?? ''])->with('notification', $res);
    }

    public function destroy(GameInstanceDeleteRequest $request) {
        $res = $this->gis->delete($request);
        return redirect()->back()->with('notification', $res);
    }


    // Vista que permite modificar los valores de los parametros pertenecientes al juego de la instancia de experimento //
    public function editParams($experiment, $slug) {
        // devuelve la vista con el arreglo de parametros, ademas del id de la instancia y experimento 
        $res = $this->gis->getParams($slug);
        
        if(isset($res['status']))
            return redirect()->back()->with('notification', $res); 

        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Parameters/EditParamForm', 
            ['parameters' => $res['parameters'], 'experiment_id' => $experiment, 'instance_id' => $res['instance_id']]
        );
    }
    
    public function updateParams($id, Request $request)
    {
        $res = $this->gis->updateParams($request, $id);
        return redirect()->back()->with('notification', $res); 
    }

    public function editGamification($experiment, $slug)
    {
        $res = $this->gis->get($slug);
        
        if(isset($res['status']))
            return redirect()->back()->with('notification', $res);

        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Partials/UpdateGamificationForm', 
            ['game_instance' => $res, 'experiment_id' => $experiment]
        );
    }

    public function updateGamification(GamificationUpdateRequest $request, $id)
    {
        $res = $this->gis->updateGamification($id, $request);
        return redirect()->back()->with('notification', $res); 
    }

    
    public function initialParams(Request $request)
    {
        $gds = new GameInstanceService();
        return response()->json($gds->initGameData($request));
    }

    public function saveData() {
        $user = Auth::user();
        $user_id = $user->id;
        $game_data = $request->input('game');
        $exercise_list = $request->input('exercises');

        # Comprueba si hay presencia de token
        if (isset($game_data['token'])) {

            $game_instance_slug = (new EncryptService())->encrypt_decrypt('decrypt', urldecode($game_data['token']));
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
                $instance_time = GameInstanceTimeCounter::where('user_id', $user_id)
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
                    $instance_time->user_id = $user_id;
                }
                $instance_time->save();
            }

            // Agregar puntaje máximo
            if (isset($game_data['max_score'])) {
                $instance_score = GameInstanceScore::where('user_id', $user_id)
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
                    $instance_score->user_id = $user_id;
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
                    $this->record_game_exercise($game_instance, $user_id, $exercise_item, $game_data);
                }
            } else {

                # Si definió test, graba en ejercicio de test
                foreach ($exercise_list as $exercise_item) {

                    # [FIX] Corrige entrada de eventos de modo string
                    if (is_string($exercise_item)) {
                        $exercise_item = json_decode($exercise_item, true);
                    }

                    # Registra evento de test
                    $this->record_test_exercise($game_instance, $user_id, $exercise_item, $game_data);
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
                    $this->record_badge($game_instance->game->id, $user_id, $badge_list);
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
}
