<?php

namespace App\Http\Controllers;

use App\Actions\DecideWhetherUserCanSubmitAssignment;
use App\Dto\TesterInput;
use App\Models\TestCase;
use App\Contracts\Tester;
use App\Models\Assignment;
use App\Dto\TesterInputCase;
use App\Models\TestScenario;
use Illuminate\Http\Request;
use App\Enums\SubmissionSource;
use App\Dto\TesterInputScenario;
use App\Actions\ProcessAssignmentWithTester;
use App\Actions\StoreSubmission;
use App\Dto\StoreSubmissionDto;

class VcsAssignmentSubmissionController extends Controller
{
    public function __invoke(
        Request $request,
        Assignment $assignment,
        ProcessAssignmentWithTester $processAssignmentWithTester,
        StoreSubmission $storeSubmission,
    ) {
        $submission = $storeSubmission->execute(new StoreSubmissionDto(
            assignmentId: $assignment->id,
            userId: auth()->id(),
            ip: $request->ip(),
            source: SubmissionSource::VCS,
        ));

        // clone the code

        $processAssignmentWithTester->onQueue()->execute(
            $submission,
            new TesterInput(
                '/whatever/path',
                $assignment->testScenarios->map(function (TestScenario $scenario) {
                    return new TesterInputScenario(
                        $scenario->id,
                        $scenario->cases->map(function (TestCase $case) {
                            return new TesterInputCase(
                                $case->id,
                                $case->cmd_in,
                                $case->std_in
                            );
                        })->toArray()
                    );
                })->toArray(),
                $assignment->tester_path,
            )
        );
    }
}
