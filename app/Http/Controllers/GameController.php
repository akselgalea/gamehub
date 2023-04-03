<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    private $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    public function index() {
        return Inertia::render('Admin/Games/Index');
    }

    public function create() {
        return Inertia::render('Admin/Games/Create', ['categories' => Category::all()]);
    }

    public function store(Request $request) {
        return redirect()->route('games.create')->with('notification', 'sucess');
    }

    public function edit($id) {
        return Inertia::render('Admin/Games/Edit', ['game' => Game::find($id)]);
    }

    public function update($id, Request $request) {
        return redirect()->route('games.edit', $id);
    }

    public function destroy($id) {
        return to_route('games.index');
    }
}
