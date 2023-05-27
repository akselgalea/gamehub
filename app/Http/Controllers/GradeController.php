<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Http\Requests\Grades\{GradeCreateRequest, GradeUpdateRequest, GradeDeleteRequest};

class GradeController extends Controller
{
    private $grade;

    public function __construct(Grade $grade) {
        $this->grade = $grade;
    }

    public function index() {
        $res = $this->grade->index();
    }

    public function get($id) {
        $res = $this->grade->get();
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
