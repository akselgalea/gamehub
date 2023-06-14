<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\SoftDeletes;

use Exception;

class Survey extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surveys';

    protected $fillable = [
        'name',
        'description',
        'type',
        'body',
        'init_date',
        'end_date',
        'experiment_id'
    ];

    protected $cast = [
        'body' => 'array',
    ];

    public function type($type) {
        $types = [
            'test' => 'Prueba',
            'survey' => 'Encuesta'
        ];

        return $types[$type];
    }

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }

    public function responses(): HasMany {
        return $this->hasMany(SurveyResponse::class);
    }

    public function scopePrePlay(Builder $query): void
    {
        $query->where('stage', 'pre');
    }

    public function scopePostPlay(Builder $query): void
    {
        $query->where('stage', 'post');
    }

    public function scopeActiveByDate(Builder $query, $date): void {
        $query->where([['init_date', '>=', $date], ['end_date', '<=', $date]])->orderBy('init_date');
    }

    public function getUserResponse($user) {
        return $this->responses()->firstWhere('user_id', $user);
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            Survey::create($validated);
            return ['status' => 200, 'message' => $this->type($req->type) . ' creada con éxito'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     * Devuelve la encuesta a editar, carga diferentes vistas según el tipo de encuesta.
     */
    public function editView($id) {
        try {
            $survey = Survey::findOrFail($id);

            if($survey->type == 'survey')
                $view = 'Admin/Experiments/Surveys/Edit';
            else
                $view = 'Admin/Experiments/Surveys/Tests/Edit';
            
            return ['survey' => $survey, 'view' => $view];
        } catch (Éxception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function edit($id, $req) {
        $validated = $req->validated();

        try {
            $survey = Survey::findOrFail($id);
            $survey->update($validated);

            return ['status' => 200, 'message' => $this->type($survey->type) . ' actualizada con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function erase($req) {
        try {
            $survey = Survey::findOrFail($req->surveyId);
            $type = $survey->type;
            $survey->delete();

            return ['status' => 200, 'message' => $this->type($type) . ' eliminada con éxito'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
