<?php

namespace App\Console\Commands;

use App\Mail\TeamInvite;
use App\Models\Surveys\PINData;
use App\Models\Surveys\Question;
use App\Models\Surveys\Response;
use App\Models\Surveys\ResponseData;
use App\Models\Surveys\Survey;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class RunMaintenance extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scouting:maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs maintenance on the database';

    /**
     * @var Team
     */
    private $team;

    /**
     * @var TeamMember
     */
    private $member;

    /**
     * @var Survey
     */
    private $survey;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ResponseData
     */
    private $responseData;

    /**
     * @var PINData
     */
    private $pinData;

    /**
     * @var Question
     */
    private $question;

    private $dry = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Team $team, TeamMember $member, Survey $survey, Response $response, ResponseData $responseData,
                                PINData $PINData, Question $question) {
        parent::__construct();
        $this->team = $team;
        $this->member = $member;
        $this->survey = $survey;
        $this->response = $response;
        $this->responseData = $responseData;
        $this->pinData = $PINData;
        $this->question = $question;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            // Put the website in maintenance mode
            $this->info("Bringing application down...");
            \Artisan::call("down");

            // Clean orphaned team invites
            $teams = $this->team->get()->getQueueableIds();

            $invites = $this->member->whereNotIn('team_id', $teams)->get();
            $this->clean($invites, "invites");

            // Clean up orphaned surveys

            $surveys = $this->survey->whereNotIn('team_id', $teams)->get();
            $this->clean($surveys, "surveys");

            // Clean orphaned questions
            $questions = $this->question->whereNotIn('survey_id', $this->survey->get()->getQueueableIds())->get();
            $this->clean($questions, "questions");
            // Clean up orphaned responses
            $responses = $this->response->whereNotIn('survey_id', $this->survey->get()->getQueueableIds())->get();
            $this->clean($responses, "responses");

            // Clean up orphaned response data
            $rData = $this->responseData->whereNotIn('response_id', $this->response->get()->getQueueableIds())->get();
            $this->clean($rData, "response data");

            // Clean up orphaned pin
            $pin = $this->pinData->whereNotIn('question_id', $this->question->get()->getQueueableIds())->get();
            $this->clean($pin, "PIN");

        } finally {
            $this->info("Bringing application up");
            \Artisan::call("up");
        }
    }

    private function clean($items, $name) {
        $this->info("Found " . count($items) . " orphaned $name");
        $bar = $this->output->createProgressBar(count($items));
        foreach ($items as $item) {
            if (!$this->dry)
                $item->delete();
            $bar->advance();
        }
        $bar->finish();
        $this->info("");
    }
}
