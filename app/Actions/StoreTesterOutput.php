<?php

namespace App\Actions;

use App\Dto\TesterCase;
use App\Dto\TesterData;
use App\Dto\TesterScenario;
use App\Models\Submission;
use App\Models\TestScenario;

class StoreTesterOutput
{
    /**
     * Store tester data object into database, structured into tables.
     */
    public function execute(TesterData $output, Submission $submission): void
    {
        collect($output->scenarios)->each(function (TesterScenario $scenario) use ($submission) {
            $hasFailedCases = collect($scenario->cases)->filter(fn (TesterCase $case) => !$case->success)->isNotEmpty();

            $resultScenario = $submission->resultScenarios()->create([
                'test_scenario_id' => $scenario->id,
                'points' => $hasFailedCases ? 0 : TestScenario::find($scenario->id)->first()->points,
            ]);

            collect($scenario->cases)->each(function (TesterCase $case) use ($resultScenario) {
                $resultScenario->resultCases()->create([
                    'build_status' => $case->buildStatus,
                    'gcc_warnings' => $case->gccWarnings,
                    'gcc_errors' => $case->gccErrors,
                    'gcc_macro_defs' => $case->gccMacroDefs,
                    'cmdin' => $case->cmdin,
                    'stdin' => $case->stdin,
                    'stdout' => $case->stdout,
                    'errout' => $case->stderr,
                    'actual_stdout' => $case->actualStdout,
                    'actual_stderr' => $case->actualStderr,
                    'is_success' => $case->success,
                    'messages' => json_encode($case->messages),
                ]);
            });
        });
    }
}
