<?php

namespace App\Dto;

use App\Dto\TesterInputScenario;

class TesterInput
{
    /**
     * @param TesterInputScenario[] $scenarios
     */
    public function __construct(
        public readonly string $src,
        public readonly array $scenarios,
        public readonly ?string $testerPath = null,
    )
    {
    }
}
