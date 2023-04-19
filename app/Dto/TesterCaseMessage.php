<?php

namespace App\Dto;

class TesterCaseMessage
{
    public function __construct(
        public readonly string $type,
        public readonly string $message,
    )
    {
    }
}
