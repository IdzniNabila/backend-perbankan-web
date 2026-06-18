<?php

namespace App\Services;

use App\Models\Rekening;
use App\Models\User;

class ValidationService
{
    public function validateTransferRequest(
        string $source_account_id,
        string $destination_account_id,
        float $amount,
        string $pin
    ): array {
        $errors = [];

        if ($amount < 10000) {
            $errors['amount'][] = 'Minimum transfer amount is Rp 10.000';
        }

        if ($amount > 999999999) {
            $errors['amount'][] = 'Maximum transfer amount is Rp 999.999.999';
        }

        if (!is_numeric($amount) || $amount <= 0) {
            $errors['amount'][] = 'Amount must be a positive number';
        }

        if (!preg_match('/^\d{6}$/', $pin)) {
            $errors['pin'][] = 'PIN must be exactly 6 digits';
        }

        if ($source_account_id === $destination_account_id) {
            $errors['destination'][] = 'Cannot transfer to the same account';
        }

        $source = Rekening::find($source_account_id);
        if (!$source) {
            $errors['source'][] = 'Source account not found';
        } elseif ($source->status !== 'aktif') {
            $errors['source'][] = 'Source account is inactive';
        } elseif ($source->saldo < $amount) {
            $errors['amount'][] = 'Insufficient balance in source account';
        }

        $destination = Rekening::find($destination_account_id);
        if (!$destination) {
            $errors['destination'][] = 'Destination account not found';
        } elseif ($destination->status !== 'aktif') {
            $errors['destination'][] = 'Destination account is inactive';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    public function validateLoginRequest(string $username, string $password): array
    {
        $errors = [];

        if (empty($username)) {
            $errors['username'][] = 'Username is required';
        }

        if (empty($password)) {
            $errors['password'][] = 'Password is required';
        }

        $user = User::where('username', $username)->first();
        if ($user && $user->status !== 'aktif') {
            $errors['username'][] = 'This account is not active';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    public function validatePin(string $pin): array
    {
        $errors = [];

        if (empty($pin)) {
            $errors['pin'][] = 'PIN is required';
        }

        if (!preg_match('/^\d{6}$/', $pin)) {
            $errors['pin'][] = 'PIN must be exactly 6 digits';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    public function validateAccount(string $account_id): array
    {
        $errors = [];

        $account = Rekening::find($account_id);
        if (!$account) {
            $errors['account'][] = 'Account not found';
        } elseif ($account->status !== 'aktif') {
            $errors['account'][] = 'Account is inactive';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    public function validateAmount(float $amount, bool $is_transfer = true): array
    {
        $errors = [];
        $min_amount = $is_transfer ? 10000 : 1000;
        $max_amount = 999999999;

        if ($amount < $min_amount) {
            $errors['amount'][] = "Minimum amount is Rp " . number_format($min_amount, 0, ',', '.');
        }

        if ($amount > $max_amount) {
            $errors['amount'][] = "Maximum amount is Rp " . number_format($max_amount, 0, ',', '.');
        }

        if ($amount <= 0) {
            $errors['amount'][] = 'Amount must be greater than zero';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }
}
