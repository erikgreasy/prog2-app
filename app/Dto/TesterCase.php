<?php

namespace App\Dto;

use App\Dto\TesterCaseMessage;

class TesterCase
{
    /**
     * @param TesterCaseMessage[] $messages
     */
    public function __construct(
        public readonly int     $id,
        public readonly bool    $buildStatus = false,
        public readonly array   $gccErrors = [],
        public readonly array   $gccWarnings = [],
        public readonly bool    $success = false,
        public readonly ?string $gccMacroDefs = null,
        public readonly ?string $cmdin = null,
        public readonly ?string $stdin = null,
        public readonly ?string $stdout = null,
        public readonly ?string $stderr = null,
        public readonly ?string $actualStdout = null,
        public readonly ?string $actualStderr = null,
        public readonly array   $messages = []
    )
    {
    }
}
