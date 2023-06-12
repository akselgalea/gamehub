<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\GameInstances\{GameInstanceCreateRequest, GameInstanceUpdateRequest, GameInstanceDeleteRequest};
use App\Models\{ GameInstance, Game, GameInstanceParameter, Parameter };
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

    // Vista que permite modificar los valores de los parametros pertenecientes al juego de la instancia de experimento //
    public function editParams($id){
        $game_instance = GameInstance::find($id); // busca los datos de la instancia
        $game_instance_parameters = $game_instance->gameParameters; // rescata los parametros con valores que pertenecen a la instancia
        $game_parameters = Parameter::whereDoesntHave('gameInstance')->where('game_id', $game_instance->game_id)->get(); // rescata los parametros que no tengan valor en la instancia
        $combine_parameters = $this->combineParameters($game_instance_parameters, $game_parameters); // combina los dos arreglos de parametros anteriores en uno 
        return Inertia::render('Admin/Experiments/Management/ExperimentInstances/Parameters/EditParamForm', 
        ['parameters' => $combine_parameters, 'experiment_id' => $game_instance->experiment_id, 'instance_id' =>$id]); // devuelve la vista con el arreglo de parametros, ademas del id de la instancia y experimento 
    }

    // Crea un arreglo con los parametros que se encuentran en la instancia de experimento junto con los parametros que no se encuentran //
    // su proposito es tener un arreglo con todos los parametros, esto incluye parametros con valores pertenecientes a la instancia del experimento y los que no //
    public function combineParameters($firstsParameters, $secondsParameters) {
        $secondsParameters = $this->setNullValuesToPivot($secondsParameters);
        $combine_parameters = $firstsParameters->concat($secondsParameters)->values()->toArray();
        return $combine_parameters;
    }
    
    // Agrega el pivot.value a los parametros que no poseen valor en la instancia, con el fin de poder almacenar sus valores en el formulario junto con al resto //
    public function setNullValuesToPivot($parameters)
    {
        foreach ($parameters as &$param) {
            if (!isset($param['pivot'])) {
                $param['pivot'] = (object)['value' => null];
            }
        }

        return $parameters;
    }

    public function updateParams(Request $request, $id)
    {
        $res = $this->game_instance->find($id)->updateParams($request, $id);
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
