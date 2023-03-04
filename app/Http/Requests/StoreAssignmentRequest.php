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
            'materials' => ['array'],
            'points' => ['required', 'numeric', 'min:0'],
            'submission_instructions' => ['nullable', 'string'],
            'materials.*.id' => ['nullable', 'integer'],
            'materials.*.src' => ['required'],
        ];
    }
}
