<?php

namespace App\Actions;

use App\Dto\FailMessage;
use App\Dto\TesterData;
use App\Contracts\Tester;
use App\Models\Submission;
use App\Enums\SubmissionStatus;
use App\Notifications\SubmissionProcessed;
use Spatie\QueueableAction\QueueableAction;

class ProcessAssignmentWithTester
{
    use QueueableAction;

    public function __construct(
        private Tester $tester,
        private ResolvePointsForSubmission $resolvePointsForSubmission,
        private AbortSubmissionProcessing $abortSubmissionProcessing,
        private StoreTesterOutput $storeTesterOutput,
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

        $this->storeTesterOutput->execute($result, $submission);

        try {
            $submission->update([
                'points' => $this->resolvePointsForSubmission->execute($submission),
                'status' => SubmissionStatus::Completed,
            ]);
        } catch (\Exception $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                actualOutput: $e->getMessage(),
                publicOutput: 'Nepodarilo sa ohodnotiť zadanie.',
            ));

            return;
        }

        $submission->user->notify(new SubmissionProcessed($submission));
    }
}
