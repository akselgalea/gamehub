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

    protected $append = [
        'slug'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getSlugAttribute() {
        return Str::of($this->name)->slug('-');
    }

    /* 
        * Guarda los archivos del juego
        * Necesita ser llamado desde una request tipo GameCreateRequest o GameUpdateRequest que contenga el campo 'file'
    */
    public function storeGameFiles($isUpdate = false) {
        try {
            $this->addMediaFromRequest('file')->toMediaCollection('games');
            $pathzip = $this->getMedia('games')->first()->getPath();
    
            $zipper = new Madzipper;
            $link = "/uploads/games/$this->slug";
            $zipper->make($pathzip)->folder('html5game')->extractTo(base_path() . "/public/$link");
            $zipper->close();
            
            $this->update(['file' => $link]);
            
            return ['success' => true, 'message' => 'Archivos subidos con exito'];
        } catch (Exception $e) {
            if(!$isUpdate) $this->delete();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function store($req) {
        $validated = $req->validated();

        try {
            $game = Game::create($validated);
            $store = $game->storeGameFiles();

            // Si hay un error al subir los archivos se manda un mensaje de error
            if(!$store['success'])
                throw new Exception($store['message']);
            
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
