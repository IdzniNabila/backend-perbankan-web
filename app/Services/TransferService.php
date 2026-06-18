<?php

namespace App\Services;

use App\Models\Rekening;
use App\Models\Mutasi;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransferService
{
    public function transfer(
        string $source_id,
        string $destination_id,
        float $amount,
        string $description,
        string $user_id,
        string $ip_address,
        string $user_agent,
        int $pin_verified = 0
    ): array {
        if (!$pin_verified) {
            throw ValidationException::withMessages([
                'pin' => 'PIN verification required before transfer'
            ]);
        }

        return DB::transaction(function () use (
            $source_id,
            $destination_id,
            $amount,
            $description,
            $user_id,
            $ip_address,
            $user_agent
        ) {
            $source = Rekening::where('id', $source_id)
                ->lockForUpdate()
                ->firstOrFail();

            $destination = Rekening::where('id', $destination_id)
                ->lockForUpdate()
                ->firstOrFail();

            // FIX (KEAMANAN KRITIS): sebelumnya tidak ada pengecekan kepemilikan,
            // sehingga user A bisa mentransfer dana KELUAR dari rekening user B
            // hanya dengan mengetahui UUID rekening tersebut (karena PIN juga hanya
            // dicek formatnya, lihat fix di TransferController). Sekarang rekening
            // sumber wajib milik nasabah yang terhubung ke user yang sedang login.
            $source->loadMissing('nasabah');
            if (!$source->nasabah || $source->nasabah->user_id !== $user_id) {
                throw ValidationException::withMessages([
                    'source' => 'You are not authorized to transfer from this account'
                ]);
            }

            if ($source->id === $destination->id) {
                throw ValidationException::withMessages([
                    'destination' => 'Cannot transfer to the same account'
                ]);
            }

            if ($source->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'source' => 'Source account is inactive'
                ]);
            }

            if ($destination->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'destination' => 'Destination account is inactive'
                ]);
            }

            if (!$source->isSaldoCukup($amount)) {
                throw ValidationException::withMessages([
                    'amount' => 'Insufficient balance for this transfer'
                ]);
            }

            $reference_id = $this->generateTransactionReference();

            $saldo_source_before = $source->saldo;
            $saldo_source_after = $source->saldo - $amount;

            $saldo_dest_before = $destination->saldo;
            $saldo_dest_after = $destination->saldo + $amount;

            $mutasi_debit = Mutasi::create([
                'rekening_id' => $source->id,
                'referensi_transaksi_id' => $reference_id,
                'jenis_transaksi' => 'DEBIT',
                'keterangan' => $description,
                'nominal' => $amount,
                'saldo_sebelum' => $saldo_source_before,
                'saldo_setelah' => $saldo_source_after,
                'referensi_eksternal' => 'TRX-' . $reference_id,
                'diinisiasi_oleh' => $user_id,
                'ip_address' => $ip_address,
                'device_info' => substr($user_agent, 0, 255),
                'status_proses' => 'berhasil',
                'waktu_transaksi' => now(),
            ]);

            $mutasi_credit = Mutasi::create([
                'rekening_id' => $destination->id,
                'referensi_transaksi_id' => $reference_id,
                'jenis_transaksi' => 'CREDIT',
                'keterangan' => $description,
                'nominal' => $amount,
                'saldo_sebelum' => $saldo_dest_before,
                'saldo_setelah' => $saldo_dest_after,
                'referensi_eksternal' => 'TRX-' . $reference_id,
                'diinisiasi_oleh' => $user_id,
                'ip_address' => $ip_address,
                'device_info' => substr($user_agent, 0, 255),
                'status_proses' => 'berhasil',
                'waktu_transaksi' => now(),
            ]);

            $source->update(['saldo' => $saldo_source_after]);
            $destination->update(['saldo' => $saldo_dest_after]);

            AuditLog::logTransfer(
                $user_id,
                $reference_id,
                "Transfer Rp " . number_format($amount, 0, ',', '.') . " to account {$destination->nomor_rekening}",
                $ip_address,
                $user_agent,
                [
                    'source_account' => $source->nomor_rekening,
                    'destination_account' => $destination->nomor_rekening,
                    'amount' => $amount,
                    'status' => 'sukses',
                ]
            );

            return [
                'success' => true,
                'reference_id' => $reference_id,
                'transaction_ref' => 'TRX-' . $reference_id,
                'source_account' => [
                    'id' => $source->id,
                    'nomor_rekening' => $source->nomor_rekening,
                    'saldo_setelah' => $saldo_source_after,
                ],
                'destination_account' => [
                    'id' => $destination->id,
                    'nomor_rekening' => $destination->nomor_rekening,
                    'saldo_setelah' => $saldo_dest_after,
                ],
                'amount' => $amount,
                'description' => $description,
                'timestamp' => now()->toIso8601String(),
                'mutasi_debit_id' => $mutasi_debit->id,
                'mutasi_credit_id' => $mutasi_credit->id,
            ];
        });
    }

    /**
     * Mencairkan dana dari sebuah rekening sistem (mis. dana beasiswa) ke
     * rekening milik user yang sedang login. Dipisah dari transfer() karena
     * transfer() mewajibkan rekening SUMBER milik si pemanggil — yang tidak
     * berlaku di sini (sumbernya rekening institusi, bukan rekening pribadi).
     * Untuk menjaga keamanan walau tanpa pengecekan kepemilikan pada sisi
     * sumber, method ini membatasi 2 hal: (1) rekening TUJUAN wajib milik
     * user yang memanggil, dan (2) jumlah dibatasi oleh $max_amount.
     */
    public function disburse(
        string $source_nomor_rekening,
        string $destination_id,
        float $amount,
        float $max_amount,
        string $description,
        string $user_id,
        string $ip_address,
        string $user_agent
    ): array {
        return DB::transaction(function () use (
            $source_nomor_rekening,
            $destination_id,
            $amount,
            $max_amount,
            $description,
            $user_id,
            $ip_address,
            $user_agent
        ) {
            if ($amount <= 0 || $amount > $max_amount) {
                throw ValidationException::withMessages([
                    'amount' => "Amount must be between Rp 1 and Rp " . number_format($max_amount, 0, ',', '.')
                ]);
            }

            $source = Rekening::where('nomor_rekening', $source_nomor_rekening)
                ->lockForUpdate()
                ->firstOrFail();

            $destination = Rekening::where('id', $destination_id)
                ->lockForUpdate()
                ->firstOrFail();

            $destination->loadMissing('nasabah');
            if (!$destination->nasabah || $destination->nasabah->user_id !== $user_id) {
                throw ValidationException::withMessages([
                    'destination' => 'You are not authorized to receive funds into this account'
                ]);
            }

            if ($destination->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'destination' => 'Destination account is inactive'
                ]);
            }

            if (!$source->isSaldoCukup($amount)) {
                throw ValidationException::withMessages([
                    'amount' => 'Insufficient funds in the source account'
                ]);
            }

            $reference_id = $this->generateTransactionReference();

            $saldo_source_before = $source->saldo;
            $saldo_source_after = $source->saldo - $amount;

            $saldo_dest_before = $destination->saldo;
            $saldo_dest_after = $destination->saldo + $amount;

            Mutasi::create([
                'rekening_id' => $source->id,
                'referensi_transaksi_id' => $reference_id,
                'jenis_transaksi' => 'DEBIT',
                'keterangan' => $description,
                'nominal' => $amount,
                'saldo_sebelum' => $saldo_source_before,
                'saldo_setelah' => $saldo_source_after,
                'referensi_eksternal' => 'TRX-' . $reference_id,
                'diinisiasi_oleh' => $user_id,
                'ip_address' => $ip_address,
                'device_info' => substr($user_agent, 0, 255),
                'status_proses' => 'berhasil',
                'waktu_transaksi' => now(),
            ]);

            $mutasi_credit = Mutasi::create([
                'rekening_id' => $destination->id,
                'referensi_transaksi_id' => $reference_id,
                'jenis_transaksi' => 'CREDIT',
                'keterangan' => $description,
                'nominal' => $amount,
                'saldo_sebelum' => $saldo_dest_before,
                'saldo_setelah' => $saldo_dest_after,
                'referensi_eksternal' => 'TRX-' . $reference_id,
                'diinisiasi_oleh' => $user_id,
                'ip_address' => $ip_address,
                'device_info' => substr($user_agent, 0, 255),
                'status_proses' => 'berhasil',
                'waktu_transaksi' => now(),
            ]);

            $source->update(['saldo' => $saldo_source_after]);
            $destination->update(['saldo' => $saldo_dest_after]);

            AuditLog::logTransfer(
                $user_id,
                $reference_id,
                $description,
                $ip_address,
                $user_agent,
                [
                    'source_account' => $source->nomor_rekening,
                    'destination_account' => $destination->nomor_rekening,
                    'amount' => $amount,
                    'status' => 'sukses',
                ]
            );

            return [
                'success' => true,
                'reference_id' => $reference_id,
                'transaction_ref' => 'TRX-' . $reference_id,
                'destination_account' => [
                    'id' => $destination->id,
                    'nomor_rekening' => $destination->nomor_rekening,
                    'saldo_setelah' => $saldo_dest_after,
                ],
                'amount' => $amount,
                'description' => $description,
                'timestamp' => now()->toIso8601String(),
                'mutasi_credit_id' => $mutasi_credit->id,
            ];
        });
    }

    public function validateBeforeTransfer(
        string $source_id,
        string $destination_id,
        float $amount
    ): array {
        $errors = [];

        $source = Rekening::find($source_id);
        if (!$source) {
            $errors['source'] = 'Source account not found';
        } elseif ($source->status !== 'aktif') {
            $errors['source'] = 'Source account is inactive';
        }

        $destination = Rekening::find($destination_id);
        if (!$destination) {
            $errors['destination'] = 'Destination account not found';
        } elseif ($destination->status !== 'aktif') {
            $errors['destination'] = 'Destination account is inactive';
        }

        if ($source && $destination && $source->id === $destination->id) {
            $errors['destination'] = 'Cannot transfer to the same account';
        }

        if ($amount < 10000) {
            $errors['amount'] = 'Minimum transfer amount is Rp 10.000';
        }

        if ($source && !$source->isSaldoCukup($amount)) {
            $errors['amount'] = 'Insufficient balance';
        }

        return [
            'is_valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    private function generateTransactionReference(): string
    {
        return strtoupper(
            substr(md5(uniqid() . time() . random_bytes(10)), 0, 16)
        );
    }
}