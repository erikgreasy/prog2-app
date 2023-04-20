<?php

namespace App\Actions;

use App\Dto\FailMessage;
use App\Dto\TesterData;
use App\Exceptions\WrongRepositoryStructureException;
use App\Github\Exceptions\BranchNotFoundException;
use App\Github\Exceptions\FailedCloneException;
use App\Github\Exceptions\RepositoryNotFoundException;
use App\Models\Submission;
use Illuminate\Support\Facades\File;
use Spatie\QueueableAction\QueueableAction;

class FetchAndProcessSubmission
{
    use QueueableAction;

    public function __construct(
        private FetchCodeFromVcs            $fetchCodeFromVcs,
        private ProcessAssignmentWithTester $processAssignmentWithTester,
        private AbortSubmissionProcessing   $abortSubmissionProcessing,
    )
    {
    }

    public function execute(Submission $submission): void
    {
        try {
            $filePath = $this->fetchCodeFromVcs->execute($submission->user, $submission);
        } catch (BranchNotFoundException $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                BranchNotFoundException::class,
                $e->getMessage(),
                'Požadovaná vetva nebola nájdená v repozitári.'
            ));

            return;
        } catch (RepositoryNotFoundException $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                RepositoryNotFoundException::class,
                $e->getMessage(),
                'Pripojený repozitár nebol nájdený. Nezmenili ste jeho názov?'
            ));

            return;
        } catch (FailedCloneException $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                FailedCloneException::class,
                $e->getMessage(),
                'Nebolo možné získať zdrojový kód z repozitára.'
            ));

            return;
        } catch (WrongRepositoryStructureException $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                WrongRepositoryStructureException::class,
                $e->getMessage(),
                'Repozitár na danej vetve obsahoval viacero súborov typu c.',
            ));

            return;
        } catch (\Exception $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                actualOutput: 'Pri vyhodnotení nastala iná chyba.',
                publicOutput: $e->getMessage(),
            ));

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
                $submission->assignment->getTesterScenariosArray()
            ),
            $submission->assignment->tester_path,
        );
    }
}
