<?php

namespace App\Http\Controllers;

use App\Http\Requests\Games\{GameCreateRequest, GameUpdateRequest, GameDeleteRequest};
use App\Models\Category;
use App\Models\Game;
use Inertia\Inertia;

class GameController extends Controller
{
    private $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    public function index() {
        return Inertia::render('Admin/Games/Index', ['games' => Game::all()]);
    }

    public function create() {
        return Inertia::render('Admin/Games/Create', ['categories' => Category::all()]);
    }

    public function store(GameCreateRequest $request) {
        $res = $this->game->store($request);
        return redirect()->route('games.create')->with('notification', $res);
    }

    public function edit($id) {
        $game = $this->game->with('parameters')->find($id);
        return Inertia::render('Admin/Games/Edit', ['game' => $game, 'categories' => Category::all()]);
    }

    public function update($id, GameUpdateRequest $request) {
        $res = $this->game->find($id)->edit($request);
        return redirect()->route('games.edit', $id)->with('notification', $res);
    }

    public function destroy(GameDeleteRequest $request) {
        $res = $this->game->erase($request);
        return redirect()->route('games.index')->with('notification', $res);
    }

    public function play($id) {
        $res = $this->game->play($id);
        return Inertia::render('Games/Play', ['game' => $res['game'], 'location' => $res['location']]);
    }
}
