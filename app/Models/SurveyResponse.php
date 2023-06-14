<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'survey_responses';

    protected $fillable = [
        'status',
        'checkpoint',
        'body',
        'user_id',
        'survey_id'
    ];

    protected $cast = [
        'body' => 'array',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function survey(): BelongsTo {
        return $this->belongsTo(Survey::class);
    }

    public function getUserSurveyResponse($user, $survey) {
        return SurveyResponse::firstWhere(['user_id' => $user, 'survey_id' => $survey]);
    }
}
