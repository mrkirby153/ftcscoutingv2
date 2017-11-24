<?php

namespace App\Http\Controllers;

use App\Models\Surveys\Response;
use App\Models\Surveys\ResponseData;
use App\Models\Surveys\Survey;
use Illuminate\Http\Request;

class ResponseController extends Controller {

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Survey
     */
    private $survey;

    /**
     * @var ResponseData
     */
    private $responseData;

    public function __construct(Survey $survey, Response $response, ResponseData $responseData) {
        $this->survey = $survey;
        $this->response = $response;
        $this->responseData = $responseData;
    }

    public function getSurveyResponseOverview(Survey $survey) {
        return \DB::table($this->response->getTable())
            ->selectRaw('team_number, COUNT(*) as responses')
            ->where('survey_id', '=', $survey->id)
            ->groupBy('team_number')
            ->orderBy('team_number', 'ASC')
            ->get();
    }

    public function getResponsesForTeam(Survey $survey, $team) {
        return $this->response->whereTeamNumber($team)->whereSurveyId($survey->id)->with('submitter')->orderBy('match_number')->get();
    }

    public function getResponse(Response $response) {
        $data = \DB::table($this->responseData->getTable())
            ->join('survey_questions', 'response_data.question_id', '=', 'survey_questions.id')
            ->where('response_id', '=', $response->id)
            ->select('response_data.id', 'response_data', 'question_name', 'type')
            ->orderBy('order', 'ASC')
            ->get();
        return [
            'data' => $data,
            'team' => $response->team_number
        ];
    }

    public function delete(Response $response){
        foreach($response->data as $data){
            $data->delete();
        }
        $response->delete();
    }
}
