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

// NASABAH
#[OA\Get(path: "/api/nasabah", summary: "Get Semua Nasabah", tags: ["Nasabah"], responses: [new OA\Response(response: 200, description: "Sukses")])]
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
#[OA\Get(path: "/api/rekening", summary: "Get Semua Rekening", tags: ["Rekening"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/rekening/{no_rekening}", summary: "Get Detail Rekening", tags: ["Rekening"],
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
#[OA\Get(path: "/api/jenis-transaksi", summary: "Get Semua Jenis Transaksi", tags: ["Jenis Transaksi"], responses: [new OA\Response(response: 200, description: "Sukses")])]
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
#[OA\Get(path: "/api/cabang-bank", summary: "Get Semua Cabang Bank", tags: ["Cabang Bank"], responses: [new OA\Response(response: 200, description: "Sukses")])]
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
#[OA\Get(path: "/api/jenis-rekening", summary: "Get Semua Jenis Rekening", tags: ["Jenis Rekening"], responses: [new OA\Response(response: 200, description: "Sukses")])]
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


//TRANSAKSI PEMBAYARAN
#[OA\Get(path: "/api/transaksi-pembayaran", summary: "Get Semua Transaksi Pembayaran", tags: ["Transaksi Pembayaran"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/transaksi-pembayaran/{id}", summary: "Get Detail Transaksi", tags: ["Transaksi Pembayaran"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/transaksi-pembayaran", summary: "Create Transaksi Pembayaran", tags: ["Transaksi Pembayaran"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "no_rekening", type: "string"), new OA\Property(property: "total_bayar", type: "number"), 
        new OA\Property(property: "tanggal_bayar", type: "string", format: "date"), new OA\Property(property: "status", type: "string")
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


