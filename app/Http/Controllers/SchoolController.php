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

    public function get($id) {
        $res = $this->school->get();
    }

    public function create() {
        return Inertia::render('Admin/Schools/Create', ['schools' => $this->school->all()]);
    }

    public function store(SchoolCreateRequest $request) {
        $res = $this->school->add($request);
        return redirect()->back()->with('notificatio', $res);
    }

    public function edit($id) {
        $school = $this->school->find($id);
        return Inertia::render('Admin/Schools/Edit', ['school' => $school]);
    }

    public function update() {
        $res = $this->school->edit($id, $request);
        return redirect()->route('schools.edit', $id)->with('notification', $res);
    }

    public function destroy(Request $request) {
        $res = $this->school->erase($request);
        return redirect()->route('schools.index')->with('notification', $res);
    }
}
