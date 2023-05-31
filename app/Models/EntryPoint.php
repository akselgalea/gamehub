<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{HasMany, HasManyThrough, BelongsToMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryPoint extends Model
{
    use HasFactory;

    protected $table = 'entry_points';

    protected $fillable = [
        'token',
        'name',
        'description',
        'obfuscated',
        'experiment_id'
        //'course_id'
        //'college_id'
    ];

    protected $cast = [
        'obfuscated' => 'boolean',
    ];

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }
}
