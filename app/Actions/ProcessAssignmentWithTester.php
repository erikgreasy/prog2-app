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
        info('start of the job');
        $submission->update(['status' => SubmissionStatus::Processing]);

        info('status updated to processing');

        try {
            $result = $this->tester->run($input);
        } catch (\Exception $e) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(
                actualOutput: $e->getMessage(),
                publicOutput: 'Nepodarilo sa ohodnotiť zadanie.',
            ));

            return;
        }

        info('cmd tester complete');

        // first of all, save the raw report! We can play with other stuff later
        $submission->update([
            'report' => $result,
        ]);

        info('updated report to submission');

        $this->storeTesterOutput->execute($result, $submission);

        info('stored output to DB structures');

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

        info('completed now, lets notify');

        $submission->user->notify(new SubmissionProcessed($submission));
    }
}
