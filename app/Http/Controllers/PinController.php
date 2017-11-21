<?php

namespace App\Http\Controllers;

use App\Models\Surveys\PINData;
use App\Models\Surveys\Question;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Http\Request;
use Keygen\Keygen;

class PinController extends Controller {

    /**
     * @var PINData
     */
    private $pin;

    /**
     * @var Question
     */
    private $question;


    public function __construct(PINData $data, Question $question) {
        $this->pin = $data;
        $this->question = $question;
    }

    public function create($question) {
        return $this->pin->create([
            'id' => Keygen::alphanum(10)->generate(),
            'question_id' => $question,
            'data' => []
        ]);
    }

    public function delete($id) {
        $this->pin->whereId($id)->delete();
    }

    public function getForQuestion($question) {
        return PINData::firstOrCreate(['question_id' => $question], [
            'data' => [],
            'id' => Keygen::alphanum(10)->generate(),
            'question_id' => $question
        ]);
    }

    public function getById($id) {
        return $this->pin->whereId($id)->first();
    }

    public function update(Request $request, PINData $id) {
        $request->validate([
            'data' => 'required'
        ]);
        $id->data = $request->get('data');
        $id->save();
    }

    public function getSurveyPinData(Survey $survey) {
        return $this->pin->whereIn('question_id', $survey->questions->getQueueableIds())->get();
    }

    public function getResponsePin(Response $response) {
        $response->load('survey', 'data.question');
        $pinData = $this->getSurveyPinData($response->survey);
        $parsed = array();
        foreach ($pinData as $pin) {
            $parsed[$pin->question_id] = \GuzzleHttp\json_decode($pin->data);
        }
        $total = 0;
        foreach ($response->data as $data) {
            $question = $data->question;
            $type = $question->type;
            if ($type == "MULTIPLE_CHOICE" || $type == "RADIO") {
                $decoded = $data->response_data;
                foreach ($decoded as $d) {
                    $total += $parsed[$question->id]->$d;
                }
            }
        }
        return $total;
    }
}
