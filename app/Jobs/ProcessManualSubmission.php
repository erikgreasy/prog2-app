<?php

namespace App\Jobs;

use App\Contracts\Tester;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\TestScenario;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessManualSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Assignment $assignment;
    public Submission $submission;
    public string $pathToFile;

    public function __construct(int $assignmentId, int $userId, string $pathToFile, int $submissionId)
    {
        $this->assignment = Assignment::find($assignmentId);
        $this->pathToFile = $pathToFile;
        $this->submission = Submission::find($submissionId);
    }

    public function handle(Tester $tester)
    {
        $this->assignment->testScenarios->each(function (TestScenario $scenario) use ($tester) {
            $case = $scenario->cases()->inRandomOrder()->first();

            $output = $tester->run($this->pathToFile, $case->input);

            $this->submission->update([
                'report' => $output,
            ]);
        });
    }
}
