<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiments\Surveys\{SurveyCreateRequest, SurveyUpdateRequest, SurveyDeleteRequest};
use App\Http\Requests\Experiments\Surveys\{SurveyQuestionCreateRequest, TestQuestionCreateRequest};
use Illuminate\Http\Request;
use App\Models\Survey;
use Inertia\Inertia;

class SurveyController extends Controller
{
    private $survey;

    public function __construct(Survey $survey) {
        $this->survey = $survey;
    }

    public function run($experiment, $survey) {
        return Inertia::render('Surveys/Run', ['experimentId' => $experiment, 'survey' => $this->survey->find($survey)]);
    }

    public function create($id) {
        return Inertia::render('Admin/Experiments/Surveys/Create', ['experimentId' => $id]);
    }

    public function store(SurveyCreateRequest $request) {
        $res = $this->survey->store($request);
        return redirect()->back()->with('notification', $res);
    }

    public function edit($id, $survey) {
        $res = $this->survey->editView($survey);

        if(isset($res['status']) && $res['status'] == 500)
            return redirect()->back()->with('notification', $res);

        return Inertia::render($res['view'], ['survey' => $res['survey']]);
    }
    
    public function update($experiment, $id, SurveyCreateRequest $request) {
        $res = $this->survey->edit($id, $request);
        return redirect()->back()->with('notification', $res);
    }

    public function destroy($experiment, $id, SurveyDeleteRequest $request) {
        $res = $this->survey->erase($request);
        return redirect()->route('experiment.management', $experiment)->with('notification', $res);
    }

    public function questionCreate(SurveyQuestionCreateRequest $request) {
        return redirect()->back();
    }

    public function testCreate($id) {
        return Inertia::render('Admin/Experiments/Surveys/Tests/Create', ['experimentId' => $id]);
    }

    public function testQuestionCreate(TestQuestionCreateRequest $request) {
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
