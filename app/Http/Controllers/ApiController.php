<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0", 
    title: "API Perbankan", 
    description: "Dokumentasi Resmi Sistem Perbankan"
)]
#[OA\Get(
    path: "/api/nasabah",
    summary: "Cek Data Nasabah",
    responses: [
        new OA\Response(response: 200, description: "Berhasil")
    ]
)]

 #[OA\Get(
    path: "/api/mutasi",
    summary: "Lihat Riwayat Transaksi",
    responses: [
        new OA\Response(response: 200, description: "Sukses")
    ]
)]

 #[OA\Get(
    path: "/api/statistik",
    summary: "Tampil Statistik",
    responses: [
        new OA\Response(response: 200, description: "Sukses")
    ]
)]

class ApiController extends BaseController
{
    // Kosongkan saja
}