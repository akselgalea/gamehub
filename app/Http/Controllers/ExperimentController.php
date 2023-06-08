<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\{ExperimentCreateRequest, ExperimentUpdateRequest};
use App\Http\Requests\Experiments\Users\{UserAssociateRequest, UserDisassociateRequest};
use App\Models\{Experiment, User, Game};
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\UserInExperiment;


class ExperimentController extends Controller
{
    private $experiment;
    
    public function __construct(Experiment $experiment) {
        $this->experiment = $experiment;
    }

    public function index() {
        return Inertia::render('Admin/Experiments/Index', ['experiments' => Experiment::all()->toArray()]);
    }

    public function create()
    {
        return Inertia::render('Admin/Experiments/Create');
    }

    public function store(ExperimentCreateRequest $request) {
        $res = $this->experiment->store($request);
        return redirect()->route('experiments.create')->with('notification', $res);
    }

    // Panel para gestionar un experimento
    public function experimentManagement($id) {
        $experiment = Experiment::find($id);
        
        return Inertia::render('Admin/Experiments/View', [
            'experiment' => $experiment,
            'users' => $experiment->students->toArray(),
            'entrypoints' => $experiment->entrypoints->toArray(),
            'games_instances' => $experiment->gameInstances->toArray(),
            'games' => Game::all()->toArray(),
            'surveys' => $experiment->surveys->toArray()
        ]);
    }

    public function generalInformationEdit($id) {
        return Inertia::render('Admin/Experiments/Edit', ['experiment' => Experiment::find($id)]);
    }

    public function generalInformationUpdate($id, ExperimentUpdateRequest $request) {
        $res = $this->experiment->find($id)->edit($request);
        // return redirect()->route('experiment_information.edit', $id)->with('notification', $res);   // Te redirecciona al formulario ya modificado
        return redirect()->route('experiment.management', $id)->with('notification', $res);  // Te redirecciona al panel de gestion de experimento
    }

    // Panel referente a la administracion de usuarios asociados a un experimento //
    public function usersExperiment($id) {
        return $this->experiment->usersExperiment($id);
    }

    public function userAssociateExperiment(UserAssociateRequest $request){
        $this->experiment->userAssociateExperiment($request);
        // return redirect()->route('users_experiment.edit', $id)->with('notification');
        return redirect()->back()->with('notification');
    }

    public function userDisassociateExperiment(UserDisassociateRequest $request){
        $this->experiment->userDisassociateExperiment($request);
        // return redirect()->route('users_experiment.edit', $id)->with('notification');
        return redirect()->back()->with('notification');
    }

    public function show(Experiment $experiment)
    {
        //
    }

    public function edit(Experiment $experiment)
    {
        //
    }

    public function update(Request $request, Experiment $experiment)
    {
        //
    }

    public function destroy(Experiment $experiment)
    {
        //
    }
}
