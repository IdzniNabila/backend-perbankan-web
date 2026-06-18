<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_account_id' => ['required', 'uuid', 'exists:rekening,id'],
            'destination_account_id' => ['required', 'uuid', 'exists:rekening,id', 'different:source_account_id'],
            'amount' => ['required', 'numeric', 'min:10000', 'max:999999999'],
            'pin' => ['required', 'digits:6'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'source_account_id.required' => 'Source account is required',
            'source_account_id.uuid' => 'Invalid source account format',
            'source_account_id.exists' => 'Source account not found',
            'destination_account_id.required' => 'Destination account is required',
            'destination_account_id.uuid' => 'Invalid destination account format',
            'destination_account_id.exists' => 'Destination account not found',
            'destination_account_id.different' => 'Cannot transfer to the same account',
            'amount.required' => 'Transfer amount is required',
            'amount.numeric' => 'Amount must be a number',
            'amount.min' => 'Minimum transfer amount is Rp 10.000',
            'amount.max' => 'Maximum transfer amount is Rp 999.999.999',
            'pin.required' => 'PIN is required',
            'pin.digits' => 'PIN must be exactly 6 digits',
            'description.max' => 'Description cannot exceed 255 characters',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (is_array($validated)) {
            $validated['amount'] = (float) $validated['amount'];
            $validated['description'] = $validated['description'] ?? 'Transfer between accounts';
        }

        return $validated;
    }
}
