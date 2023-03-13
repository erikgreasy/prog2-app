<?php

namespace App\Dto;

use App\Dto\TestResultCaseMessage;

class TestResultCase
{
    /**
     * @param TestResultCaseMessage[] $messages
     */
    public function __construct(
        public readonly int $id,
        public readonly bool $success,
        public readonly ?string $cmdIn = null,
        public readonly ?string $stdIn = null,
        public readonly ?string $stdOut = null,
        public readonly ?string $stdErr = null,
        public readonly ?string $actualStdout = null,
        public readonly ?string $actualStderr = null,
        public readonly array $messages = []
    )
    {
    }
}
