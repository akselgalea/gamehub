<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grades\{GradeCreateRequest, GradeUpdateRequest, GradeDeleteRequest};
use Illuminate\Http\Request;
use App\Models\Grade;
use Inertia\Inertia;

class GradeController extends Controller
{
    private $grade;

    public function __construct(Grade $grade) {
        $this->grade = $grade;
    }

    public function index() {
        return $this->grade->all();
    }

    public function get($id) {
        $grade = Grade::find($id);
        $school = $grade->school;
        $students = $grade->students;

        return Inertia::render('Admin/Schools/Grades/View', ['school' => $school, 'grade' => $grade, 'students' => $students]);
    }

    public function create() {

    }

    public function store(GradeCreateRequest $request) {
        $res = $this->grade->add($request);
        return redirect()->back()->with('notification', $res);
    }

    public function edit() {
        $res = $this->grade->get();
    }

    public function update($id, GradeUpdateRequest $request) {
        $res = $this->grade->edit($id, $request);
        return redirect()->back()->with('notification', $res);
    }

    public function destroy($id, GradeDeleteRequest $request) {
        $res = $this->grade->erase($id, $request);
        return redirect()->back()->with('notification', $res);
    }
}
