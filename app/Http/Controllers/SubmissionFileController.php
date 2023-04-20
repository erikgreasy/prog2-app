<?php

namespace App\Http\Controllers;

use App\Models\Submission;

class SubmissionFileController
{
    public function __invoke(Submission $submission)
    {
        return response()->download($submission->file_path);
    }
}
