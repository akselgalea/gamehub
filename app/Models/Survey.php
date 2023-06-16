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

    protected $casts = [
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
        $query->where('stage', 'pre')->orderBy('init_date', 'asc');
    }

    public function scopePostPlay(Builder $query): void
    {
        $query->where('stage', 'post')->orderBy('init_date', 'asc');
    }

    public function scopeActiveByDate(Builder $query, $date): void {
        $query->where([['init_date', '>=', $date], ['end_date', '<=', $date]])->orderBy('init_date');
    }

    public function getUserResponse($user) {
        return $this->responses()->firstWhere('user_id', $user);
    }
}
