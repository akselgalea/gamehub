<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SurveyService;

class SurveyResponseController extends Controller
{
    private $ss;

    public function __construct(SurveyService $ss) {
        $this->ss = $ss;
    }

    public function store(Request $request) {
        $res = $this->ss->storeResponse($request);
        return redirect()->back()->with('notification', $res);
    }
}
