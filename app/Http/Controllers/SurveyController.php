<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteSurvey;
use App\Models\Surveys\PINData;
use App\Models\Surveys\Question;
use App\Models\Surveys\ResponseData;
use App\Models\Surveys\Survey;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Keygen\Keygen;
use PHPUnit\Framework\Constraint\RegularExpression;

class SurveyController extends Controller {

    public function createSurvey(Request $request, Team $team) {
        $request->validate([
            'name' => 'required|max:255'
        ]);
        $survey = $team->surveys()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'name' => $request->get('name'),
            'created_by' => $request->user()->id
        ]);
        if ($request->has('cloneFrom') && $request->get('cloneFrom') != "-1") {
            \Log::debug("Cloning from survey " . $request->get('clone-from'));
            // Clone the survey
            $toClone = Survey::whereId($request->get('cloneFrom'))->with('questions', 'questions.pin')->first();
            if ($toClone == null) {
                return response()->json(["errors" => ["cloneFrom" => ['That survey does not exist']]], 422);
            }
            foreach ($toClone->questions as $question) {
                $q = $survey->questions()->create([
                    'id' => Keygen::alphanum(15)->generate(),
                    'question_name' => $question->question_name,
                    'survey_id' => $survey->id,
                    'type' => $question->type,
                    'extra_data' => $question->extra_data,
                    'order' => $question->order
                ]);
                if ($question->pin != null) {
                    $q->pin()->create([
                        'id' => Keygen::alphanum(15)->generate(),
                        'data' => $question->pin->data
                    ]);
                }
            }
        } else {
            $this->createQuestion($survey);
        }
        return $survey;
    }

    public function deleteSurvey(Survey $survey) {
        DeleteSurvey::dispatch($survey);
    }

    public function createQuestion(Survey $survey) {
        return $survey->questions()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'question_name' => 'New Question',
            'order' => $this->findNextOrderNumber($survey)
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

        // Fix PIN for multiple choice
        if ($question->type == "MULTIPLE_CHOICE" || $question->type == "RADIO") {
            $pin = PINData::whereQuestionId($question->id)->first();
            if ($pin != null) {
                $toDelete = array();
                foreach ($pin->data as $k => $data) {
                    $found = false;
                    foreach ($question->extra_data->items as $ed) {
                        if ($ed->name == $k) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $toDelete[] = $k;
                    }
                }
                $d = $pin->data;
                foreach ($toDelete as $u) {
                    unset($d->$u);
                }
                $pin->data = $d;
                $pin->save();
            }
        }

        $question->save();
        return $question;
    }

    public function setQuestionType(Request $request, Survey $survey, Question $question) {
        $question->type = $request->get('type');
        $question->save();
    }

    public function get($survey) {
        $survey = Survey::whereId($survey);
        return $survey->with('questions')->first();
    }

    public function getQuestion($question) {
        return Question::whereId($question)->firstOrFail();
    }

    public function showSurveys(Team $team) {
        $num = 0;
        if (\Auth::user()->can('update', $team)) {
            $num = 1;
        }
        $r = \DB::table('responses')
            ->selectRaw('survey_id, COUNT(*) AS response_count')
            ->groupBy('survey_id');
        return \DB::table('surveys')
            ->where('team_id', '=', $team->id)
            ->where('archived', '<=', $num)
            ->leftJoin(\DB::raw("(" . $r->toSql() . ") AS a"), 'survey_id', '=', 'id')
            ->get();
    }

    public function processSubmit(Request $request, Survey $survey) {
        \Log::debug($request);
        $request->validate([
            'match_number' => 'required|numeric',
            'team_number' => 'required'
        ]);
        $matchNumber = $request->get('match_number');
        $teamNumber = $request->get('team_number');
        // Check if there's a response already
        if($survey->responses()->whereTeamNumber($teamNumber)->whereMatchNumber($matchNumber)->exists()){
            // The survey exists, we should return that to the user
            return response()->json([
                'errors' => [
                   'match_number' => ['This match has already been recorded.']
                ]
            ], 422);
        }
        $resp = $survey->responses()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'user_id' => \Auth::id(),
            'team_number' => $teamNumber,
            'match_number' => $matchNumber
        ]);
        foreach ($survey->questions as $question) {
            $d = new ResponseData();
            $d->id = Keygen::alphanum(15)->generate();
            $d->response_id = $resp->id;
            $d->question_id = $question->id;
            $d->response_data = $request->get($question->id);
            if ($d->response_data == null) {
                $d->response_data = [];
            }
            $d->save();
        }
    }

    public function setQuestionOrder(Request $request, Survey $survey, Question $question) {
        $question->order = $request->get('order');
        $question->save();
        return $question;
    }

    public function setArchived(Request $request, Survey $survey){
        $survey->archived = $request->get('archived') == "true";
        $survey->save();
        return $survey;
    }

    public function findNextOrderNumber(Survey $survey) {
        $num = 0;

        foreach ($survey->questions as $question) {
            if ($num < $question->order) {
                $num = $question->order;
            }
        }

        return ++$num;
    }
}
