<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API Perbankan",
    description: "Dokumentasi Resmi Sistem Perbankan"
)]
#[OA\Get(
    path: "/api/nasabah",
    summary: "Cek Data Nasabah",
    tags: ["Nasabah"],
    responses: [
        new OA\Response(response: 200, description: "Berhasil")
    ]
)]
#[OA\Post(
    path: "/api/nasabah",
    summary: "Create Nasabah",
    tags: ["Nasabah"],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(property: "nik", type: "string"),
                        new OA\Property(property: "nama_lengkap", type: "string"),
                        new OA\Property(property: "no_hp", type: "string")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(response: 201, description: "Nasabah berhasil ditambah")
    ]
)]
#[OA\Put(
    path: "/api/nasabah/{id}",
    summary: "Update Nasabah",
    tags: ["Nasabah"],
    parameters: [
        new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(property: "nama_lengkap", type: "string"),
                        new OA\Property(property: "no_hp", type: "string")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(response: 200, description: "Data Nasabah berhasil diupdate")
    ]
)]
#[OA\Delete(
    path: "/api/nasabah/{id}",
    summary: "Delete Nasabah",
    tags: ["Nasabah"],
    parameters: [
        new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
    ],
    responses: [
        new OA\Response(response: 200, description: "Data Nasabah berhasil dihapus")
    ]
)]
#[OA\Get(
    path: "/api/mutasi",
    summary: "Lihat Riwayat Transaksi",
    tags: ["Mutasi"],
    responses: [
        new OA\Response(response: 200, description: "Sukses")
    ]
)]
#[OA\Post(
    path: "/api/mutasi",
    summary: "Create Mutasi",
    tags: ["Mutasi"],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(property: "no_rekening", type: "string"),
                        new OA\Property(property: "id_jenis_transaksi", type: "integer"),
                        new OA\Property(property: "nominal", type: "number")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(response: 201, description: "Transaksi berhasil dicatat")
    ]
)]
#[OA\Put(
    path: "/api/mutasi/{id}",
    summary: "Update Mutasi",
    tags: ["Mutasi"],
    parameters: [
        new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(property: "nominal", type: "number")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(response: 200, description: "Koreksi transaksi berhasil")
    ]
)]
#[OA\Delete(
    path: "/api/mutasi/{id}",
    summary: "Delete Mutasi",
    tags: ["Mutasi"],
    parameters: [
        new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
    ],
    responses: [
        new OA\Response(response: 200, description: "Transaksi berhasil dihapus")
    ]
)]
#[OA\Get(
    path: "/api/statistik",
    summary: "Tampil Statistik",
    tags: ["Statistik"],
    responses: [
        new OA\Response(response: 200, description: "Sukses")
    ]
)]
#[OA\Post(
    path: "/api/statistik",
    summary: "Create Statistik",
    tags: ["Statistik"],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(property: "nama", type: "string"),
                        new OA\Property(property: "deskripsi", type: "string")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(response: 201, description: "Statistik berhasil dibuat")
    ]
)]
#[OA\Put(
    path: "/api/statistik/{id}",
    summary: "Update Statistik",
    tags: ["Statistik"],
    parameters: [
        new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(property: "nama", type: "string"),
                        new OA\Property(property: "deskripsi", type: "string")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(response: 200, description: "Statistik berhasil diperbarui")
    ]
)]
#[OA\Delete(
    path: "/api/statistik/{id}",
    summary: "Delete Statistik",
    tags: ["Statistik"],
    parameters: [
        new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
    ],
    responses: [
        new OA\Response(response: 200, description: "Statistik berhasil dihapus")
    ]
)]

class ApiController extends BaseController
{
    public function getNasabah()
    {
        return DB::connection('mysql')->table('nasabah')->get();
    }

    public function createNasabah(Request $request)
    {
        $data = $request->only(['nik', 'nama_lengkap', 'no_hp']);
        $id = DB::connection('mysql')->table('nasabah')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Nasabah berhasil ditambah'], 201);
    }

    public function updateNasabah(Request $request, $id)
    {
        $data = $request->only(['nama_lengkap', 'no_hp']);
        DB::connection('mysql')->table('nasabah')->where('id', $id)->update($data);
        return response()->json(['message' => 'Data Nasabah berhasil diupdate']);
    }

    public function deleteNasabah($id)
    {
        DB::connection('mysql')->table('nasabah')->where('id', $id)->delete();
        return response()->json(['message' => 'Data Nasabah berhasil dihapus']);
    }

    public function getMutasi()
    {
        return DB::connection('mysql')->table('mutasi_transaksi')->get();
    }

    public function createMutasi(Request $request)
    {
        $data = $request->only(['no_rekening', 'id_jenis_transaksi', 'nominal']);
        $id = DB::connection('mysql')->table('mutasi_transaksi')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Transaksi berhasil dicatat'], 201);
    }

    public function updateMutasi(Request $request, $id)
    {
        $data = $request->only(['nominal']);
        DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->update($data);
        return response()->json(['message' => 'Koreksi transaksi berhasil']);
    }

    public function deleteMutasi($id)
    {
        DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }

    public function getStatistik()
    {
        return DB::connection('mysql')->table('statistik')->get();
    }

    public function createStatistik(Request $request)
    {
        $data = $request->only(['nama', 'deskripsi']);
        $id = DB::connection('mysql')->table('statistik')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Statistik berhasil dibuat'], 201);
    }

    public function updateStatistik(Request $request, $id)
    {
        $data = $request->only(['nama', 'deskripsi']);
        DB::connection('mysql')->table('statistik')->where('id', $id)->update($data);
        return response()->json(['message' => 'Statistik berhasil diperbarui']);
    }

    public function deleteStatistik($id)
    {
        DB::connection('mysql')->table('statistik')->where('id', $id)->delete();
        return response()->json(['message' => 'Statistik berhasil dihapus']);
    }
}
