<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestScenarioRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'points' => ['required', 'numeric', 'min:0'],
            'cases' => ['array'],
            'cases.*.id' => ['nullable', 'numeric', 'integer'],
            'cases.*.gcc_macro_defs' => ['nullable', 'string', 'max:255'],
            'cases.*.cmdin' => ['nullable', 'string', 'max:255'],
            'cases.*.stdin' => ['nullable', 'string', 'max:255'],
            'cases.*.stdout' => ['nullable', 'string', 'max:255'],
            'cases.*.errout' => ['nullable', 'string', 'max:255'],
        ];
    }
}
