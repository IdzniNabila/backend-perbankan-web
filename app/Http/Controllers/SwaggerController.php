<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="API Core Banking Himatif",
 *   description="Dokumentasi API Perbankan"
 * )
 * @OA\Server(
 *   url="http://127.0.0.1:8000",
 *   description="Server Lokal"
 * )
 * @OA\Get(
 *   path="/api/nasabah",
 *   summary="Ambil Semua Data Nasabah",
 *   tags={"Master Data"},
 *   @OA\Response(response="200", description="Sukses")
 * )
 * @OA\Post(
 *   path="/api/nasabah",
 *   summary="Create Nasabah",
 *   tags={"Master Data"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(property="nik", type="string"),
 *         @OA\Property(property="nama_lengkap", type="string"),
 *         @OA\Property(property="no_hp", type="string")
 *       )
 *     )
 *   ),
 *   @OA\Response(response="201", description="Nasabah berhasil ditambah")
 * )
 * @OA\Put(
 *   path="/api/nasabah/{id}",
 *   summary="Update Nasabah",
 *   tags={"Master Data"},
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(property="nama_lengkap", type="string"),
 *         @OA\Property(property="no_hp", type="string")
 *       )
 *     )
 *   ),
 *   @OA\Response(response="200", description="Data Nasabah berhasil diupdate")
 * )
 * @OA\Delete(
 *   path="/api/nasabah/{id}",
 *   summary="Delete Nasabah",
 *   tags={"Master Data"},
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *   @OA\Response(response="200", description="Data Nasabah berhasil dihapus")
 * )
 * @OA\Get(
 *   path="/api/mutasi",
 *   summary="Lihat Riwayat Transaksi",
 *   tags={"Data Transaksi"},
 *   @OA\Response(response="200", description="Sukses")
 * )
 * @OA\Post(
 *   path="/api/mutasi",
 *   summary="Create Mutasi",
 *   tags={"Data Transaksi"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(property="no_rekening", type="string"),
 *         @OA\Property(property="id_jenis_transaksi", type="integer"),
 *         @OA\Property(property="nominal", type="number", format="float")
 *       )
 *     )
 *   ),
 *   @OA\Response(response="201", description="Transaksi berhasil dicatat")
 * )
 * @OA\Put(
 *   path="/api/mutasi/{id}",
 *   summary="Update Mutasi",
 *   tags={"Data Transaksi"},
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(property="nominal", type="number", format="float")
 *       )
 *     )
 *   ),
 *   @OA\Response(response="200", description="Koreksi transaksi berhasil")
 * )
 * @OA\Delete(
 *   path="/api/mutasi/{id}",
 *   summary="Delete Mutasi",
 *   tags={"Data Transaksi"},
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *   @OA\Response(response="200", description="Transaksi berhasil dihapus")
 * )
 * @OA\Get(
 *   path="/api/statistik",
 *   summary="Tampil Statistik",
 *   tags={"Laporan"},
 *   @OA\Response(response="200", description="Sukses")
 * )
 * @OA\Post(
 *   path="/api/statistik",
 *   summary="Create Statistik",
 *   tags={"Laporan"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(property="nama", type="string"),
 *         @OA\Property(property="deskripsi", type="string")
 *       )
 *     )
 *   ),
 *   @OA\Response(response="201", description="Statistik berhasil dibuat")
 * )
 * @OA\Put(
 *   path="/api/statistik/{id}",
 *   summary="Update Statistik",
 *   tags={"Laporan"},
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(property="nama", type="string"),
 *         @OA\Property(property="deskripsi", type="string")
 *       )
 *     )
 *   ),
 *   @OA\Response(response="200", description="Statistik berhasil diperbarui")
 * )
 * @OA\Delete(
 *   path="/api/statistik/{id}",
 *   summary="Delete Statistik",
 *   tags={"Laporan"},
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *   @OA\Response(response="200", description="Statistik berhasil dihapus")
 * )
 */
class SwaggerController
{
    // Wadah khusus Swagger
}