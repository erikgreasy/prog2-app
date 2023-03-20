<?php

namespace App\Http\Controllers;

use App\Actions\ProcessAssignmentWithTester;
use App\Actions\StoreSubmission;
use App\Contracts\Tester;
use App\Dto\TesterInput;
use App\Dto\TesterInputCase;
use App\Dto\TesterInputScenario;
use App\Enums\SubmissionSource;
use App\Http\Requests\ManualSubmissionRequest;
use App\Jobs\ProcessManualSubmission;
use App\Models\Assignment;
use App\Models\TestCase;
use App\Models\TestScenario;
use Illuminate\Http\Request;

class ManualAssignmentSubmissionController extends Controller
{
    public function __invoke(
        ManualSubmissionRequest $request, 
        Assignment $assignment, 
        ProcessAssignmentWithTester $processAssignmentWithTester,
        StoreSubmission $storeSubmission,
    ) {
        $user = auth()->user();

        $filePath = $request->file('file')->store("/assignments/{$assignment->id}/{$user->id}");

        $submission = $storeSubmission->execute($request->toDto());

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
                    $inputScenarios->toArray()
                )
            );
        die;

        // ProcessManualSubmission::dispatch(
        //     $assignment->id, 
        //     $user->id, 
        //     storage_path($filePath),
        //     $submission->id,
        // );

        return response()->json([
            'message' => 'Success'
        ]);
        // return response(json_encode($request->all()), 400);
        // check if assignment can be submitted by user
        
        // fetch code

        // send it to tester
        // $result = $tester->run([
        //     'file' => '/path/to/file',
        //     'input' => ' 1 2 3',
        // ]);

        // store results
        return auth()->user()->submissions()->create([
            'assignment_id' => $assignment->id,
            'points' => 10,
            'report' => '',
            'ip' => $request->ip(),
        ]);
    }
}
