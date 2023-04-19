<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestCaseRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cmdin' => ['required', 'string'],
            'stdin' => ['required', 'string'],
            'stdout' => ['required', 'string'],
            'errout' => ['required', 'string'],
        ];
    }
}
