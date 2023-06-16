<?php

namespace App\Services;

use App\Models\{Experiment, User};
use App\Services\GameInstanceService;
use Exception;

class ExperimentService
{
    private $exp;
    private $instancia;

    public function __construct(Experiment $e, GameInstanceService $instancia) {
        $this->exp = $e;
        $this->instancia = $instancia;
    }

    public function get($id) {
        return $this->exp->find($id);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Experimento no encontrado'];
    }

    public function store($req) {
        $validated = $req->validated();
        
        try {
            $experiment = Experiment::create($validated);

            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Actualiza la informacion general de un experimento //
    public function update($id, $req) {
        $experiment = $this->get($id);
        
        if(!$experiment)
            $this->notFoundText();
  
        try {
            $validated = $req->validated();
            $experiment->update($validated);
            return ['status' => 200, 'message' => 'Datos del experimento actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Permite crear una asociacion entre un usuario y un experimento //
    public function userAssociateExperiment($req) {

        $validated = $req->validated();

        try {
            
            $user = User::find($validated['user_id']);
            $experiment = Experiment::find($validated['experiment_id']);

            $experiment->students()->attach($user);
            $this->instancia->getUserGameInstance($experiment['id'], $user['id']); // se le asigna una instancia de juego de el experimento en caso de que tenga

            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Permite la desvinculacion de un usuario y un experimento //
    public function userDisassociateExperiment($req) {

        $validated = $req->validated();
 
        try {

            $user = User::find($validated['user_id']);
            $experiment = Experiment::find($validated['experiment_id']);

            $experiment->students()->detach($user);

            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}