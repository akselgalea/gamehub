<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\EntryPoints\{EntryPointCreateRequest, EntryPointUpdateRequest, EntryPointDeleteRequest};
use App\Models\EntryPoint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntryPointController extends Controller
{
    private $entrypoint;
    
    public function __construct(EntryPoint $entrypoint) {
        $this->entrypoint = $entrypoint;
    }

    public function index($id)
    {
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Index', ['entrypoints' => EntryPoint::all()->toArray(), 'experiment_id' => $id]);
    }

    public function create($id)
    {
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Create', ['experiment_id' => $id]);
    }

    public function store(EntryPointCreateRequest $request)
    {
        $res = $this->entrypoint->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function show($id)
    {
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Edit', ['entrypoints' => EntryPoint::all()->toArray(), 'experiment_id' => $id]);
    }

    public function edit($id)
    {
        $entrypoint = EntryPoint::find($id);
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Partials/UpdateEntryPointForm', ['entrypoint' => $entrypoint, 'experiment_id' => $entrypoint->experiment_id]);
    }

    public function update($id, EntryPointUpdateRequest $request)
    {
        $res = $this->entrypoint->find($id)->edit($request);
        return redirect()->back()->with('notification', $res);  // Te redirecciona al panel de gestion de experimento
    }

    public function destroy(EntryPointDeleteRequest $request) {
        $res = $this->entrypoint->erase($request);
        return redirect()->back()->with('notification', $res);
    }
}
