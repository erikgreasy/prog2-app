<?php

namespace App\Http\Controllers;

use App\Dto\TesterInput;
use App\Models\TestCase;
use App\Contracts\Tester;
use App\Models\Assignment;
use App\Dto\TesterInputCase;
use App\Models\TestScenario;
use Illuminate\Http\Request;
use App\Enums\SubmissionSource;
use App\Actions\StoreSubmission;
use App\Dto\TesterInputScenario;
use App\Jobs\ProcessManualSubmission;
use Illuminate\Support\Facades\Storage;
use App\Actions\ProcessAssignmentWithTester;
use App\Http\Requests\ManualSubmissionRequest;

class ManualAssignmentSubmissionController extends Controller
{
    public function __invoke(
        ManualSubmissionRequest $request, 
        Assignment $assignment, 
        ProcessAssignmentWithTester $processAssignmentWithTester,
        StoreSubmission $storeSubmission,
    ) {
        $user = auth()->user();

        $filePath = Storage::path($request->file('file')->store("/assignments/{$assignment->id}/{$user->id}"));

        $submission = $storeSubmission->execute($request->toDto($filePath));

        $inputScenarios = $assignment->testScenarios->map(function (TestScenario $scenario) {
            $inputCases = $scenario->cases->map(function (TestCase $case) {
                return new TesterInputCase(
                    $case->id,
                    $case->cmd_in,
                    $case->std_in
                );
            });

            return new TesterInputScenario(
                $scenario->id,
                $inputCases->toArray()
            );
        });

        $processAssignmentWithTester
            ->onQueue()
            ->execute(
                $submission,
                new TesterInput(
                    $filePath,
                    $inputScenarios->toArray(),
                    $assignment->tester_path,
                )
            );
        die;

        return response()->json([
            'message' => 'Success'
        ]);
    }
}
