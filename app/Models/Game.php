<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Madnest\Madzipper\Madzipper;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Game extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'games';

    protected $fillable = [
        'name',
        'description',
        'file',
        'category_id',
        'user_id'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            $game = Game::create($validated);
            $game->addMediaFromRequest('file')->toMediaCollection('games');
            $pathzip = $game->getMedia('games')->first()->getPath();

            $zipper = new Madzipper;
            $slug = Str::of($game->name)->slug('-');
            $link = "/uploads/games/$slug";
            $zipper->make($pathzip)->folder('html5game')->extractTo(base_path() . "/public/$link");
            $zipper->close();
            
            $game->update(['file' => $link]);
            
            return ['status' => 200, 'message' => 'Juego creado con exito'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function edit($req) {
        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Juego actualizado con exito'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
