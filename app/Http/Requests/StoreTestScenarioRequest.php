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
            'cases.*.cmd_in' => ['nullable', 'string', 'max:255'],
            'cases.*.std_in' => ['nullable', 'string', 'max:255'],
            'cases.*.std_out' => ['nullable', 'string', 'max:255'],
            'cases.*.err_out' => ['nullable', 'string', 'max:255'],
        ];
    }
}
