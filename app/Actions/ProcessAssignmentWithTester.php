<?php

namespace App\Actions;

use App\Models\User;
use App\Dto\TesterInput;
use App\Contracts\Tester;
use App\Models\Assignment;
use App\Models\Submission;
use App\Dto\TestResultCase;
use App\Models\TestScenario;
use App\Dto\TestResultScenario;
use Illuminate\Support\Facades\File;
use App\Models\SubmissionTestScenario;
use App\Notifications\SubmissionProcessed;
use Spatie\QueueableAction\QueueableAction;

class ProcessAssignmentWithTester
{
    use QueueableAction;

    public function __construct(
        private Tester $tester,
        private ResolvePointsForSubmission $resolvePointsForSubmission,
    )
    {
    }

    public function execute(Submission $submission, TesterInput $input)
    {
        $result = $this->tester->run($input);

        collect($result->scenarios)->each(function (TestResultScenario $scenario) use ($submission) {
            $hasFailedCases = collect($scenario->cases)->filter(fn (TestResultCase $case) => !$case->success)->isNotEmpty();

            $resultScenario = $submission->resultScenarios()->create([
                'test_scenario_id' => $scenario->id,
                'points' => $hasFailedCases ? 0 : TestScenario::find($scenario->id)->first()->points,
            ]);

            collect($scenario->cases)->each(function (TestResultCase $case) use ($resultScenario) {
                $resultScenario->resultCases()->create([
                    'cmdin' => $case->cmdIn,
                    'stdin' => $case->stdIn,
                    'stdout' => $case->stdOut,
                    'errout' => $case->stdErr,
                    'actual_stdout' => $case->actualStdout,
                    'actual_stderr' => $case->actualStderr,
                    'is_success' => $case->success,
                    'messages' => json_encode($case->messages),
                ]);
            });
        });

        $submission->update([
            'report' => $result,
            'points' => $this->resolvePointsForSubmission->execute($submission),
            'file_content' => File::get($submission->file_path),
        ]);

        $submission->user->notify(new SubmissionProcessed($submission));
    }
}
