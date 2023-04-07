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
            'file' => ['required', 'file', 'mimes:c', 'max:1024']
        ];
    }

    public function toDto(): StoreSubmissionDto
    {
        return new StoreSubmissionDto(
            assignmentId: $this->route('assignment')->id,
            userId: auth()->id(),
            ip: $this->ip(),
            source: SubmissionSource::MANUAL,
        );
    }
}
