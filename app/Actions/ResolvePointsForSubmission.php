<?php

namespace App\Actions;

use App\Models\Submission;

class ResolvePointsForSubmission
{
    /**
     * Count points for the submission based on the actual try no. and
     * the specified max points for that try no. in assignment
     */
    public function execute(Submission $submission): float
    {
        $actualPoints = $submission->resultScenarios()->sum('points');
        
        if ($submission->try > $submission->assignment->maxTries()) {
            throw new \Exception("Trying to calculate points for try(try no. {$submission->try}), that ist bigger than max no. of tries ({$submission->assignment->maxTries()}) for assignment.");
        }

        $maxPointsForTry = $submission->assignment->tries[$submission->try - 1];

        if ($maxPointsForTry['max_points'] < $actualPoints) {
            return $maxPointsForTry['max_points'];
        }

        return $actualPoints;
    }
}
