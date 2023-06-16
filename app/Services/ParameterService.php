<?php

namespace App\Services;

use App\Models\Parameter;
use Exception;

class ParameterService
{
    private $param;

    public function __construct(Parameter $p) {
        $this->param = $p;
    }

    public function get($id) {
        return $this->param->find($id);
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            $this->param->create($validated);
            return ['status' => 200, 'message' => 'Parametro creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($id, $req) {
        $param = $this->get($id);
        
        if(empty($param))
            return $this->notFoundText();

        try {
            $validated = $req->validated();
            $param->update($validated);

            return ['status' => 200, 'message' => 'Parametro actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function erase($req) {
        try {
            $param = $this->get($req->id);

            if(empty($param))
                return $this->notFoundText();

            $param->delete();
            
            return ['status' => 200, 'message' => 'Parametro eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Parámetro no encontrado'];
    }
}