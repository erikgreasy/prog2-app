<?php

namespace App\Actions;

use App\Models\Submission;

class ResolveSubmissionFolder
{
    /**
     * @return string relative path (inside storage/app)
     */
    public function handle(Submission $submission): string
    {
        $now = now();

        $relativeDiskPath = "students/assignment_{$submission->assignment_id}/user_{$submission->user_id}/try_{$submission->try}__{$now->format('Y-m-d_H-i-s')}";
        
        return $relativeDiskPath;
    }
}
