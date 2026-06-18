<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\Rekening;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * FIX: Seeder lama membuat data lewat User::factory() dengan kolom
     * 'name' & 'email' bergaya tabel `users` default Laravel. Tabel
     * `pengguna` yang sekarang dipakai sama sekali tidak punya kolom-kolom
     * itu (lihat migrations/2026_06_18_000001_create_pengguna_table.php),
     * jadi seeder lama akan selalu gagal dengan SQL error. Tanpa seeder
     * yang benar, tabel `pengguna` selalu kosong dan TIDAK ADA satupun
     * akun yang bisa login ke aplikasi.
     */
    public function run(): void
    {
        // Password & PIN demo yang sama untuk semua nasabah, supaya mudah dites:
        // password: password123 | PIN transaksi: 123456
        $password = Hash::make('kudaterbang');
        $pin = Hash::make('230406');

        $mahasiswa = [
            ['username' => 'mhs_001', 'nama' => 'Idzni', 'nim' => '12050111', 'saldo' => 150000],
            ['username' => 'mhs_002', 'nama' => 'Adisky', 'nim' => '12050112', 'saldo' => 300000],
            ['username' => 'mhs_003', 'nama' => 'Adinda', 'nim' => '12050113', 'saldo' => 50000],
        ];

        foreach ($mahasiswa as $i => $m) {
            $user = User::create([
                'username' => $m['username'],
                'password' => $password,
                'pin_hash' => $pin,
                'email' => $m['username'] . '@kampus.test',
                'nama_lengkap' => $m['nama'],
                'nomor_identitas' => $m['nim'],
                'jenis_identitas' => 'NIM',
                'nomor_telepon' => '0812000000' . $i,
                'status' => 'aktif',
            ]);

            $nasabah = Nasabah::create([
                'user_id' => $user->id,
                'nama_nasabah' => $m['nama'],
                'nomor_identitas' => $m['nim'],
                'jenis_identitas' => 'NIM',
                'nomor_telepon' => '0812000000' . $i,
                'email' => $user->email,
                'status_kepemilikan' => 'individu',
                'tanggal_daftar' => now(),
                'status' => 'aktif',
            ]);

            Rekening::create([
                'nasabah_id' => $nasabah->id,
                'nomor_rekening' => '8801205011' . $i,
                'jenis_rekening' => 'tabungan',
                'saldo' => $m['saldo'],
                'saldo_minimum' => 0,
                'mata_uang' => 'IDR',
                'keterangan' => 'Dompet Reguler Mahasiswa',
                'status' => 'aktif',
                'tanggal_buka' => now(),
            ]);
        }

        // Dosen
        $dosen = User::create([
            'username' => 'dosen_001',
            'password' => $password,
            'pin_hash' => $pin,
            'email' => 'dosen_001@kampus.test',
            'nama_lengkap' => 'Dr. Mufid',
            'nomor_identitas' => '19800101',
            'jenis_identitas' => 'NIDN',
            'status' => 'aktif',
        ]);

        $nasabahDosen = Nasabah::create([
            'user_id' => $dosen->id,
            'nama_nasabah' => 'Dr. Mufid',
            'nomor_identitas' => '19800101',
            'jenis_identitas' => 'NIDN',
            'status_kepemilikan' => 'individu',
            'tanggal_daftar' => now(),
            'status' => 'aktif',
        ]);

        Rekening::create([
            'nasabah_id' => $nasabahDosen->id,
            'nomor_rekening' => '88019800101',
            'jenis_rekening' => 'tabungan',
            'saldo' => 2500000,
            'saldo_minimum' => 0,
            'mata_uang' => 'IDR',
            'keterangan' => 'Dompet Dosen',
            'status' => 'aktif',
            'tanggal_buka' => now(),
        ]);

        // Akun sistem (non-login) untuk menampung dana keuangan kampus &
        // dana beasiswa. Status 'nonaktif' supaya tidak bisa dipakai login.
        $sistem = User::create([
            'username' => 'sistem_kampus',
            'password' => Hash::make(bin2hex(random_bytes(16))),
            'pin_hash' => Hash::make(bin2hex(random_bytes(16))),
            'nama_lengkap' => 'Sistem Keuangan Kampus',
            'status' => 'nonaktif',
        ]);

        $nasabahSistem = Nasabah::create([
            'user_id' => $sistem->id,
            'nama_nasabah' => 'Sistem Keuangan Kampus',
            'nomor_identitas' => 'SYS-0001',
            'jenis_identitas' => 'NPK',
            'status_kepemilikan' => 'perusahaan',
            'tanggal_daftar' => now(),
            'status' => 'aktif',
        ]);

        Rekening::create([
            'nasabah_id' => $nasabahSistem->id,
            'nomor_rekening' => config('banking.finance_account_number'),
            'jenis_rekening' => 'giro',
            'saldo' => 999999999,
            'saldo_minimum' => 0,
            'mata_uang' => 'IDR',
            'keterangan' => 'Rekening Penerimaan UKT / Keuangan Kampus',
            'status' => 'aktif',
            'tanggal_buka' => now(),
        ]);

        Rekening::create([
            'nasabah_id' => $nasabahSistem->id,
            'nomor_rekening' => config('banking.scholarship_account_number'),
            'jenis_rekening' => 'giro',
            'saldo' => 50000000,
            'saldo_minimum' => 0,
            'mata_uang' => 'IDR',
            'keterangan' => 'Dana Beasiswa',
            'status' => 'aktif',
            'tanggal_buka' => now(),
        ]);
    }
}