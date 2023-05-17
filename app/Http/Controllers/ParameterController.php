<?php

namespace App\Http\Controllers;

use App\Http\Requests\Games\Parameters\{ParameterCreateRequest, ParameterUpdateRequest};
use App\Models\Parameter;
class ParameterController extends Controller
{
    private $param;

    public function __construct(Parameter $param) {
        $this->param = $param;
    }

    public function store(ParameterCreateRequest $request) {
        $res = $this->param->store($request);
        return redirect()->route('games.edit', $request->game_id)->with('notification', $res);
    }

    public function update(ParameterUpdateRequest $request) {
        $res = $this->param->edit($request);
        return redirect()->route('games.edit', $request->game_id)->with('notification', $res);
    }

    public function destroy($id) {
        $res = $this->param->erase($id);
        return redirect()->route('games.edit', $res['game_id'])->with('notification', $res);
    }
}
