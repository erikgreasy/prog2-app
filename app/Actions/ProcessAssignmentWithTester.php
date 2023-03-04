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

        $submission->update([
            'report' => $result
        ]);

        collect($result->scenarios)->each(function (TestResultScenario $scenario) use ($submission) {
            $resultScenario = $submission->resultScenarios()->create([
                'test_scenario_id' => $scenario->id
            ]);

            collect($scenario->cases)->each(function (TestResultCase $case) use ($resultScenario) {
                $resultScenario->resultCases()->create([
                    'std_out' => $case->stdOut,
                    'err_out' => $case->stdErr,
                    'is_success' => $case->success,
                    'messages' => json_encode($case->messages),
                ]);
            });
        });
    }
}
