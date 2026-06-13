<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API Bank Mini Kampus",
 * description="Dokumentasi RESTful API lengkap untuk sistem perbankan internal skala kampus."
 * )
 * * @OA\Server(
 * url="http://127.0.0.1:8000",
 * description="Server Lokal"
 * )
 * * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="API Token"
 * )
 */
class ApiDocumentation
{
    // File ini dikhususkan HANYA untuk dibaca oleh pemindai Swagger.
    // Jangan tambahkan fungsi logika apapun di sini.
}