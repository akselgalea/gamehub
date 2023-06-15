<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\EntryPoints\{EntryPointCreateRequest, EntryPointUpdateRequest, EntryPointDeleteRequest};
use App\Services\{ExperimentService, EntryPointService, EncryptService};
use App\Models\EntryPoint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntryPointController extends Controller
{
    private $entrypoint;
    private $exp;
    
    public function __construct(EntryPointService $entrypoint, ExperimentService $e) {
        $this->entrypoint = $entrypoint;
        $this->exp = $e;
    }

    public function register($token) {
        //Mover al service
        $entry = $this->entrypoint->findByLink($token);
        
        if(!$entry)
            return redirect()->route('register');

        if(Auth()->check())
            return redirect()->route('game_instances.select_instance', $entry->experiment_id);

        return Inertia::render('Auth/Register', ['entrypoint' => $entry]);
    }

    public function index($id)
    {
        $experiment = $this->exp->get($id);
        if(!$experiment)
            return redirect()->back()->with('notication', $this->exp->notFoundText());

        $entrypoints = $experiment->entrypoints()->get();
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Index', ['entrypoints' => $entrypoints, 'experiment_id' => $id]);
    }

    public function create($id)
    {
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Create', ['experiment_id' => $id]);
    }

    public function store(EntryPointCreateRequest $request)
    {
        $res = $this->entrypoint->store($request);
    }

    public function show($id)
    {
        $experiment = $this->exp->get($id);
        if(!$experiment)
            return redirect()->back()->with('notication', $this->exp->notFoundText());
        
        $entrypoints = $experiment->entrypoints()->get();
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Edit', ['entrypoints' => $entrypoints, 'experiment_id' => $id]);
    }

    public function edit($id)
    {
        $entrypoint = EntryPoint::find($id);
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Partials/UpdateEntryPointForm', ['entrypoint' => $entrypoint, 'experiment_id' => $entrypoint->experiment_id]);
    }

    public function update($id, EntryPointUpdateRequest $request)
    {
        $res = $this->entrypoint->update($id, $request);
        return redirect()->back()->with('notification', $res);  // Te redirecciona al panel de gestion de experimento
    }

    public function destroy(EntryPointDeleteRequest $request) {
        $res = $this->entrypoint->erase($request);
        return redirect()->back()->with('notification', $res);
    }
}
