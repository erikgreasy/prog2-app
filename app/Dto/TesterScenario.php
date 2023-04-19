<?php

namespace App\Dto;

use App\Dto\TesterCase;

class TesterScenario
{
    /**
     * @param TesterCase[] $cases
     */
    public function __construct(
        public readonly int $id,
        public readonly array $cases,
    )
    {
    }
}
