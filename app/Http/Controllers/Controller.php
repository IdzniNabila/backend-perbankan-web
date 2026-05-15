<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API Core Banking Himatif",
 * description="Dokumentasi resmi untuk Sistem Informasi Perbankan"
 * )
 *
 * @OA\Get(
 * path="/api/nasabah",
 * summary="Ambil Semua Data Nasabah",
 * @OA\Response(response="200", description="Sukses mengambil data")
 * )
 */
abstract class Controller
{
    // Bawaan asli Laravel
}
