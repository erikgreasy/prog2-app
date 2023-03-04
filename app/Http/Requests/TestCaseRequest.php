<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestCaseRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cmd_in' => ['required', 'string'],
            'std_in' => ['required', 'string'],
            'std_out' => ['required', 'string'],
            'err_out' => ['required', 'string'],
        ];
    }
}
