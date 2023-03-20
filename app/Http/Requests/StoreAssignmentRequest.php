<?php

namespace App\Http\Requests;

use App\Enums\AssignmentStatus;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'slug' => ['required', 'string', "unique:assignments,slug,{$this->id}"],
            'excerpt' => ['required', 'string'],
            'deadline' => ['required', 'date'],
            'content' => ['nullable'],
            'status' => ['in:' . implode(',', array_column(AssignmentStatus::cases(), 'value'))],
            'materials' => ['nullable'],
            'tries' => ['nullable', 'array'],
            'tries.*.max_points' => ['required', 'numeric'],
            'published_at' => ['nullable', 'date'],
            'points' => ['required', 'numeric', 'min:0'],
            'submission_instructions' => ['nullable'],
            'tester_path' => ['required', 'string'],
            'vcs_branch' => ['required', 'string'],
            'vcs_filename' => ['required', 'string'],
        ];
    }
}
