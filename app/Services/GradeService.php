<?php

namespace App\Services;
use App\Models\Grade;

class GradeService
{

    private $grade;

    public function __construct(Grade $grade) {
        $this->grade = $grade;
    }

    function index() {
        return $this->grade->all();
    }

    function get($slug) {
        $grade = $this->grade->findBySlug($slug);
        
        if(empty($grade))
            return ['status' => 404, 'message' => 'No se ha encontrado el curso'];

        return ['grade' => $grade, 'school' => $grade->school, 'students' => $grade->students];
    }

    function store($req) {
        $validated = $req->validated();

        try {
            $this->grade->create($validated);
            return ['status' => 200, 'message' => 'Curso añadido con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    function update($id, $req) {
        $validated = $req->validated();

        try {
            $this->grade->findOrFail($id)->update($validated);
            return ['status' => 200, 'message' => 'Curso actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    function delete($id, $req) {
        try {
            Grade::findOrFail($id)->delete();
            return ['status' => 200, 'message' => 'Colegio eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}