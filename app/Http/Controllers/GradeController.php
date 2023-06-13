<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grades\{GradeCreateRequest, GradeUpdateRequest, GradeDeleteRequest};
use Illuminate\Http\Request;
use App\Services\GradeService;
use Inertia\Inertia;

class GradeController extends Controller
{
    private $gradeService;

    public function __construct(GradeService $grade) {
        $this->gradeService = $grade;
    }

    public function index() {
        return $this->gradeService->index();
    }

    public function get($school, $grade) {
        $res = $this->gradeService->get($grade);

        if(isset($res['status']))
            return redirect()->back()->with('notification', $res);

        return Inertia::render('Admin/Schools/Grades/View', ['school' => $res['school'], 'grade' => $res['grade'], 'students' => $res['students']]);
    }

    public function store(GradeCreateRequest $request) {
        $res = $this->gradeService->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function update($id, GradeUpdateRequest $request) {
        $res = $this->gradeService->update($id, $request);
        return redirect()->back()->with('notification', $res);
    }

    public function destroy($id, GradeDeleteRequest $request) {
        $res = $this->gradeService->delete($id, $request);
        return redirect()->back()->with('notification', $res);
    }
}
