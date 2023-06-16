<?php

namespace App\Services;

use App\Models\Game;
use Inertia\Inertia;
use Madnest\Madzipper\Madzipper;
use Exception;
use Illuminate\Support\Facades\Response;

class GameService
{
    private $game;
    private $fs;

    public function __construct(Game $g, FileService $fs) {
        $this->game = $g;
        $this->fs = $fs;
    }

    public function get($slug) {
        return $this->game->findBySlug($slug);
    }

    public function getGm2Games() {
        return $this->game->getGm2Games();
    }

    public function store($req) {
        $validated = $req->validated();
        
        try {
            $game = Game::create($validated);
            $store = $this->storeGameFiles($game);

            // Si hay un error al subir los archivos se manda un mensaje de error
            if($store['status'] != 200) 
                throw new Exception($store['message']);
            
            return ['status' => 200, 'message' => 'Juego creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function updateGame($slug, $req) {
        $game = $this->get($slug);

        if(!$game)
            return $this->notFoundText();

        $validated = $req->validated();

        try {
            $game->update($validated);
            return ['status' => 200, 'message' => 'Juego actualizado con éxito!', 'slug' => $game->slug];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function deleteGame($id) {
        try {
            $game = $this->game->findOrFail($id);
            $gameFolder = $game->gm2game ? base_path() . $game->file : public_path($game->file);

            if ($this->fs->deleteFolder($gameFolder))
                $game->delete();
            else
                throw new Exception('Error al eliminar los archivos del juego.');

            return ['status' => 200, 'message' => 'Juego eliminado con éxito!'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    /** 
     * Guarda los archivos del juego
     * Necesita ser llamado desde una request tipo GameCreateRequest o GameUpdateRequest que contenga el campo 'file'
     * retorna un mensaje de si la subida fue exitosa 
    */
    private function storeGameFiles($game, $isUpdate = false) {
        try {
            $game->addMediaFromRequest('file')->toMediaCollection('games');
            $pathzip = $game->getMedia('games')->first()->getPath();
    
            $zipper = new Madzipper;
            $link = "/uploads/games/$game->slug";

            if(!$game->gm2game)
                return $this->extractToPublic($game, $zipper, $pathzip, $link);
            
            $path2extract = base_path() . $link;

            // Borra los archivos, si existe el directorio
            // if (Storage::exists($path2extract)) (new Filesystem)->cleanDirectory($path2extract);
            $zipper->make($pathzip)->folder('html5game')->extractTo($path2extract);
        
            // Recupera archivo html5game para obtener nombre de archivo .js de GameMaker
            $dothtml = $zipper->make($pathzip)->getFileContent('index.html');
            preg_match_all('/html5game\/([^*]*).js/', $dothtml, $matches);
            $gamedata = ["type" => 'GM2', "filename" => $matches[1][0]];
            $zipper->close();
            
            $game->extra = json_encode($gamedata);
            $game->file = $link;
            $game->save();
            
            return ['status' => 200, 'message' => 'Archivos subidos con éxito!'];
        } catch (Exception $e) {
            if(!$isUpdate) $game->delete();
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    private function extractToPublic($game, $zipper, $pathzip, $link) {
        try {
            $path2extract = public_path() . $link;
            $zipper->make($pathzip)->extractTo($path2extract);
            $game->file = $link;
            $game->save();

            return ['status' => 200, 'message' => 'Archivos subidos con éxito!'];
        } catch (Exception $e) {
            $game->delete();
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
    
    public function play($slug) {
        $game = $this->get($slug);
        
        if(!$game)
            return redirect()->back()->with('notification', $this->notFoundText());

        if($game->gm2game)
            return $this->playGameMakerGame($game);

        $location = "$game->file/index.html";

        return Inertia::render('Games/Play', ['game' => $game, 'location' => $location]);
    }

    public function playGameMakerGame($game) {
        $location = $game->file . '/' .$game->extra['filename'] . '.js';

        return Inertia::render('Games/PlayGM2Game', ['game' => $game, 'location' => $location]);
    }

    public function getFile($gameSlug, $instance, $filename) {
        $game = $this->get($gameSlug);

        if(empty($game))
            return $this->notFoundText();

        if (substr($filename, 0, strlen('html5game')) == 'html5game') {
            $filename = substr($filename, strlen('html5game'));
        }
        
        $path = base_path() . $game->file . '/'. $filename;
        
        if(!$this->fs->exists($path)) {
            return ['status' => 404, 'message' => 'File not found.', 'path' => $path, 'filename' => $filename];
        }

        $file = $this->fs->get($path);
        $type = $this->fs->type($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Juego no encontrado'];
    }
}