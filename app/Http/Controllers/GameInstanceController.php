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
        return $this->gis->initGameData($request);
    }

    public function selectInstance($experiment) {
        return $this->gis->selectInstance($experiment);
    }

    public function play(Request $request) {
        $res = $this->gis->play($request);

        if(isset($res['status']))
            return redirect()->back()->with('notification', $res);

        return Inertia::render('Games/PlayInstance', $res);
    }
}
