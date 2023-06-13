<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schools\{SchoolCreateRequest, SchoolUpdateRequest, SchoolDeleteRequest};
use Illuminate\Http\Request;
use App\Models\School;
use Inertia\Inertia;

class SchoolController extends Controller
{
    private $school;

    public function __construct(School $school) {
        $this->school = $school;
    }

    public function index() {
        return Inertia::render('Admin/Schools/Index', ['schools' => $this->school->all()]);
    }

    public function getAll() {
        return $this->school->with('grades')->get();
    }

    public function grades($id) {
        return $this->school->find($id)->grades();
    }

    public function get($id) {
        return null;
    }

    public function create() {
        return Inertia::render('Admin/Schools/Create', ['schools' => $this->school->all()]);
    }

    public function store(SchoolCreateRequest $request) {
        $res = $this->school->add($request);
        return redirect()->back()->with('notification', $res);
    }

    public function edit($slug) {
        $school = $this->school->findBySlug($slug);
        if(empty($school))
            return redirect()->back()->with('notification', ['status' => 404, 'message' => 'No se encontró el colegio']);

        $grades = $school->grades->toArray();
        return Inertia::render('Admin/Schools/Edit', ['school' => $school, 'grades' => $grades]);
    }

    public function update($id, SchoolUpdateRequest $request) {
        $res = $this->school->edit($id, $request);
        return redirect()->route('schools.edit', $id)->with('notification', $res);
    }

    public function destroy(SchoolDeleteRequest $request) {
        $res = $this->school->erase($request);
        return redirect()->route('schools.index')->with('notification', $res);
    }
}
