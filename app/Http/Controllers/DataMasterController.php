<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    // GET /master/entitas — Semua Data
    public function index()
    {
        $data = DB::table('entitas')->get();
        return response()->json(['status' => 'Sukses', 'data' => $data], 200);
    }

    // GET /master/entitas/{id} — Detail 1 Data
    public function show($id)
    {
        $data = DB::table('entitas')->where('id', $id)->first();

        if (!$data) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data entitas tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'Sukses', 'data' => $data], 200);
    }

    // POST /master/entitas — Tambah Data
    public function store(Request $request)
    {
        $request->validate([
            'nama_entitas'     => 'required|string|max:100',
            'nomor_identitas'  => 'required|string|max:20|unique:entitas,nomor_identitas',
            'kategori'         => 'required|in:Mahasiswa,Dosen,Organisasi,Kantin,Kampus',
        ]);

        $id = DB::table('entitas')->insertGetId([
            'nama_entitas'    => $request->nama_entitas,
            'nomor_identitas' => $request->nomor_identitas,
            'kategori'        => $request->kategori,
        ]);

        $dataBaru = DB::table('entitas')->where('id', $id)->first();

        return response()->json([
            'status' => 'Sukses',
            'pesan'  => 'Entitas berhasil ditambah',
            'data'   => $dataBaru
        ], 201);
    }

    // PUT /master/entitas/{id} — Edit Data
    public function update(Request $request, $id)
    {
        $cekData = DB::table('entitas')->where('id', $id)->first();

        if (!$cekData) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data entitas tidak ditemukan'], 404);
        }

        DB::table('entitas')->where('id', $id)->update([
            'nama_entitas'    => $request->nama_entitas    ?? $cekData->nama_entitas,
            'nomor_identitas' => $request->nomor_identitas ?? $cekData->nomor_identitas,
            'kategori'        => $request->kategori        ?? $cekData->kategori,
        ]);

        $dataUpdate = DB::table('entitas')->where('id', $id)->first();

        return response()->json([
            'status' => 'Sukses',
            'pesan'  => 'Data entitas berhasil diperbarui',
            'data'   => $dataUpdate
        ], 200);
    }

    // DELETE /master/entitas/{id} — Hapus Data
    public function destroy($id)
    {
        $hapus = DB::table('entitas')->where('id', $id)->delete();

        if (!$hapus) {
            return response()->json(['status' => 'Gagal', 'pesan' => 'Data gagal dihapus atau tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'Sukses', 'pesan' => 'Data entitas berhasil dihapus'], 200);
    }
}
