<?php

namespace App\Dto;

class SearchResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $type,
    )
    {
    }
}
