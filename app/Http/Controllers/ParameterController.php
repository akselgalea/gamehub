<?php

namespace App\Http\Controllers;

use App\Http\Requests\Games\Parameters\{ParameterCreateRequest, ParameterUpdateRequest, ParameterDeleteRequest};
use App\Services\ParameterService;
class ParameterController extends Controller
{
    private $param;

    public function __construct(ParameterService $param) {
        $this->param = $param;
    }

    public function store($slug, ParameterCreateRequest $request) {
        $res = $this->param->store($request);
        return redirect()->route('games.edit', $slug)->with('notification', $res);
    }

    public function update($slug, $id, ParameterUpdateRequest $request) {
        $res = $this->param->update($request);
        return redirect()->route('games.edit', $slug)->with('notification', $res);
    }

    public function destroy(ParameterDeleteRequest $request) {
        $res = $this->param->erase($request);
        return redirect()->back()->with('notification', $res);
    }
}
