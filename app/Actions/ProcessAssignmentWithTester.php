<?php

namespace App\Actions;

use App\Dto\TesterData;
use App\Models\User;
use App\Dto\TesterInput;
use App\Contracts\Tester;
use App\Models\Assignment;
use App\Models\Submission;
use App\Dto\TesterCase;
use App\Models\TestScenario;
use App\Dto\TesterScenario;
use App\Enums\SubmissionStatus;
use App\Notifications\UnsuccessfulSubmission;
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

    public function execute(Submission $submission, TesterData $input, string $testerPath): void
    {
        $submission->update(['status' => SubmissionStatus::Processing]);

        $result = $this->tester->run($input);

        // first of all, save the raw report! We can play with other stuff later
        $submission->update([
            'report' => $result,
        ]);

        collect($result->scenarios)->each(function (TesterScenario $scenario) use ($submission) {
            $hasFailedCases = collect($scenario->cases)->filter(fn (TesterCase $case) => !$case->success)->isNotEmpty();

            $resultScenario = $submission->resultScenarios()->create([
                'test_scenario_id' => $scenario->id,
                'points' => $hasFailedCases ? 0 : TestScenario::find($scenario->id)->first()->points,
            ]);

            collect($scenario->cases)->each(function (TesterCase $case) use ($resultScenario) {
                $resultScenario->resultCases()->create([
                    'build_status' => $case->buildStatus,
                    'gcc_warnings' => $case->gccWarnings,
                    'gcc_errors' => $case->gccErrors,
                    'gcc_macro_defs' => $case->gccMacroDefs,
                    'cmdin' => $case->cmdin,
                    'stdin' => $case->stdin,
                    'stdout' => $case->stdout,
                    'errout' => $case->stderr,
                    'actual_stdout' => $case->actualStdout,
                    'actual_stderr' => $case->actualStderr,
                    'is_success' => $case->success,
                    'messages' => json_encode($case->messages),
                ]);
            });
        });

        try {
            $submission->update([
                'points' => $this->resolvePointsForSubmission->execute($submission),
                'status' => SubmissionStatus::Completed,
//                'build_status' => $result->buildStatus,
//                'gcc_error' => $result->gccError,
//                'gcc_warning' => $result->gccWarning,
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

            $submission->user->notify(new UnsuccessfulSubmission($submission));

            return;
        }

        $submission->user->notify(new SubmissionProcessed($submission));
    }
}
