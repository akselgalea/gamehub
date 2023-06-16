<?php

namespace App\Services;

use App\Models\Student;
use Exception;

class StudentService
{
    private $student;

    public function __construct(Student $s) {
        $this->student = $s;
    }

    public function get($id) {
        return $this->student->find($id);
    }

    public function schoolInfo($id) {
        try {
            $student = $this->get($id);

            if(empty($student))
                return $this->notFoundText();

            return response()->json(['school' => $student->school(), 'grade' => $student->grade]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function gradeUpdate($id, $req) {
        $validated = $req->validated();

        try {
            $student = $this->get($id);

            if(empty($student))
                return $this->notFoundText();

            $student->grade()->associate($validated['gradeId'])->save();
            return ['status' => 200, 'message' => 'Curso actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Estudiante no encontrado'];
    }
}