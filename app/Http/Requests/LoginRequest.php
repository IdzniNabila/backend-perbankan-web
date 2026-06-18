<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'device_name' => ['nullable', 'string', 'max:100'],
            'device_type' => ['nullable', 'in:web,mobile,tablet,desktop'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required',
            'username.min' => 'Username must be at least 3 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'device_type.in' => 'Invalid device type',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (is_array($validated)) {
            $validated['device_name'] = $validated['device_name'] ?? 'Web Browser';
            $validated['device_type'] = $validated['device_type'] ?? 'web';
        }

        return $validated;
    }
}
