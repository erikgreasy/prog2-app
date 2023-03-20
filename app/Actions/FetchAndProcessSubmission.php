<?php

namespace App\Actions;

use App\Dto\TesterInput;
use App\Models\TestCase;
use App\Models\Submission;
use App\Dto\TesterInputCase;
use App\Models\TestScenario;
use App\Dto\TesterInputScenario;
use Spatie\QueueableAction\QueueableAction;

class FetchAndProcessSubmission
{
    use QueueableAction;

    public function __construct(
        private FetchCodeFromVcs $fetchCodeFromVcs,
        private ProcessAssignmentWithTester $processAssignmentWithTester,
    )
    {
    }
    
    public function execute(Submission $submission): void
    {
        $filePath = $this->fetchCodeFromVcs->execute($submission->user, $submission);
        
        $this->processAssignmentWithTester->onQueue()->execute(
            $submission,
            new TesterInput(
                $filePath,
                $submission->assignment->testScenarios->map(function (TestScenario $scenario) {
                    return new TesterInputScenario(
                        $scenario->id,
                        $scenario->cases->map(function (TestCase $case) {
                            return new TesterInputCase(
                                $case->id,
                                $case->cmd_in,
                                $case->std_in
                            );
                        })->toArray()
                    );
                })->toArray(),
                $submission->assignment->tester_path,
            )
        );
    }
}
