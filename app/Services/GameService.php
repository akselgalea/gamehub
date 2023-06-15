<?php

namespace App\Services;

use App\Models\Game;
use Inertia\Inertia;
use Exception;

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

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Juego no encontrado'];
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
        $extra = json_decode($game->extra);
        $location = "$game->file/$extra->filename.js";

        return Inertia::render('Games/PlayGM2Game', ['game' => $game, 'location' => $location]);
    }

    public function deleteGame($id) {
        try {
            $game = Game::findOrFail($id);

            $gameFolder = $game->gm2game ? base_path() . $game->file : public_path($game->file);

            if ($game->deleteFolder($gameFolder))
                $game->delete();
            else
                throw new Exception('Error al eliminar los archivos del juego.');

            return ['status' => 200, 'message' => 'Juego eliminado con éxito!'];
        } catch(Exception $e) {
            return ['status' => 500, 'message' => 'Ha ocurrido un error al eliminar el juego.'];
        }
    }
}