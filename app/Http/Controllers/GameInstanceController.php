<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\GameInstances\{GameInstanceCreateRequest, GameInstanceUpdateRequest, GameInstanceDeleteRequest};
use App\Models\{ GameInstance, Game };
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameInstanceController extends Controller
{
    private $game_instance;
    
    public function __construct(GameInstance $game_instance) {
        $this->game_instance = $game_instance;
    }

    // public function index($id)
    // {
    //     return Inertia::render('Admin/Experiments/Management/Entrypoints/Index', ['entrypoints' => EntryPoint::all()->toArray(), 'experiment_id' => $id]);
    // }

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
        return Inertia::render('Admin/Experiments/Management/ExperimentInstances/Partials/UpdateGameInstanceForm', 
            ['game_instance' => $game_instance, 'games' => Game::all()->toArray(), 'experiment_id' => $game_instance->experiment_id]);
    }

    public function update($id, GameInstanceUpdateRequest $request)
    {
        $res = $this->game_instance->find($id)->edit($request);
        return redirect()->back()->with('notification', $res);  // Te redirecciona al panel de gestion de experimento
    }

    public function destroy(GameInstanceDeleteRequest $request) {
        $res = $this->game_instance->erase($request);
        return redirect()->back()->with('notification', $res);
    }
}
