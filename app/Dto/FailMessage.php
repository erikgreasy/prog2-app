<?php

namespace App\Dto;

class FailMessage
{
    public function __construct(
        public readonly string $exception = \Exception::class,
        public readonly string $actualOutput = '',
        public readonly string $publicOutput = '',
    )
    {
    }

    public function toArray(): array
    {
        return [
            'exception' => $this->exception,
            'actual_output' => $this->actualOutput,
            'public_output' => $this->publicOutput,
        ];
    }
}
