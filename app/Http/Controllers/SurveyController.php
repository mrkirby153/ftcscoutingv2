<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteSurvey;
use App\Models\Surveys\Question;
use App\Models\Surveys\Survey;
use App\Models\Team;
use Illuminate\Http\Request;
use Keygen\Keygen;

class SurveyController extends Controller {

    public function createSurvey(Request $request, Team $team) {
        $request->validate([
            'name' => 'required|max:255'
        ]);
        return $team->surveys()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'name' => $request->get('name'),
            'created_by' => $request->user()->id
        ]);
    }

    public function deleteSurvey(Survey $survey) {
        DeleteSurvey::dispatch($survey);
    }

    public function createQuestion(Survey $survey) {
        return $survey->questions()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'question_name' => 'New Question'
        ]);
    }

    public function deleteQuestion(Survey $survey, Question $question) {
        $question->delete();
    }

    public function editQuestion(Request $req, Survey $survey, Question $question) {
        $req->validate([
            'question_name' => 'required|max:255'
        ]);
        $question->question_name = $req->get('question_name');
        $question->extra_data = $req->get('extra_data');
        $question->save();
        return $question;
    }

    public function setQuestionType(Request $request, Survey $survey, Question $question){
        $question->type = $request->get('type');
        $question->save();
    }

    public function get(Survey $survey) {
        return $survey->with('questions')->first();
    }

    public function showSurveys(Team $team){
        return $team->surveys;
    }
}
