<?php

namespace App\Dto;

use App\Dto\TestResultScenario;

class TesterResult
{
    /**
     * @param TestResultScenario[] $scenarios
     */
    public function __construct(
        public readonly bool $buildStatus,
        public readonly array $gccError,
        public readonly array $gccWarning,
        public readonly array $scenarios,
    )
    {
    }

    public static function fromJson(string $json): self
    {
        $obj = json_decode($json);

        return new self(
            buildStatus: $obj->build_status,
            gccError: $obj->gcc_error,
            gccWarning: $obj->gcc_warning,
            scenarios: collect($obj->scenarios)->map(function (object $scenario) {
                return new TestResultScenario(
                    id: $scenario->id,
                    cases: collect($scenario->cases)->map(function (object $case) {
                        return new TestResultCase(
                            id: $case->id,
                            success: $case->success,
                            cmdIn: $case->cmdin,
                            stdIn: $case->stdin,
                            stdOut: $case->stdout,
                            stdErr: $case->stderr,
                            actualStdout: $case->actual_stdout,
                            actualStderr: $case->actual_stderr,
                            messages: collect($case->messages)->map(function (object $message) {
                                return new TestResultCaseMessage(
                                    $message->type,
                                    message: $message->message,
                                );
                            })->toArray(),
                        );
                    })->toArray(),
                );
            })->toArray(),
        );
    }
}
