<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nasabah_id' => ['required', 'uuid', 'exists:nasabah,id'],
            'nomor_rekening' => ['required', 'string', 'unique:rekening,nomor_rekening', 'min:10', 'max:20'],
            'jenis_rekening' => ['required', 'in:tabungan,giro,deposito'],
            'saldo_minimum' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nasabah_id.required' => 'Customer is required',
            'nasabah_id.exists' => 'Customer not found',
            'nomor_rekening.required' => 'Account number is required',
            'nomor_rekening.unique' => 'Account number already exists',
            'nomor_rekening.min' => 'Account number must be at least 10 characters',
            'jenis_rekening.required' => 'Account type is required',
            'jenis_rekening.in' => 'Invalid account type',
            'saldo_minimum.numeric' => 'Minimum balance must be a number',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (is_array($validated)) {
            $validated['saldo_minimum'] = $validated['saldo_minimum'] ?? 0;
            $validated['keterangan'] = $validated['keterangan'] ?? '';
        }

        return $validated;
    }
}
