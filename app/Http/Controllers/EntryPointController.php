<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\EntryPoints\{EntryPointCreateRequest, EntryPointUpdateRequest};
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

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        return Inertia::render('Admin/Experiments/Management/Entrypoints/Create', ['experiment_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntryPointCreateRequest $request)
    {
        $res = $this->entrypoint->store($request);
        return redirect()->back()->with('notification', $res);
    }

    /**
     * Display the specified resource.
     */
    public function show(EntryPoint $entryPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EntryPoint $entryPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, EntryPointUpdateRequest $request)
    {
        $res = $this->entrypoint->find($id)->edit($request);
        return redirect()->route('experiment.management', $id)->with('notification', $res);  // Te redirecciona al panel de gestion de experimento
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntryPoint $entryPoint)
    {
        //
    }
}
