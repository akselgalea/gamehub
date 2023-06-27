<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\GameInstances\{GameInstanceCreateRequest, GameInstanceUpdateRequest, GameInstanceDeleteRequest};
use App\Http\Requests\Experiments\GameInstances\Gamification\{GamificationUpdateRequest};
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\{GameInstanceService, ExperimentService, GameService};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GameInstanceExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GameInstanceController extends Controller
{
    private $gis;
    private $exp;
    private $gs;
    
    public function __construct(GameInstanceService $gis, GameService $gs,ExperimentService $e) {
        $this->gis = $gis;
        $this->exp = $e;
        $this->gs = $gs;
    }

    public function show($id)
    {
        $experiment = $this->exp->get($id);
        
        if(!$experiment)
            return redirect()->back()->with('notication', $this->exp->notFoundText());
        
        $instances = $experiment->gameInstances()->with('game')->get();

        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Edit',
            ['games_instances' => $instances, 'experiment_id' => $id]
        );
    }

    public function create($id)
    {
        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Create',
            ['games' => $this->gs->getGm2Games(), 'experiment_id' => $id]
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
        
        $req = $this->gis->getParams($instance->slug);
        return Inertia::render(
            'Admin/Experiments/Management/ExperimentInstances/Partials/UpdateGameInstanceForm', 
            ['game_instance' => $instance, 'games' => $this->gs->getGm2Games(), 'parameters' => $req['parameters'], 'experiment_id' => $experimento]
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
            ['parameters' => $res['parameters'], 'experiment_id' => $experiment, 'slug' => $slug]
        );
    }
    
    public function updateParams($experiment, $slug, Request $request)
    {
        $res = $this->gis->updateParams($slug, $request);
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

    public function updateGamification(GamificationUpdateRequest $request, $slug)
    {
        $res = $this->gis->updateGamification($request, $slug);
        return redirect()->back()->with('notification', $res); 
    }
    
    public function initialParams(Request $request)
    {
        return $this->gis->initGameData($request);
    }

    public function saveData(Request $request) {
        return $this->gis->saveData($request);
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

    public function exportGameInstance($id) {
        $fileName = 'game_instance_exercises.xlsx';

        return Excel::download(new GameInstanceExport($id), $fileName);
    }
}
