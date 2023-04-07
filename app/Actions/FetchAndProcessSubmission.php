<?php

namespace App\Actions;

use App\Dto\TesterInput;
use App\Models\TestCase;
use App\Models\Submission;
use App\Dto\TesterInputCase;
use App\Models\TestScenario;
use App\Dto\TesterInputScenario;
use App\Enums\SubmissionStatus;
use App\Github\Exceptions\BranchNotFoundException;
use App\Github\Exceptions\FailedCloneException;
use App\Github\Exceptions\RepositoryNotFoundException;
use Illuminate\Support\Facades\File;
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
        try {
            $filePath = $this->fetchCodeFromVcs->execute($submission->user, $submission);
        } catch (BranchNotFoundException $e) {
            $submission->update([
                'points' => 0,
                'status' => SubmissionStatus::Failed,
                'fail_messages' => [
                    'exception' => BranchNotFoundException::class,
                    'actual_output' => $e->getMessage(),
                    'public_output' => 'Branchka nebola najdena',
                ],
            ]);

            return;
        } catch (RepositoryNotFoundException $e) {
            $submission->update([
                'points' => 0,
                'status' => SubmissionStatus::Failed,
                'fail_messages' => [
                    'exception' => RepositoryNotFoundException::class,
                    'actual_output' => $e->getMessage(),
                    'public_output' => 'Repo nebolo najdene',
                ],
            ]);

            return;
        } catch (FailedCloneException $e) {
            $submission->update([
                'points' => 0,
                'status' => SubmissionStatus::Failed,
                'fail_messages' => [
                    'exception' => FailedCloneException::class,
                    'actual_output' => $e->getMessage(),
                    'public_output' => 'Nepodarilo sa clonovat repo.',
                ],
            ]);

            return;
        }

        $submission->update([
            'file_path' => $filePath,
            'file_content' => File::get($filePath),
        ]);
        
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
