<?php

namespace App\Dto;

class TestResultCaseMessage
{
    public function __construct(
        public readonly string $type,
        public readonly string $message,
    )
    {
    }
}