<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API Core Banking Himatif",
 * description="Dokumentasi API Perbankan"
 * )
 * * @OA\Server(
 * url="http://127.0.0.1:8000",
 * description="Server Lokal"
 * )
 * * @OA\Get(
 * path="/api/nasabah",
 * summary="Ambil Semua Data Nasabah",
 * tags={"Master Data"},
 * @OA\Response(response="200", description="Sukses")
 * )
 * * @OA\Get(
 * path="/api/mutasi",
 * summary="Lihat Riwayat Transaksi",
 * tags={"Data Transaksi"},
 * @OA\Response(response="200", description="Sukses")
 * )
 * * @OA\Get(
 * path="/api/statistik",
 * summary="Tampil Statistik",
 * tags={"Laporan"},
 * @OA\Response(response="200", description="Sukses")
 * )
 */
class SwaggerController
{
    // Wadah khusus Swagger
}