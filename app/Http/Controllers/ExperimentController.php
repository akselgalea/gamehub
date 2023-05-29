<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\{ExperimentCreateRequest, ExperimentUpdateRequest};
use App\Models\Experiment;
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

    // Panel para gestionar un experimento //

    public function experimentManagement($id) {
        $experiment = Experiment::find($id);
        return Inertia::render('Admin/Experiments/Management/ExperimentManagement', ['experiment' => $experiment, 'users' => $experiment->users->toArray()]);
    }

    // public function experimentUser($id) {

    //     $experiment = Experiment::find($id);
    //     $users = $experiment->users->toArray();
    //     dd($users);

    //     return $experiment_user;
    // }

    // Panel referente a la informacion general de un experimento //

    public function generalInformationEdit($id) {
        return Inertia::render('Admin/Experiments/Edit', ['experiment' => Experiment::find($id)]);
    }

    public function generalInformationUpdate($id, ExperimentUpdateRequest $request) {
        $res = $this->experiment->find($id)->edit($request);
        // return redirect()->route('experiment_information.edit', $id)->with('notification', $res);   // Te redirecciona al formulario ya modificado
        return redirect()->route('experiment.management', $id)->with('notification', $res);  // Te redirecciona al panel de gestion de experimento
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
