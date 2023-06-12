<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\GameInstances\{GameInstanceCreateRequest, GameInstanceUpdateRequest, GameInstanceDeleteRequest};
use App\Http\Requests\Experiments\GameInstances\Gamification\{GamificationUpdateRequest};
use App\Models\{ GameInstance, Game, GameInstanceParameter, Parameter };
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameInstanceController extends Controller
{
    private $game_instance;
    
    public function __construct(GameInstance $game_instance) {
        $this->game_instance = $game_instance;
    }

    public function show($id)
    {
        return Inertia::render('Admin/Experiments/Management/ExperimentInstances/Edit', ['games_instances' => GameInstance::all()->toArray(), 'games' => Game::all()->toArray(), 'experiment_id' => $id]);
    }

    public function create($id)
    {
        return Inertia::render('Admin/Experiments/Management/ExperimentInstances/Create', ['games' => Game::all()->toArray(), 'experiment_id' => $id]);
    }

    public function store(GameInstanceCreateRequest $request)
    {
        $res = $this->game_instance->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function edit($id)
    {
        $game_instance = GameInstance::find($id);

        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Partials/UpdateGameInstanceForm', 
            ['game_instance' => $game_instance, 'games' => Game::all()->toArray(), 'experiment_id' => $game_instance->experiment_id]
        );
    }

    // Vista que permite modificar los valores de los parametros pertenecientes al juego de la instancia de experimento //
    public function editParams($id) {
        // devuelve la vista con el arreglo de parametros, ademas del id de la instancia y experimento 
        $res = $this->game_instance->editParams($id);
        
        if(isset($res['status']) && $res['status'] == 500)
            return redirect()->back()->with('notification', $res); 

        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Parameters/EditParamForm', 
            ['parameters' => $res['parameters'], 'experiment_id' => $res['experiment_id'], 'instance_id' => $id]
        );
    }
    
    public function updateParams(Request $request, $id)
    {
        $res = $this->game_instance->find($id)->updateParams($request, $id);
        return redirect()->back()->with('notification', $res); 
    }

    public function editGamification($id)
    {
        $game_instance = GameInstance::find($id);
        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Partials/UpdateGamificationForm', 
            ['game_instance' => $game_instance, 'experiment_id' => $game_instance->experiment_id]
        );
    }

    public function updateGamification(GamificationUpdateRequest $request, $id)
    {
        $res = $this->game_instance->updateGamification($request, $id);
        return redirect()->back()->with('notification', $res); 
    }

    public function update($id, GameInstanceUpdateRequest $request)
    {
        $res = $this->game_instance->find($id)->edit($request);
        return redirect()->back()->with('notification', $res); 
    }

    public function destroy(GameInstanceDeleteRequest $request) {
        $res = $this->game_instance->erase($request);
        return redirect()->back()->with('notification', $res);
    }
}
