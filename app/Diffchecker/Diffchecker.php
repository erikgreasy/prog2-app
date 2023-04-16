<?php

namespace App\Diffchecker;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Diffchecker
{
    private string $baseUrl = 'https://api.diffchecker.com/';

    public function __construct(private string $email)
    {
    }

    public function textDiff(string $left, string $right, OutputType $outputType = OutputType::Json): Response
    {
        return Http::post($this->baseUrl . "public/text?email={$this->email}&output_type={$outputType->value}", [
            'left' => $left,
            'right' => $right,
        ]);
    }
}
