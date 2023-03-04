<?php

namespace App\Dto;

use App\Dto\TestResultCase;

class TesterInputScenario
{
    /**
     * @param TestResultCase[] $cases
     */
    public function __construct(
        public readonly int $id,
        public readonly array $cases,
    )
    {
    }
}
