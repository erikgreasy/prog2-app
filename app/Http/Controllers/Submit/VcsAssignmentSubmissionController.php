<?php

namespace App\Http\Controllers\Submit;

use App\Actions\FetchAndProcessSubmission;
use App\Actions\StoreSubmission;
use App\Dto\StoreSubmissionDto;
use App\Enums\SubmissionSource;
use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Notifications\UnsuccessfulSubmission;
use Illuminate\Http\Request;

class VcsAssignmentSubmissionController extends Controller
{
    public function __invoke(
        Request $request,
        Assignment $assignment,
        StoreSubmission $storeSubmission,
        FetchAndProcessSubmission $fetchAndProcessSubmission,
    ) {
        $submission = $storeSubmission->execute(new StoreSubmissionDto(
            assignmentId: $assignment->id,
            userId: auth()->id(),
            ip: $request->ip(),
            source: SubmissionSource::VCS,
        ));

        $fetchAndProcessSubmission->onQueue()->execute($submission);
    }
}
