<?php

namespace App\Actions;

use App\Models\User;
use App\Dto\TesterInput;
use App\Contracts\Tester;
use App\Dto\TestResultCase;
use App\Dto\TestResultScenario;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\SubmissionTestScenario;
use App\Models\TestScenario;
use Spatie\QueueableAction\QueueableAction;

class ProcessAssignmentWithTester
{
    use QueueableAction;

    public function __construct(private Tester $tester)
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
            'points' => $this->resolvePoints($submission),
        ]);
    }

    private function resolvePoints(Submission $submission): float
    {
        $actualPoints = $submission->resultScenarios()->sum('points');
        
        $maxPoints =  match($submission->try) {
            1 => 10,
            2 => 9,
            3 => 7,
            4 => 5,
            5 => 3,
            6 => 1,
            default => 0
        };

        if ($actualPoints > $maxPoints) {
            return $maxPoints;
        }

        return $actualPoints;
    }
}
