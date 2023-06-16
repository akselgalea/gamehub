<?php

namespace App\Services;

use App\Models\School;
use Exception;

class SchoolService
{
    private $school;

    public function __construct(School $s) {
        $this->school = $s;
    }

    public function findBySlug($slug) {
        return $this->school->findBySlug($slug);
    }

    public function get($id) {
        return $this->school->find($id);
    }

    public function getOrFail($id) {
        return $this->school->findOrFail($id);
    }

    public function all() {
        return $this->school->all();
    }

    public function getWithGrades() {
        return $this->school->with('grades')->get();
    }

    public function store($req)
    {
        $validated = $req->validated();

        try {
            $this->school->create($validated);
            return ['status' => 200, 'message' => 'Colegio añadido con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($id, $req)
    {
        $validated = $req->validated();

        try {
            $school = $this->get($id);

            if(empty($school))
                return $this->notFoundText();

            $school->update($validated);
            return ['status' => 200, 'message' => 'Colegio añadido con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function destroy($req) {
        try {
            $school = $this->get($req->id);

            if(empty($school))
                return $this->notFoundText();

            if($req->name == $school->name)
                $school->delete();
            else throw new Exception('El nombre no coincide'); 

            return ['status' => 200, 'message' => 'Colegio eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Colegio no encontrado'];
    }
}