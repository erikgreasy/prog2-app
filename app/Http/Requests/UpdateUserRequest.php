<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'role' => 'in:' . implode(',', array_column(Role::cases(), 'value'))
        ];
    }
}
