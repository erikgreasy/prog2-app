<?php

namespace App\Dto;

class TesterData
{
    /**
     * @param TesterScenario[] $scenarios
     */
    public function __construct(
        public readonly int $id,
        public readonly string $src,
        public readonly array $scenarios,
    )
    {
    }

    public static function fromJson(string $json): self
    {
        $obj = json_decode($json);

        return new self(
            $obj->id,
            $obj->src,
            collect($obj->scenarios)->map(function (object $scenario) {
                return new TesterScenario(
                    $scenario->id,
                    collect($scenario->cases)->map(function (object $case) {
                        return new TesterCase(
                            $case->id,
                            $case->build_status,
                            $case->gcc_errors,
                            $case->gcc_warnings,
                            $case->success,
                            $case->gcc_macro_defs,
                            $case->cmdin,
                            $case->stdin,
                            $case->stdout,
                            $case->stderr,
                            $case->actual_stdout,
                            $case->actual_stdout,
                        );
                    })->toArray()
                );
            })->toArray()
        );

//        return new self(
//            buildStatus: $obj->build_status,
//            gccError: $obj->gcc_error,
//            gccWarning: $obj->gcc_warning,
//            scenarios: collect($obj->scenarios)->map(function (object $scenario) {
//                return new TestResultScenario(
//                    id: $scenario->id,
//                    cases: collect($scenario->cases)->map(function (object $case) {
//                        return new TestResultCase(
//                            id: $case->id,
//                            success: $case->success,
//                            cmdIn: $case->cmdin,
//                            stdIn: $case->stdin,
//                            stdOut: $case->stdout,
//                            stdErr: $case->stderr,
//                            actualStdout: $case->actual_stdout,
//                            actualStderr: $case->actual_stderr,
//                            messages: collect($case->messages)->map(function (object $message) {
//                                return new TestResultCaseMessage(
//                                    $message->type,
//                                    message: $message->message,
//                                );
//                            })->toArray(),
//                        );
//                    })->toArray(),
//                );
//            })->toArray(),
//        );
    }

    public function toJson(): string
    {
        return json_encode([
            'id' => $this->id,
            'src' => $this->src,
            'scenarios' => collect($this->scenarios)->map(function (TesterScenario $scenario) {
                return [
                    'id' => $scenario->id,
                    'cases' => collect($scenario->cases)->map(function (TesterCase $case) {
                        return [
                            'id' => $case->id,
                            'build_status' => $case->buildStatus,
                            'gcc_errors' => $case->gccErrors,
                            'gcc_warnings' => $case->gccWarnings,
                            'success' => $case->success,
                            'gcc_macro_defs' => $case->gccMacroDefs,
                            'cmdin' => $case->cmdin,
                            'stdin' => $case->stdin,
                            'stdout' => $case->stdout,
                            'stderr' => $case->stderr,
                            'actual_stdout' => $case->actualStdout,
                            'actual_stderr' => $case->actualStderr,
                            'messages' => $case->messages,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ]);
    }
}
