<?php

namespace App\Http\Requests;

use App\Enums\AssignmentStatus;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'slug' => ['required', 'string', "unique:assignments,slug,{$this->id}"],
            'excerpt' => ['required', 'string'],
            'deadline' => ['required', 'date'],
            'content' => ['nullable'],
            'status' => ['in:' . implode(',', array_column(AssignmentStatus::cases(), 'value'))],
        ];
    }
}
