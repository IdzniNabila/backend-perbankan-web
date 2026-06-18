namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\{Rekening, Mutasi};
use Exception;

class TransaksiService
{
    public function prosesTransfer($sumberId, $nomorTujuan, $nominal, $berita)
    {
        return DB::transaction(function () use ($sumberId, $nomorTujuan, $nominal, $berita) {
            // 1. Lock baris database (Pessimistic Locking)
            $rekSumber = Rekening::where('id', $sumberId)->lockForUpdate()->firstOrFail();
            $rekTujuan = Rekening::where('nomor_rekening', $nomorTujuan)->lockForUpdate()->firstOrFail();

            // 2. Cek Saldo
            if ($rekSumber->saldo_tersedia < $nominal) {
                throw new Exception('Saldo tidak mencukupi.');
            }

            $referensi = 'TRX-' . date('YmdHis') . rand(1000, 9999);

            // 3. Debit Sumber
            $rekSumber->saldo_tersedia -= $nominal;
            $rekSumber->save();
            Mutasi::create([
                'rekening_id' => $rekSumber->id,
                'referensi_transaksi' => $referensi,
                'jenis' => 'DEBIT',
                'nominal' => $nominal,
                'saldo_setelah_mutasi' => $rekSumber->saldo_tersedia,
                'deskripsi_sistem' => "Transfer ke {$nomorTujuan}",
                'berita_transfer' => $berita
            ]);

            // 4. Kredit Tujuan
            $rekTujuan->saldo_tersedia += $nominal;
            $rekTujuan->save();
            Mutasi::create([
                'rekening_id' => $rekTujuan->id,
                'referensi_transaksi' => $referensi,
                'jenis' => 'KREDIT',
                'nominal' => $nominal,
                'saldo_setelah_mutasi' => $rekTujuan->saldo_tersedia,
                'deskripsi_sistem' => "Terima dana dari {$rekSumber->nomor_rekening}",
                'berita_transfer' => $berita
            ]);

            return $referensi;
        });
    }
}