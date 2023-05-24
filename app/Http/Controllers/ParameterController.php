<?php

namespace App\Http\Controllers;

use App\Http\Requests\Games\Parameters\{ParameterCreateRequest, ParameterUpdateRequest, ParameterDeleteRequest};
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

    public function update($id, ParameterUpdateRequest $request) {
        $res = $this->param->find($id)->edit($request);
        return redirect()->route('games.edit', $request->game_id)->with('notification', $res);
    }

    public function destroy(ParameterDeleteRequest $request) {
        $res = $this->param->erase($request);
        return redirect()->back()->with('notification', $res);
    }
}
