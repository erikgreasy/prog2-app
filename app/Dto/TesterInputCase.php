<?php

namespace App\Dto;

class TesterInputCase
{
    public function __construct(
        public readonly int $id,
        public readonly string $cmdIn,
        public readonly string $stdIn,
    )
    {
    }
}
