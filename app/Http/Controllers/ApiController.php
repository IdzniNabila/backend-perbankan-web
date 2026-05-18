<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use Exception;

#[OA\Info(
    version: "1.0.0",
    title: "API Perbankan",
    description: "Dokumentasi Resmi Sistem Perbankan"
)]

// NASABAH
#[OA\Get(
    path: "/api/nasabah", summary: "Get Semua Nasabah", tags: ["Nasabah"], 
    parameters: [new OA\Parameter(name: "page", in: "query", required: false, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Get(
    path: "/api/nasabah/{id}", summary: "Get Detail Nasabah", tags: ["Nasabah"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/nasabah", summary: "Create Nasabah", tags: ["Nasabah"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nik", type: "string"), new OA\Property(property: "nama_lengkap", type: "string"), new OA\Property(property: "no_hp", type: "string")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/nasabah/{id}", summary: "Update Nasabah", tags: ["Nasabah"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_lengkap", type: "string"), new OA\Property(property: "no_hp", type: "string")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/nasabah/{id}", summary: "Delete Nasabah", tags: ["Nasabah"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


// REKENING
#[OA\Get(
    path: "/api/rekening", summary: "Get Semua Rekening Detail ", tags: ["Rekening"], 
    parameters: [new OA\Parameter(name: "page", in: "query", required: false, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Get(
    path: "/api/rekening/{no_rekening}", summary: "Get Detail Rekening Lengkap", tags: ["Rekening"],
    parameters: [new OA\Parameter(name: "no_rekening", in: "path", required: true, schema: new OA\Schema(type: "string"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/rekening", summary: "Create Rekening", tags: ["Rekening"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "no_rekening", type: "string"), new OA\Property(property: "id_nasabah", type: "integer"),
        new OA\Property(property: "id_jenis_rekening", type: "integer"), new OA\Property(property: "id_cabang", type: "integer"), new OA\Property(property: "saldo", type: "number")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/rekening/{no_rekening}", summary: "Update Rekening", tags: ["Rekening"],
    parameters: [new OA\Parameter(name: "no_rekening", in: "path", required: true, schema: new OA\Schema(type: "string"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "id_jenis_rekening", type: "integer"), new OA\Property(property: "id_cabang", type: "integer"), new OA\Property(property: "saldo", type: "number")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/rekening/{no_rekening}", summary: "Delete Rekening", tags: ["Rekening"],
    parameters: [new OA\Parameter(name: "no_rekening", in: "path", required: true, schema: new OA\Schema(type: "string"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]

// JENIS TRANSAKSI
#[OA\Get(path: "/api/jenis-transaksi", summary: "Get Semua Jenis Transaksi ", tags: ["Jenis Transaksi"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/jenis-transaksi/{id}", summary: "Get Detail Jenis Transaksi", tags: ["Jenis Transaksi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/jenis-transaksi", summary: "Create Jenis Transaksi", tags: ["Jenis Transaksi"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_transaksi", type: "string"), new OA\Property(property: "tipe", type: "string")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/jenis-transaksi/{id}", summary: "Update Jenis Transaksi", tags: ["Jenis Transaksi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_transaksi", type: "string"), new OA\Property(property: "tipe", type: "string")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/jenis-transaksi/{id}", summary: "Delete Jenis Transaksi", tags: ["Jenis Transaksi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


// CABANG BANK
#[OA\Get(path: "/api/cabang-bank", summary: "Get Semua Cabang Bank ", tags: ["Cabang Bank"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/cabang-bank/{id}", summary: "Get Detail Cabang", tags: ["Cabang Bank"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/cabang-bank", summary: "Create Cabang Bank", tags: ["Cabang Bank"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_cabang", type: "string"), new OA\Property(property: "alamat", type: "string")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/cabang-bank/{id}", summary: "Update Cabang Bank", tags: ["Cabang Bank"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_cabang", type: "string"), new OA\Property(property: "alamat", type: "string")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/cabang-bank/{id}", summary: "Delete Cabang Bank", tags: ["Cabang Bank"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


// JENIS REKENING
#[OA\Get(path: "/api/jenis-rekening", summary: "Get Semua Jenis Rekening ", tags: ["Jenis Rekening"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/jenis-rekening/{id}", summary: "Get Detail Jenis Rekening", tags: ["Jenis Rekening"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/jenis-rekening", summary: "Create Jenis Rekening", tags: ["Jenis Rekening"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_jenis", type: "string"), new OA\Property(property: "biaya_admin", type: "number")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/jenis-rekening/{id}", summary: "Update Jenis Rekening", tags: ["Jenis Rekening"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama_jenis", type: "string"), new OA\Property(property: "biaya_admin", type: "number")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/jenis-rekening/{id}", summary: "Delete Jenis Rekening", tags: ["Jenis Rekening"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


// TRANSAKSI PEMBAYARAN
#[OA\Get(path: "/api/transaksi-pembayaran", summary: "Get Semua Transaksi Pembayaran Detail ", tags: ["Transaksi Pembayaran"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/transaksi-pembayaran/{id}", summary: "Get Detail Transaksi Lengkap", tags: ["Transaksi Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/transaksi-pembayaran", summary: "Create Transaksi Pembayaran", tags: ["Transaksi Pembayaran"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "no_rekening", type: "string"), 
        new OA\Property(property: "total_bayar", type: "number"), 
        new OA\Property(property: "status", type: "string"),
        new OA\Property(property: "tanggal_bayar", type: "string", format: "date-time", description: "Format: YYYY-MM-DD HH:MM:SS (Opsional, kosong = otomatis saat ini)")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/transaksi-pembayaran/{id}", summary: "Update Status Transaksi", tags: ["Transaksi Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "status", type: "string")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/transaksi-pembayaran/{id}", summary: "Delete Transaksi Pembayaran", tags: ["Transaksi Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


// DETAIL PEMBAYARAN
#[OA\Get(path: "/api/detail-pembayaran", summary: "Get Semua Detail Pembayaran Gabungan ", tags: ["Detail Pembayaran"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/detail-pembayaran/{id}", summary: "Get Spesifik Detail Pembayaran Lengkap", tags: ["Detail Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/detail-pembayaran", summary: "Create Detail Pembayaran", tags: ["Detail Pembayaran"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "id_transaksi", type: "integer"), new OA\Property(property: "item_pembayaran", type: "string"), new OA\Property(property: "nominal", type: "number")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/detail-pembayaran/{id}", summary: "Update Detail Pembayaran", tags: ["Detail Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "item_pembayaran", type: "string"), new OA\Property(property: "nominal", type: "number")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/detail-pembayaran/{id}", summary: "Delete Detail Pembayaran", tags: ["Detail Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]

// MUTASI
#[OA\Get(path: "/api/mutasi", summary: "Get Semua Mutasi Gabungan ", tags: ["Mutasi"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/mutasi/{id}", summary: "Get Detail Mutasi Lengkap", tags: ["Mutasi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Get(
    path: "/api/mutasi/rekening/{no_rekening}", summary: "Get Mutasi Berdasarkan Nomor Rekening", tags: ["Mutasi"],
    parameters: [new OA\Parameter(name: "no_rekening", in: "path", required: true, schema: new OA\Schema(type: "string"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/mutasi", summary: "Create Mutasi", tags: ["Mutasi"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "no_rekening", type: "string"), 
        new OA\Property(property: "id_jenis_transaksi", type: "integer"), 
        new OA\Property(property: "nominal", type: "number"),
        new OA\Property(property: "tanggal_transaksi", type: "string", format: "date-time", description: "Format: YYYY-MM-DD HH:MM:SS (Opsional, kosong = otomatis saat ini)")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/mutasi/{id}", summary: "Update Mutasi", tags: ["Mutasi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nominal", type: "number")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/mutasi/{id}", summary: "Delete Mutasi", tags: ["Mutasi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


// STATISTIK
#[OA\Get(path: "/api/statistik", summary: "Get Semua Statistik ", tags: ["Statistik"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Post(
    path: "/api/statistik", summary: "Create Statistik", tags: ["Statistik"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama", type: "string"), new OA\Property(property: "deskripsi", type: "string")
    ]))]),
    responses: [new OA\Response(response: 201, description: "Sukses")]
)]
#[OA\Put(
    path: "/api/statistik/{id}", summary: "Update Statistik", tags: ["Statistik"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "nama", type: "string"), new OA\Property(property: "deskripsi", type: "string")
    ]))]),
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Delete(
    path: "/api/statistik/{id}", summary: "Delete Statistik", tags: ["Statistik"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]


class ApiController extends BaseController
{

    // NASABAH
    public function getNasabah() {
        try {
            $data = DB::connection('mysql')->table('nasabah')->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getNasabahById($id) {
        try {
            $data = DB::connection('mysql')->table('nasabah')->where('id', $id)->first();
            if (!$data) return response()->json(['message' => 'Data tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createNasabah(Request $request) {
        try {
            $data = $request->only(['nik', 'nama_lengkap', 'no_hp']);
            $id = DB::connection('mysql')->table('nasabah')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Nasabah berhasil ditambah'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateNasabah(Request $request, $id) {
        try {
            $data = $request->only(['nama_lengkap', 'no_hp']);
            DB::connection('mysql')->table('nasabah')->where('id', $id)->update($data);
            return response()->json(['message' => 'Data Nasabah berhasil diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteNasabah($id) {
        try {
            DB::connection('mysql')->table('nasabah')->where('id', $id)->delete();
            return response()->json(['message' => 'Data Nasabah berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // REKENING (DETAIL JOIN)
    public function getRekening() {
        try {
            $data = DB::connection('mysql')->table('rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->join('jenis_rekening', 'rekening.id_jenis_rekening', '=', 'jenis_rekening.id')
                ->join('cabang_bank', 'rekening.id_cabang', '=', 'cabang_bank.id')
                ->select('rekening.*', 'nasabah.nama_lengkap as nama_nasabah', 'nasabah.nik', 'jenis_rekening.nama_jenis', 'cabang_bank.nama_cabang')
                ->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getRekeningByNoRekening($no_rekening) {
        try {
            $data = DB::connection('mysql')->table('rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->join('jenis_rekening', 'rekening.id_jenis_rekening', '=', 'jenis_rekening.id')
                ->join('cabang_bank', 'rekening.id_cabang', '=', 'cabang_bank.id')
                ->select('rekening.*', 'nasabah.nama_lengkap as nama_nasabah', 'nasabah.nik', 'jenis_rekening.nama_jenis', 'cabang_bank.nama_cabang')
                ->where('rekening.no_rekening', $no_rekening)
                ->first();
            if (!$data) return response()->json(['message' => 'Rekening tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createRekening(Request $request) {
        try {
            $data = $request->only(['no_rekening', 'id_nasabah', 'id_jenis_rekening', 'id_cabang', 'saldo']);
            DB::connection('mysql')->table('rekening')->insert($data);
            return response()->json(['message' => 'Rekening berhasil dibuat'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateRekening(Request $request, $no_rekening) {
        try {
            $data = $request->only(['id_jenis_rekening', 'id_cabang', 'saldo']);
            DB::connection('mysql')->table('rekening')->where('no_rekening', $no_rekening)->update($data);
            return response()->json(['message' => 'Rekening berhasil diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteRekening($no_rekening) {
        try {
            DB::connection('mysql')->table('rekening')->where('no_rekening', $no_rekening)->delete();
            return response()->json(['message' => 'Rekening berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    // JENIS TRANSAKSI
    public function getJenisTransaksi() {
        try {
            $data = DB::connection('mysql')->table('jenis_transaksi')->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getJenisTransaksiById($id) {
        try {
            $data = DB::connection('mysql')->table('jenis_transaksi')->where('id', $id)->first();
            if (!$data) return response()->json(['message' => 'Jenis transaksi tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createJenisTransaksi(Request $request) {
        try {
            $data = $request->only(['nama_transaksi', 'tipe']);
            $id = DB::connection('mysql')->table('jenis_transaksi')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Jenis Transaksi berhasil ditambah'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateJenisTransaksi(Request $request, $id) {
        try {
            $data = $request->only(['nama_transaksi', 'tipe']);
            DB::connection('mysql')->table('jenis_transaksi')->where('id', $id)->update($data);
            return response()->json(['message' => 'Jenis Transaksi berhasil diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteJenisTransaksi($id) {
        try {
            DB::connection('mysql')->table('jenis_transaksi')->where('id', $id)->delete();
            return response()->json(['message' => 'Jenis Transaksi berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // CABANG BANK
    public function getCabangBank() {
        try {
            $data = DB::connection('mysql')->table('cabang_bank')->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getCabangBankById($id) {
        try {
            $data = DB::connection('mysql')->table('cabang_bank')->where('id', $id)->first();
            if (!$data) return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createCabangBank(Request $request) {
        try {
            $data = $request->only(['nama_cabang', 'alamat']);
            $id = DB::connection('mysql')->table('cabang_bank')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Cabang Bank berhasil ditambah'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCabangBank(Request $request, $id) {
        try {
            $data = $request->only(['nama_cabang', 'alamat']);
            DB::connection('mysql')->table('cabang_bank')->where('id', $id)->update($data);
            return response()->json(['message' => 'Cabang Bank berhasil diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteCabangBank($id) {
        try {
            DB::connection('mysql')->table('cabang_bank')->where('id', $id)->delete();
            return response()->json(['message' => 'Cabang Bank berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    // JENIS REKENING
    public function getJenisRekening() {
        try {
            $data = DB::connection('mysql')->table('jenis_rekening')->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getJenisRekeningById($id) {
        try {
            $data = DB::connection('mysql')->table('jenis_rekening')->where('id', $id)->first();
            if (!$data) return response()->json(['message' => 'Jenis rekening tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createJenisRekening(Request $request) {
        try {
            $data = $request->only(['nama_jenis', 'biaya_admin']);
            $id = DB::connection('mysql')->table('jenis_rekening')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Jenis Rekening berhasil ditambah'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateJenisRekening(Request $request, $id) {
        try {
            $data = $request->only(['nama_jenis', 'biaya_admin']);
            DB::connection('mysql')->table('jenis_rekening')->where('id', $id)->update($data);
            return response()->json(['message' => 'Jenis Rekening berhasil diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteJenisRekening($id) {
        try {
            DB::connection('mysql')->table('jenis_rekening')->where('id', $id)->delete();
            return response()->json(['message' => 'Jenis Rekening berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // TRANSAKSI PEMBAYARAN
    public function getTransaksiPembayaran() {
        try {
            $data = DB::connection('mysql')->table('transaksi_pembayaran')
                ->join('rekening', 'transaksi_pembayaran.no_rekening', '=', 'rekening.no_rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->select('transaksi_pembayaran.*', 'nasabah.nama_lengkap as nama_pembayar')
                ->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getTransaksiPembayaranById($id) {
        try {
            $data = DB::connection('mysql')->table('transaksi_pembayaran')
                ->join('rekening', 'transaksi_pembayaran.no_rekening', '=', 'rekening.no_rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->select('transaksi_pembayaran.*', 'nasabah.nama_lengkap as nama_pembayar')
                ->where('transaksi_pembayaran.id', $id)
                ->first();
            if (!$data) return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createTransaksiPembayaran(Request $request) {
        try {
            $data = $request->only(['no_rekening', 'total_bayar', 'status']);
            // Otomatis isi tanggal jika kosong
            $data['tanggal_bayar'] = $request->input('tanggal_bayar', now());
            
            $id = DB::connection('mysql')->table('transaksi_pembayaran')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Transaksi berhasil dicatat'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateTransaksiPembayaran(Request $request, $id) {
        try {
            $data = $request->only(['status']);
            DB::connection('mysql')->table('transaksi_pembayaran')->where('id', $id)->update($data);
            return response()->json(['message' => 'Status Transaksi diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteTransaksiPembayaran($id) {
        try {
            DB::connection('mysql')->table('transaksi_pembayaran')->where('id', $id)->delete();
            return response()->json(['message' => 'Transaksi berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // DETAIL PEMBAYARAN
    public function getDetailPembayaran() {
        try {
            $data = DB::connection('mysql')->table('detail_pembayaran')
                ->join('transaksi_pembayaran', 'detail_pembayaran.id_transaksi', '=', 'transaksi_pembayaran.id')
                ->select('detail_pembayaran.*', 'transaksi_pembayaran.no_rekening', 'transaksi_pembayaran.status as status_pembayaran')
                ->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getDetailPembayaranById($id) {
        try {
            $data = DB::connection('mysql')->table('detail_pembayaran')
                ->join('transaksi_pembayaran', 'detail_pembayaran.id_transaksi', '=', 'transaksi_pembayaran.id')
                ->select('detail_pembayaran.*', 'transaksi_pembayaran.no_rekening', 'transaksi_pembayaran.status as status_pembayaran')
                ->where('detail_pembayaran.id', $id)
                ->first();
            if (!$data) return response()->json(['message' => 'Detail tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createDetailPembayaran(Request $request) {
        try {
            $data = $request->only(['id_transaksi', 'item_pembayaran', 'nominal']);
            $id = DB::connection('mysql')->table('detail_pembayaran')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Detail berhasil dicatat'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateDetailPembayaran(Request $request, $id) {
        try {
            $data = $request->only(['item_pembayaran', 'nominal']);
            DB::connection('mysql')->table('detail_pembayaran')->where('id', $id)->update($data);
            return response()->json(['message' => 'Detail diupdate'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteDetailPembayaran($id) {
        try {
            DB::connection('mysql')->table('detail_pembayaran')->where('id', $id)->delete();
            return response()->json(['message' => 'Detail dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    // MUTASI
    public function getMutasi() {
        try {
            $data = DB::connection('mysql')->table('mutasi_transaksi')
                ->join('rekening', 'mutasi_transaksi.no_rekening', '=', 'rekening.no_rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->join('jenis_transaksi', 'mutasi_transaksi.id_jenis_transaksi', '=', 'jenis_transaksi.id')
                ->select('mutasi_transaksi.*', 'nasabah.nama_lengkap as nama_nasabah', 'jenis_transaksi.nama_transaksi', 'jenis_transaksi.tipe')
                ->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getMutasiById($id) {
        try {
            $data = DB::connection('mysql')->table('mutasi_transaksi')
                ->join('rekening', 'mutasi_transaksi.no_rekening', '=', 'rekening.no_rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->join('jenis_transaksi', 'mutasi_transaksi.id_jenis_transaksi', '=', 'jenis_transaksi.id')
                ->select('mutasi_transaksi.*', 'nasabah.nama_lengkap as nama_nasabah', 'jenis_transaksi.nama_transaksi', 'jenis_transaksi.tipe')
                ->where('mutasi_transaksi.id', $id)
                ->first();
            if (!$data) return response()->json(['message' => 'Data tidak ditemukan'], 404);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getMutasiByRekening($no_rekening) {
        try {
            $data = DB::connection('mysql')->table('mutasi_transaksi')
                ->join('rekening', 'mutasi_transaksi.no_rekening', '=', 'rekening.no_rekening')
                ->join('nasabah', 'rekening.id_nasabah', '=', 'nasabah.id')
                ->join('jenis_transaksi', 'mutasi_transaksi.id_jenis_transaksi', '=', 'jenis_transaksi.id')
                ->select('mutasi_transaksi.*', 'nasabah.nama_lengkap as nama_nasabah', 'jenis_transaksi.nama_transaksi', 'jenis_transaksi.tipe')
                ->where('mutasi_transaksi.no_rekening', $no_rekening)
                ->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createMutasi(Request $request) {
        try {
            $data = $request->only(['no_rekening', 'id_jenis_transaksi', 'nominal']);
            // Otomatis isi tanggal jika kosong
            $data['tanggal_transaksi'] = $request->input('tanggal_transaksi', now());
            
            $id = DB::connection('mysql')->table('mutasi_transaksi')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Transaksi berhasil dicatat'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateMutasi(Request $request, $id) {
        try {
            $data = $request->only(['nominal']);
            DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->update($data);
            return response()->json(['message' => 'Koreksi transaksi berhasil'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteMutasi($id) {
        try {
            DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->delete();
            return response()->json(['message' => 'Transaksi berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // STATISTIK
    public function getStatistik() {
        try {
            $data = DB::connection('mysql')->table('statistik')->paginate(10);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createStatistik(Request $request) {
        try {
            $data = $request->only(['nama', 'deskripsi']);
            $id = DB::connection('mysql')->table('statistik')->insertGetId($data);
            return response()->json(['id' => $id, 'message' => 'Statistik berhasil dibuat'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatistik(Request $request, $id) {
        try {
            $data = $request->only(['nama', 'deskripsi']);
            DB::connection('mysql')->table('statistik')->where('id', $id)->update($data);
            return response()->json(['message' => 'Statistik berhasil diperbarui'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteStatistik($id) {
        try {
            DB::connection('mysql')->table('statistik')->where('id', $id)->delete();
            return response()->json(['message' => 'Statistik berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}