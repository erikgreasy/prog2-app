<?php

namespace App\Http\Controllers\Submit;

use App\Actions\ProcessAssignmentWithTester;
use App\Actions\ResolveSubmissionFolder;
use App\Actions\StoreSubmission;
use App\Dto\TesterCase;
use App\Dto\TesterData;
use App\Dto\TesterInput;
use App\Dto\TesterInputCase;
use App\Dto\TesterInputScenario;
use App\Dto\TesterScenario;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManualSubmissionRequest;
use App\Models\Assignment;
use App\Models\TestCase;
use App\Models\TestScenario;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ManualAssignmentSubmissionController extends Controller
{
    public function __invoke(
        ManualSubmissionRequest $request,
        Assignment $assignment,
        ProcessAssignmentWithTester $processAssignmentWithTester,
        StoreSubmission $storeSubmission,
        ResolveSubmissionFolder $resolveSubmissionFolder,
    ) {
        $submission = $storeSubmission->execute($request->toDto());

        $relativeDiskPath = $resolveSubmissionFolder->handle($submission);

        $filePath = Storage::path(
            $request->file('file')->storeAs($relativeDiskPath, $request->file('file')->getClientOriginalName())
        );

        $submission->update([
            'file_path' => $filePath,
            'file_content' => File::get($filePath),
        ]);

        $processAssignmentWithTester
            ->onQueue()
            ->execute(
                $submission,
                new TesterData(
                    $submission->id,
                    $filePath,
                    $assignment->getTesterScenariosArray(),
                ),
                $assignment->tester_path,
            );

        return response()->json([
            'message' => 'Success'
        ]);
    }
}
