<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experiment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'experiments';

    protected $fillable = [
        'name',
        'description',
        'status', // Detenido, Activo
        'time_limit' // In minutes
    ];

    public function instances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function games(): HasManyThrough {
        return $this->hasManyThrough(Game::class, GameInstance::class);
    }
}