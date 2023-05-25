<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\ExperimentCreateRequest;
use App\Models\Experiment;
use Illuminate\Http\Request;
use Inertia\Inertia;


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
