<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Parameter extends Model
{
    use HasFactory;

    protected $table = 'parameters';
    protected $fillable = [
        'name',
        'description',
        'type',
        'game_id'
    ];

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function instances(): BelongsToMany {
        return $this->belongsToMany(GameInstance::class, 'game_instance_parameters' , 'parameter_id', 'game_instance_id')
            ->withPivot('value');
    }
}
