<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    // Membaca Data Master (Read)
    public function index()
    {
        $data = DB::table('entitas')->get();
        return response()->json(['status' => 'Sukses', 'data' => $data], 200);
    }

    // Menambah Data Master (Create)
    public function store(Request $request)
    {
        $id = DB::table('entitas')->insertGetId([
            'nama_entitas' => $request->nama_entitas,
            'nomor_identitas' => $request->nomor_identitas,
            'kategori' => $request->kategori
        ]);
        
        return response()->json(['status' => 'Sukses', 'pesan' => 'Entitas berhasil ditambah', 'id' => $id], 201);
    }
}