<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\{HasSlug, SlugOptions};

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'categories';
    protected $fillable = [
        'name',
    ];

    public function games(): HasMany {
        return $this->hasMany(Game::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
