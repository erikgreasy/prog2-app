<?php

namespace App\Actions;

use App\Dto\TesterCase;
use App\Dto\TesterData;
use App\Dto\TesterScenario;
use App\Exceptions\WrongRepositoryStructureException;
use App\Models\TestCase;
use App\Models\Submission;
use App\Models\TestScenario;
use App\Enums\SubmissionStatus;
use App\Github\Exceptions\BranchNotFoundException;
use App\Github\Exceptions\FailedCloneException;
use App\Github\Exceptions\RepositoryNotFoundException;
use App\Notifications\UnsuccessfulSubmission;
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

            $submission->user->notify(new UnsuccessfulSubmission($submission));

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

            $submission->user->notify(new UnsuccessfulSubmission($submission));

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

            $submission->user->notify(new UnsuccessfulSubmission($submission));

            return;
        } catch (WrongRepositoryStructureException $e) {
            $submission->update([
                'points' => 0,
                'status' => SubmissionStatus::Failed,
                'fail_messages' => [
                    'exception' => WrongRepositoryStructureException::class,
                    'actual_output' => $e->getMessage(),
                    'public_output' => 'Repozitár na danej vetve obsahoval viacero súborov typu c.',
                ],
            ]);

            $submission->user->notify(new UnsuccessfulSubmission($submission));

            return;
        } catch (\Exception $e) {
            $submission->update([
                'points' => 0,
                'status' => SubmissionStatus::Failed,
                'fail_messages' => [
                    'exception' => \Exception::class,
                    'actual_output' => $e->getMessage(),
                    'public_output' => 'Pri vyhodnotení nastala iná chyba.',
                ],
            ]);

            $submission->user->notify(new UnsuccessfulSubmission($submission));

            return;
        }

        $submission->update([
            'file_path' => $filePath,
            'file_content' => File::get($filePath),
        ]);

        $this->processAssignmentWithTester->onQueue()->execute(
            $submission,
            new TesterData(
                $submission->id,
                $filePath,
                $submission->assignment->testScenarios->map(function (TestScenario $scenario) {
                    return new TesterScenario(
                        $scenario->id,
                        $scenario->cases->map(function (TestCase $case) {
                            return new TesterCase(
                                id: $case->id,
                                gccMacroDefs: $case->gcc_macro_defs,
                                cmdIn: $case->cmd_in,
                                stdIn: $case->std_in,
                                stdOut: $case->std_out,
                                stdErr: $case->std_err,
                            );
                        })->toArray()
                    );
                })->toArray(),
            ),
            $submission->assignment->tester_path,
        );
    }
}
