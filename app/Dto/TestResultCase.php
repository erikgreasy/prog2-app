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
        public readonly string $stdOut,
        public readonly string $stdErr,
        public readonly bool $success,
        public readonly array $messages = []
    )
    {
    }
}
