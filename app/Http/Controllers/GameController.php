<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameCreateRequest;
use App\Http\Requests\GameUpdateRequest;
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
        return Inertia::render('Admin/Games/Edit', ['game' => Game::find($id), 'categories' => Category::all()]);
    }

    public function update($id, GameUpdateRequest $request) {
        $res = $this->game->find($id)->edit($request);
        return redirect()->route('games.edit', $id)->with('notification', $res);
    }

    public function destroy($id) {
        return to_route('games.index');
    }
}
