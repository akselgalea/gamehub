<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function store($req) {
        $validated = $req->validated();

        try {
            Parameter::create($validated);
            return ['status' => 200, 'message' => 'Parametro creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function edit($req) {
        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Parametro actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function erase($req) {
        try {
            Parameter::findOrFail($req->id)->delete();
            return ['status' => 200, 'message' => 'Parametro eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
