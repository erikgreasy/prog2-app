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
use App\Dto\TesterInputScenario;
use App\Actions\ProcessAssignmentWithTester;

class VcsAssignmentSubmissionController extends Controller
{
    public function __invoke(Request $request, Assignment $assignment, Tester $tester, ProcessAssignmentWithTester $processAssignmentWithTester)
    {
        // check if assignment can be submitted by user

        $submission = auth()->user()->submissions()->create([
            'assignment_id' => $assignment->id,
            'ip' => $request->ip(),
            'source' => SubmissionSource::VCS,
        ]);

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
                })->toArray()
            )
        );
    }
}
