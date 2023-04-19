<?php

namespace App\Dto;

use App\Dto\TesterCaseMessage;

class TesterCase
{
    /**
     * @param TesterCaseMessage[] $messages
     */
    public function __construct(
        public readonly int $id,
        public readonly bool $buildStatus = false,
        public readonly array $gccErrors = [],
        public readonly array $gccWarnings = [],
        public readonly bool $success = false,
        public readonly ?string $gccMacroDefs = null,
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
