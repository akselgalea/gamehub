<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class GameInstanceParameter extends Model
{
    use HasFactory;

    protected $table = 'game_instance_parameters';

    protected $fillable = [
        'value',
        'parameter_id',
        'game_instance_id'
    ];

    protected $parametersTypes = [
    ];

    
}
