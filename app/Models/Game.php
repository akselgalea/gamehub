<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\URL;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Builder;

class Game extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug;

    protected $table = 'games';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'file',
        'gm2game',
        'extra',
        'category_id',
        'user_id'
    ];

    protected $casts = [
        'gm2game' => 'boolean',
        'extra' => 'array'
    ];

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function parameters(): HasMany {
        return $this->hasMany(Parameter::class);    
    }

    public function instances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function scopeAllAccessGames(Builder $query): void {
        $query->where('gm2game', false);
    }

    public function scopeGm2Games(Builder $query): void {
        $query->where('gm2game', true);
    }

    public function getAllAccessGames() {
        return Game::allAccessGames()->get();
    }

    public function getGm2Games() {
        return Game::Gm2Games()->get();
    }

    public function findBySlug($slug) {
        return Game::firstWhere('slug', $slug);
    }
}
