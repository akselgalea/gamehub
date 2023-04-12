<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
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
        'gm2game',
        'extra',
        'category_id',
        'user_id'
    ];

    protected $cast = [
        'gm2game' => 'boolean',
    ];

    protected $append = [
        'slug'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function parameters(): HasMany {
        return $this->hasMany(Parameter::class);    
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
            $path2extract = base_path() . "/public" . $link;

            // Borra los archivos, si existe el directorio
            // if (Storage::exists($path2extract)) (new Filesystem)->cleanDirectory($path2extract);
            $zipper->make($pathzip)->folder('html5game')->extractTo($path2extract);
            
            if($this->gm2game) {
                // Recupera archivo html5game para obtener nombre de archivo .js de GameMaker
                $dothtml = $zipper->make($pathzip)->getFileContent('index.html');
                preg_match_all('/html5game\/([^*]*).js/', $dothtml, $matches);
                $gamedata = ["type" => 'GM2', "filename" => $matches[1][0]];
                $this->extra = json_encode($gamedata);
            }
            
            $zipper->close();
            $this->file = $link;
            $this->save();
            
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
            $store = $game->storeGameFiles($req->gm2game);

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
