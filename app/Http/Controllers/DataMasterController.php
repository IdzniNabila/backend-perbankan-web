<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    // Membaca Semua Data Master (Index)
    public function index()
    {
        $data = DB::table('entitas')->get();
        return response()->json(['status' => 'Sukses', 'data' => $data], 200);
    }

    // Membaca Satu Data Master Berdasarkan ID (Show)
    public function show($id)
    {
        $data = DB::table('entitas')->where('id', $id)->first();
        
        if (!$data) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data entitas tidak ditemukan'], 404);
        }
        
        return response()->json(['status' => 'Sukses', 'data' => $data], 200);
    }

    // Menambah Data Master (Store)
    public function store(Request $request)
    {
        $id = DB::table('entitas')->insertGetId([
            'nama_entitas' => $request->nama_entitas,
            'nomor_identitas' => $request->nomor_identitas,
            'kategori' => $request->kategori
        ]);
        
        $dataBaru = DB::table('entitas')->where('id', $id)->first();

        return response()->json(['status' => 'Sukses', 'pesan' => 'Entitas berhasil ditambah', 'data' => $dataBaru], 201);
    }

    // Mengubah Data Master Berdasarkan ID (Update)
    public function update(Request $request, $id)
    {
        $cekData = DB::table('entitas')->where('id', $id)->first();
        
        if (!$cekData) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data entitas tidak ditemukan'], 404);
        }

        DB::table('entitas')->where('id', $id)->update([
            'nama_entitas' => $request->nama_entitas ?? $cekData->nama_entitas,
            'nomor_identitas' => $request->nomor_identitas ?? $cekData->nomor_identitas,
            'kategori' => $request->kategori ?? $cekData->kategori
        ]);
        
        $dataUpdate = DB::table('entitas')->where('id', $id)->first();

        return response()->json(['status' => 'Sukses', 'pesan' => 'Data entitas berhasil diperbarui', 'data' => $dataUpdate], 200);
    }

    // Menghapus Data Master Berdasarkan ID (Destroy)
    public function destroy($id)
    {
        $hapus = DB::table('entitas')->where('id', $id)->delete();
        
        if (!$hapus) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data gagal dihapus atau tidak ditemukan'], 404);
        }
        
        return response()->json(['status' => 'Sukses', 'pesan' => 'Data entitas berhasil dihapus'], 200);
    }
}