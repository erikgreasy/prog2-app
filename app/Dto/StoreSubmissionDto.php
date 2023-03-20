<?php

namespace App\Dto;

use App\Enums\SubmissionSource;

class StoreSubmissionDto
{
    public function __construct(
        public readonly int $assignmentId,
        public readonly int $userId,
        public readonly string $ip,
        public readonly SubmissionSource $source,
        public readonly ?float $points = null,
        public readonly ?object $report = null,
        public readonly ?string $filePath = null,
    )
    {
    }
}
