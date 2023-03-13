<?php

namespace App;

use App\Contracts\Tester;
use App\Dto\TesterInput;
use App\Dto\TesterInputCase;
use App\Dto\TesterInputScenario;
use App\Dto\TesterResult;
use App\Dto\TestResultCase;
use App\Dto\TestResultScenario;
use Illuminate\Support\Facades\Log;

class DummyTester implements Tester
{
    public function run(TesterInput $input): TesterResult
    {
        // Log::error("Running dummy tester, with input {$input} on file {$filePath}");

        $resultScenarios = collect($input->scenarios)->map(function (TesterInputScenario $scenario) {
            $cases = collect($scenario->cases)->map(function (TesterInputCase $case) {
                return new TestResultCase(
                    $case->id,
                    true,
                    'cmd in',
                    'std in',
                    'std out',
                    'std err',
                    'actual_stdout',
                    'actual stderr'
                );
            });

            return new TestResultScenario(
                $scenario->id,
                $cases->toArray()
            );
        });

        return new TesterResult(
            "OK",
            "",
            "",
            $resultScenarios->toArray()
        );
    }
}