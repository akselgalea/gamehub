<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryPoint extends Model
{
    use HasFactory;

    protected $table = 'EntryPoint';

    protected $fillable = [
        'toke',
        'title',
        'information',
        'obfuscated',
        'experiment_id'
        //'course_id'
        //'college_id'
    ];

    protected $cast = [
        'obfuscated' => 'boolean',
    ];

    
}
