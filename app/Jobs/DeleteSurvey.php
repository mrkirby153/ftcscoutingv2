<?php

namespace App\Jobs;

use App\Models\Surveys\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteSurvey implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Survey
     */
    private $survey;

    /**
     * Create a new job instance.
     *
     * @param Survey $survey
     */
    public function __construct(Survey $survey) {
        $this->survey = $survey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        \Log::debug("Deleting survey " . $this->survey->id);
        $this->survey->delete();
    }
}
