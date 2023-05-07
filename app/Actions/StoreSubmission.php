<?php

namespace App\Actions;

use App\Dto\StoreSubmissionDto;
use App\Enums\SubmissionStatus;
use App\Models\Submission;

class StoreSubmission
{
    public function execute(StoreSubmissionDto $storeSubmissionDto): Submission
    {
        Submission::where('assignment_id', $storeSubmissionDto->assignmentId)
            ->where('user_id', $storeSubmissionDto->userId)
            ->update(['is_final' => false]);

        return Submission::create([
            'user_id' => $storeSubmissionDto->userId,
            'assignment_id' => $storeSubmissionDto->assignmentId,
            'try' => $storeSubmissionDto->try ?? $this->resolveTry($storeSubmissionDto),
            'source' => $storeSubmissionDto->source,
            'ip' => $storeSubmissionDto->ip,
            'report' => $storeSubmissionDto->report,
            'file_path' => $storeSubmissionDto->filePath,
            'status' => SubmissionStatus::Created,
            'is_final' => true,
        ]);
    }

    /**
     * Calculate the try no. of the submission for user for assignment.
     */
    private function resolveTry(StoreSubmissionDto $storeSubmissionDto): int
    {
        $try = Submission::query()
            ->completed()
            ->where('assignment_id', $storeSubmissionDto->assignmentId)
            ->where('user_id', $storeSubmissionDto->userId)
            ->max('try') ?? 0;

        return $try + 1;
    }
}
