<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;

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

    public function store($req) {
        
        $validated = $req->validated();
        try {
            $entrypoint = EntryPoint::create($validated);

            return ['status' => 200, 'message' => 'Entrypoint creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
