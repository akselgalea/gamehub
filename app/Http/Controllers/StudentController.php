<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\{StudentUpdateGradeRequest};
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    private $student;

    public function __construct(Student $student) {
        $this->student = $student;
    }

    public function schoolInfo($id) {
        return $this->student->schoolInfo($id);
    }

    public function gradeUpdate($id, StudentUpdateGradeRequest $request) {
        $res = $this->student->gradeUpdate($id, $request);
        return redirect()->back()->with('notification', $res);
    }
}
