<?php

namespace App\Services;

use App\Models\Experiment;
use Exception;

class ExperimentService
{
    private $exp;

    public function __construct(Experiment $e) {
        $this->exp = $e;
    }

    public function get($id) {
        return $this->exp->find($id);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Experimento no encontrado'];
    }
}