<?php

namespace App\Actions;

use App\Dto\FailMessage;
use App\Enums\SubmissionStatus;
use App\Models\Submission;
use App\Notifications\UnsuccessfulSubmission;

class AbortSubmissionProcessing
{
    public function execute(Submission $submission, FailMessage $failMessage): void
    {
        $submission->update([
            'points' => 0,
            'status' => SubmissionStatus::Failed,
            'fail_messages' => $failMessage->toArray()
        ]);

        $submission->user->notify(new UnsuccessfulSubmission($submission));
    }
}
