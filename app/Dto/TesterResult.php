<?php

namespace App\Dto;

use App\Dto\TestResultScenario;

class TesterResult
{
    /**
     * @param TestResultScenario[] $scenarios
     */
    public function __construct(
        public readonly string $buildStatus,
        public readonly string $gccError,
        public readonly string $gccWarning,
        public readonly array $scenarios,
    )
    {
    }
}
