<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Madnest\Madzipper\Madzipper;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\URL;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Builder;

class Game extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug;

    protected $table = 'games';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'file',
        'gm2game',
        'extra',
        'category_id',
        'user_id'
    ];

    protected $cast = [
        'gm2game' => 'boolean',
        'extra' => 'array'
    ];

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function parameters(): HasMany {
        return $this->hasMany(Parameter::class);    
    }

    public function instances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function scopeAllAccessGames(Builder $query): void {
        $query->where('gm2game', false);
    }

    public function scopeGm2Games(Builder $query): void {
        $query->where('gm2game', true);
    }

    public function getAllAccessGames() {
        return Game::allAccessGames()->get();
    }

    /** 
     * Guarda los archivos del juego
     * Necesita ser llamado desde una request tipo GameCreateRequest o GameUpdateRequest que contenga el campo 'file'
     * retorna un mensaje de si la subida fue exitosa 
    */
    public function storeGameFiles($isUpdate = false) {
        try {
            $this->addMediaFromRequest('file')->toMediaCollection('games');
            $pathzip = $this->getMedia('games')->first()->getPath();
    
            $zipper = new Madzipper;
            $link = "/uploads/games/$this->slug";

            if(!$this->gm2game)
                return $this->extractToPublic($zipper, $pathzip, $link);
            
            $path2extract = base_path() . $link;

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
            
            return ['status' => 200, 'message' => 'Archivos subidos con éxito!'];
        } catch (Exception $e) {
            if(!$isUpdate) $this->delete();
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    private function extractToPublic($zipper, $pathzip, $link) {
        try {
            $path2extract = public_path() . $link;
            $zipper->make($pathzip)->extractTo($path2extract);
            $this->file = $link;
            $this->save();

            return ['status' => 200, 'message' => 'Archivos subidos con éxito!'];
        } catch (Exception $e) {
            $this->delete();
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function store($req) {
        $validated = $req->validated();
        
        try {
            $game = Game::create($validated);
            $store = $game->storeGameFiles();

            // Si hay un error al subir los archivos se manda un mensaje de error
            if($store['status'] != 200) 
                throw new Exception($store['message']);
            
            return ['status' => 200, 'message' => 'Juego creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function edit($req) {
        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Juego actualizado con éxito!', 'slug' => $this->slug];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function findBySlug($slug) {
        return Game::firstWhere('slug', $slug);
    }
}
