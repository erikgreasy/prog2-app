<?php

namespace App\Http\Requests;

use App\Dto\StoreSubmissionDto;
use App\Enums\SubmissionSource;
use Illuminate\Foundation\Http\FormRequest;

class ManualSubmissionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => ['nullable']
        ];
    }

    public function toDto(string $filePath): StoreSubmissionDto
    {
        return new StoreSubmissionDto(
            assignmentId: $this->route('assignment')->id,
            userId: auth()->id(),
            ip: $this->ip(),
            source: SubmissionSource::MANUAL,
            filePath: $filePath,
        );
    }
}
