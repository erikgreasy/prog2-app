<?php

namespace App\Dto;

use App\Dto\TestResultCase;

class TestResultScenario
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
