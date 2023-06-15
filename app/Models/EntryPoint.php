<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasManyThrough, BelongsToMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;

class EntryPoint extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'entry_points';

    protected $fillable = [
        'token',
        'slug',
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

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom('token')
            ->saveSlugsTo('slug');
    }

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }
}