// SWAGGER: DETAIL PEMBAYARAN
#[OA\Get(path: "/api/detail-pembayaran", summary: "Get Semua Detail Pembayaran", tags: ["Detail Pembayaran"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/detail-pembayaran/{id}", summary: "Get Spesifik Detail Pembayaran", tags: ["Detail Pembayaran"],
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
#[OA\Get(path: "/api/mutasi", summary: "Get Semua Mutasi", tags: ["Mutasi"], responses: [new OA\Response(response: 200, description: "Sukses")])]
#[OA\Get(
    path: "/api/mutasi/{id}", summary: "Get Detail Mutasi", tags: ["Mutasi"],
    parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Get(
    path: "/api/mutasi/rekening/{no_rekening}", summary: "Get Mutasi Berdasarkan Rekening", tags: ["Mutasi"],
    parameters: [new OA\Parameter(name: "no_rekening", in: "path", required: true, schema: new OA\Schema(type: "string"))],
    responses: [new OA\Response(response: 200, description: "Sukses")]
)]
#[OA\Post(
    path: "/api/mutasi", summary: "Create Mutasi", tags: ["Mutasi"],
    requestBody: new OA\RequestBody(required: true, content: [new OA\MediaType(mediaType: "application/json", schema: new OA\Schema(type: "object", properties: [
        new OA\Property(property: "no_rekening", type: "string"), new OA\Property(property: "id_jenis_transaksi", type: "integer"), new OA\Property(property: "nominal", type: "number")
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
#[OA\Get(path: "/api/statistik", summary: "Get Semua Statistik", tags: ["Statistik"], responses: [new OA\Response(response: 200, description: "Sukses")])]
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
        return DB::connection('mysql')->table('nasabah')->paginate(10);
    }
    public function getNasabahById($id) {
        $data = DB::connection('mysql')->table('nasabah')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Data tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createNasabah(Request $request) {
        $data = $request->only(['nik', 'nama_lengkap', 'no_hp']);
        $id = DB::connection('mysql')->table('nasabah')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Nasabah berhasil ditambah'], 201);
    }
    public function updateNasabah(Request $request, $id) {
        $data = $request->only(['nama_lengkap', 'no_hp']);
        DB::connection('mysql')->table('nasabah')->where('id', $id)->update($data);
        return response()->json(['message' => 'Data Nasabah berhasil diupdate']);
    }
    public function deleteNasabah($id) {
        DB::connection('mysql')->table('nasabah')->where('id', $id)->delete();
        return response()->json(['message' => 'Data Nasabah berhasil dihapus']);
    }

    // REKENING
    public function getRekening() {
        return DB::connection('mysql')->table('rekening')->paginate(10);
    }
    public function getRekeningByNoRekening($no_rekening) {
        $data = DB::connection('mysql')->table('rekening')->where('no_rekening', $no_rekening)->first();
        if (!$data) return response()->json(['message' => 'Rekening tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createRekening(Request $request) {
        $data = $request->only(['no_rekening', 'id_nasabah', 'id_jenis_rekening', 'id_cabang', 'saldo']);
        DB::connection('mysql')->table('rekening')->insert($data);
        return response()->json(['message' => 'Rekening berhasil dibuat'], 201);
    }
    public function updateRekening(Request $request, $no_rekening) {
        $data = $request->only(['id_jenis_rekening', 'id_cabang', 'saldo']);
        DB::connection('mysql')->table('rekening')->where('no_rekening', $no_rekening)->update($data);
        return response()->json(['message' => 'Rekening berhasil diupdate']);
    }
    public function deleteRekening($no_rekening) {
        DB::connection('mysql')->table('rekening')->where('no_rekening', $no_rekening)->delete();
        return response()->json(['message' => 'Rekening berhasil dihapus']);
    }

    // JENIS TRANSAKSI
    public function getJenisTransaksi() {
        return DB::connection('mysql')->table('jenis_transaksi')->paginate(10);
    }
    public function getJenisTransaksiById($id) {
        $data = DB::connection('mysql')->table('jenis_transaksi')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Jenis transaksi tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createJenisTransaksi(Request $request) {
        $data = $request->only(['nama_transaksi', 'tipe']);
        $id = DB::connection('mysql')->table('jenis_transaksi')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Jenis Transaksi berhasil ditambah'], 201);
    }
    public function updateJenisTransaksi(Request $request, $id) {
        $data = $request->only(['nama_transaksi', 'tipe']);
        DB::connection('mysql')->table('jenis_transaksi')->where('id', $id)->update($data);
        return response()->json(['message' => 'Jenis Transaksi berhasil diupdate']);
    }
    public function deleteJenisTransaksi($id) {
        DB::connection('mysql')->table('jenis_transaksi')->where('id', $id)->delete();
        return response()->json(['message' => 'Jenis Transaksi berhasil dihapus']);
    }


    // CABANG BANK
    public function getCabangBank() {
        return DB::connection('mysql')->table('cabang_bank')->paginate(10);
    }
    public function getCabangBankById($id) {
        $data = DB::connection('mysql')->table('cabang_bank')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createCabangBank(Request $request) {
        $data = $request->only(['nama_cabang', 'alamat']);
        $id = DB::connection('mysql')->table('cabang_bank')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Cabang Bank berhasil ditambah'], 201);
    }
    public function updateCabangBank(Request $request, $id) {
        $data = $request->only(['nama_cabang', 'alamat']);
        DB::connection('mysql')->table('cabang_bank')->where('id', $id)->update($data);
        return response()->json(['message' => 'Cabang Bank berhasil diupdate']);
    }
    public function deleteCabangBank($id) {
        DB::connection('mysql')->table('cabang_bank')->where('id', $id)->delete();
        return response()->json(['message' => 'Cabang Bank berhasil dihapus']);
    }

    // JENIS REKENING
    public function getJenisRekening() {
        return DB::connection('mysql')->table('jenis_rekening')->paginate(10);
    }
    public function getJenisRekeningById($id) {
        $data = DB::connection('mysql')->table('jenis_rekening')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Jenis rekening tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createJenisRekening(Request $request) {
        $data = $request->only(['nama_jenis', 'biaya_admin']);
        $id = DB::connection('mysql')->table('jenis_rekening')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Jenis Rekening berhasil ditambah'], 201);
    }
    public function updateJenisRekening(Request $request, $id) {
        $data = $request->only(['nama_jenis', 'biaya_admin']);
        DB::connection('mysql')->table('jenis_rekening')->where('id', $id)->update($data);
        return response()->json(['message' => 'Jenis Rekening berhasil diupdate']);
    }
    public function deleteJenisRekening($id) {
        DB::connection('mysql')->table('jenis_rekening')->where('id', $id)->delete();
        return response()->json(['message' => 'Jenis Rekening berhasil dihapus']);
    }

    // TRANSAKSI PEMBAYARAN
    public function getTransaksiPembayaran() {
        return DB::connection('mysql')->table('transaksi_pembayaran')->paginate(10);
    }
    public function getTransaksiPembayaranById($id) {
        $data = DB::connection('mysql')->table('transaksi_pembayaran')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createTransaksiPembayaran(Request $request) {
        $data = $request->only(['no_rekening', 'total_bayar', 'tanggal_bayar', 'status']);
        $id = DB::connection('mysql')->table('transaksi_pembayaran')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Transaksi berhasil dicatat'], 201);
    }
    public function updateTransaksiPembayaran(Request $request, $id) {
        $data = $request->only(['status']);
        DB::connection('mysql')->table('transaksi_pembayaran')->where('id', $id)->update($data);
        return response()->json(['message' => 'Status Transaksi diupdate']);
    }
    public function deleteTransaksiPembayaran($id) {
        DB::connection('mysql')->table('transaksi_pembayaran')->where('id', $id)->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }

    // DETAIL PEMBAYARAN
    public function getDetailPembayaran() {
        return DB::connection('mysql')->table('detail_pembayaran')->paginate(10);
    }
    public function getDetailPembayaranById($id) {
        $data = DB::connection('mysql')->table('detail_pembayaran')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Detail tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function createDetailPembayaran(Request $request) {
        $data = $request->only(['id_transaksi', 'item_pembayaran', 'nominal']);
        $id = DB::connection('mysql')->table('detail_pembayaran')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Detail berhasil dicatat'], 201);
    }
    public function updateDetailPembayaran(Request $request, $id) {
        $data = $request->only(['item_pembayaran', 'nominal']);
        DB::connection('mysql')->table('detail_pembayaran')->where('id', $id)->update($data);
        return response()->json(['message' => 'Detail diupdate']);
    }
    public function deleteDetailPembayaran($id) {
        DB::connection('mysql')->table('detail_pembayaran')->where('id', $id)->delete();
        return response()->json(['message' => 'Detail dihapus']);
    }


    // MUTASI
    public function getMutasi() {
        return DB::connection('mysql')->table('mutasi_transaksi')->paginate(10);
    }
    public function getMutasiById($id) {
        $data = DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->first();
        if (!$data) return response()->json(['message' => 'Data tidak ditemukan'], 404);
        return response()->json($data);
    }
    public function getMutasiByRekening($no_rekening) {
        $data = DB::connection('mysql')->table('mutasi_transaksi')->where('no_rekening', $no_rekening)->paginate(10);
        return response()->json($data);
    }
    public function createMutasi(Request $request) {
        $data = $request->only(['no_rekening', 'id_jenis_transaksi', 'nominal']);
        $id = DB::connection('mysql')->table('mutasi_transaksi')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Transaksi berhasil dicatat'], 201);
    }
    public function updateMutasi(Request $request, $id) {
        $data = $request->only(['nominal']);
        DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->update($data);
        return response()->json(['message' => 'Koreksi transaksi berhasil']);
    }
    public function deleteMutasi($id) {
        DB::connection('mysql')->table('mutasi_transaksi')->where('id', $id)->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }


    // STATISTIK
    public function getStatistik() {
        return DB::connection('mysql')->table('statistik')->paginate(10);
    }
    public function createStatistik(Request $request) {
        $data = $request->only(['nama', 'deskripsi']);
        $id = DB::connection('mysql')->table('statistik')->insertGetId($data);
        return response()->json(['id' => $id, 'message' => 'Statistik berhasil dibuat'], 201);
    }
    public function updateStatistik(Request $request, $id) {
        $data = $request->only(['nama', 'deskripsi']);
        DB::connection('mysql')->table('statistik')->where('id', $id)->update($data);
        return response()->json(['message' => 'Statistik berhasil diperbarui']);
    }
    public function deleteStatistik($id) {
        DB::connection('mysql')->table('statistik')->where('id', $id)->delete();
        return response()->json(['message' => 'Statistik berhasil dihapus']);
    }
}