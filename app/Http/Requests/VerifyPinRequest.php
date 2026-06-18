<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pin' => ['required', 'digits:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'pin.required' => 'PIN is required',
            'pin.digits' => 'PIN must be exactly 6 digits',
        ];
    }
}
