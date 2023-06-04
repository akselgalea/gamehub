<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Exception;

class Survey extends Model
{
    use HasFactory;

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

    public function store($req) {
        $validated = $req->validated();

        try {
            Survey::create($validated);
            return ['status' => 200, 'message' => $this->type($req->type) . ' creada con éxito'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
