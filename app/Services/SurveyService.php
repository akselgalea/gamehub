<?php

namespace App\Services;
use App\Models\{Survey, SurveyResponse};
use Exception;

class SurveyService
{

    private $survey;
    private $surveyResponse;

    public function __construct(Survey $survey, SurveyResponse $sr) {
        $this->survey = $survey;
        $this->surveyResponse = $sr;
    }


    public function get($id) {
        return $this->survey->find($id);
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            $this->survey->create($validated);
            return ['status' => 200, 'message' => $this->survey->type($req->type) . ' creada con éxito'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     * Devuelve la encuesta a editar, carga diferentes vistas según el tipo de encuesta.
     */
    public function editView($id) {
        try {
            $survey = $this->survey->findOrFail($id);

            if($survey->type == 'survey')
                $view = 'Admin/Experiments/Surveys/Edit';
            else
                $view = 'Admin/Experiments/Surveys/Tests/Edit';
            
            return ['survey' => $survey, 'view' => $view];
        } catch (Éxception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($id, $req) {
        $validated = $req->validated();

        try {
            $survey = $this->survey->findOrFail($id);
            $survey->update($validated);

            return ['status' => 200, 'message' => $this->survey->type($survey->type) . ' actualizada con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function destroy($req) {
        try {
            $survey = $this->survey->findOrFail($req->surveyId);
            $type = $survey->type;
            $survey->delete();

            return ['status' => 200, 'message' => $this->survey->type($type) . ' eliminada con éxito'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Encuesta no encontrada'];
    }

    public function storeResponse($req) {
        try {
            $this->surveyResponse->create($req->all());

            return ['status' => 200, 'message' => 'Respuesta guardada con éxito'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}