<?php

namespace App\Http\Controllers;

use App\Http\Requests\Games\{GameCreateRequest, GameUpdateRequest, GameDeleteRequest};
use App\Services\GameService;
use App\Models\Category;
use App\Models\{Game, User};
use Inertia\Inertia;

class GameController extends Controller
{
    private $game;
    private $gs;

    public function __construct(Game $game, GameService $gs) {
        $this->game = $game;
        $this->gs = $gs;
    }

    public function index() {
        return Inertia::render('Admin/Games/Index', ['games' => Game::all()]);
    }

    public function myGames() {
        return Inertia::render('Games/MyGames', ['games' => Auth()->user()->getGamesICanPlay()]);
    }
    public function get($id) {
        return Inertia::render('Admin/Games/Index', ['games' => Game::find($id)]);
    }

    public function create() {
        return Inertia::render('Admin/Games/Create', ['categories' => Category::all()]);
    }

    public function store(GameCreateRequest $request) {
        $res = $this->game->store($request);
        return redirect()->route('games.create')->with('notification', $res);
    }

    public function edit($slug) {
        $game = $this->game->with('parameters')->firstWhere('slug', $slug);
        
        if(!$game)
            return redirect()->back()->with('notification', ['status' => 404, 'message' => 'No se ha encontrado el juego']);

        return Inertia::render('Admin/Games/Edit', ['game' => $game, 'categories' => Category::all()]);
    }

    public function update($slug, GameUpdateRequest $request) {
        $res = $this->game->findBySlug($slug)->edit($request);
        return redirect()->route('games.edit', $res['slug'])->with('notification', $res);
    }

    public function destroy(GameDeleteRequest $request) {
        $res = $this->game->erase($request);
        return redirect()->route('games.index')->with('notification', $res);
    }

    public function play($slug) {
        return $this->gs->play($slug);
    }
}
