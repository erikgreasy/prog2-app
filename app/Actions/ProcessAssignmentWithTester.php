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
use App\Enums\SubmissionStatus;
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

    public function execute(Submission $submission, TesterInput $input): void
    {
        $submission->update(['status' => SubmissionStatus::Processing]);

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

        try {
            $submission->update([
                'report' => $result,
                'points' => $this->resolvePointsForSubmission->execute($submission),
                'status' => SubmissionStatus::Completed,
                'build_status' => $result->buildStatus,
                'gcc_error' => $result->gccError,
                'gcc_warning' => $result->gccWarning,
            ]);
        } catch (\Exception $e) {
            $submission->update([
                'points' => 0,
                'status' => SubmissionStatus::Failed,
                'fail_messages' => [
                    'exception' => \Exception::class,
                    'public_output' => 'Nepodarilo sa ohodnotiť zadanie.',
                    'actual_output' => $e->getMessage(),
                ]
            ]);

            return;
        }

        $submission->user->notify(new SubmissionProcessed($submission));
    }
}
