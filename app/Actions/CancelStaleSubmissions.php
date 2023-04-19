<?php

namespace App\Actions;

use App\Dto\FailMessage;
use App\Enums\SubmissionStatus;
use App\Github\Exceptions\BranchNotFoundException;
use App\Models\Submission;
use App\Notifications\UnsuccessfulSubmission;

class CancelStaleSubmissions
{
    public function __construct(public AbortSubmissionProcessing $abortSubmissionProcessing)
    {
    }

    public function execute(): void
    {
        $submissionsToCancel = Submission::query()
            ->whereIn('status', [SubmissionStatus::Created, SubmissionStatus::Processing])
            ->where('created_at', '<', now()->subMinutes(5)->toDateTimeString())
            ->get();

        foreach ($submissionsToCancel as $submission) {
            $this->abortSubmissionProcessing->execute($submission, new FailMessage(publicOutput: 'Spracovanie odovzdania prekročilo časový limit.'));
        }
    }
}
