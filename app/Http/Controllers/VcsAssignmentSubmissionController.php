<?php

namespace App\Http\Controllers;

use App\Actions\FetchAndProcessSubmission;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Enums\SubmissionSource;
use App\Actions\StoreSubmission;
use App\Dto\StoreSubmissionDto;

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
