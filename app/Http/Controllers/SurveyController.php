<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\Surveys\{SurveyCreateRequest, SurveyUpdateRequest, SurveyDeleteRequest};
use App\Http\Requests\Experiments\Surveys\{SurveyQuestionCreateRequest};
use Illuminate\Http\Request;
use App\Models\Survey;
use Inertia\Inertia;

class SurveyController extends Controller
{
    private $survey;

    public function __construct(Survey $survey) {
        $this->survey = $survey;
    }

    public function create($id) {
        return Inertia::render('Admin/Experiments/Surveys/Create', ['experimentId' => $id]);
    }

    public function store(SurveyCreateRequest $request) {
        $res = $this->survey->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function questionCreate(SurveyQuestionCreateRequest $request) {
        return redirect()->back();
    }

    // public function update($id, SurveyUpdateRequest $request) {
    //     $res = $this->survey->find($id)->edit($request);
    //     return redirect()->route('games.edit', $request->game_id)->with('notification', $res);
    // }

    // public function destroy(SurveyDeleteRequest $request) {
    //     $res = $this->survey->erase($request);
    //     return redirect()->back()->with('notification', $res);
    // }
}
