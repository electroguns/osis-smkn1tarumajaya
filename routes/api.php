<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\StrukturApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\FinanceApiController;
use App\Http\Controllers\Api\SuaraApiController;
use App\Http\Controllers\Api\BeritaApiController;
use App\Http\Controllers\Api\VisionMissionApiController;

/*
|--------------------------------------------------------------------------
| Mobile RESTful API Routes (OSIS SMKN 1 Tarumajaya)
|--------------------------------------------------------------------------
| Digunakan langsung oleh Aplikasi Kotlin Mobile (Android APK & iOS)
*/

// --- Protected by OSIS API Security Firewall (Anti-Scraping, Anti-SQL Injection & Rate Limiting) ---
Route::middleware(['api.firewall', 'throttle:60,1'])->group(function () {

    // --- 1. Autentikasi Publik (Max 10 percobaan/menit untuk mencegah Brute-Force) ---
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    // --- 2. Konten Publik / Guest ---
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/struktur', [StrukturApiController::class, 'index']);
    Route::get('/berita', [BeritaApiController::class, 'index']);
    Route::get('/berita/{id}', [BeritaApiController::class, 'show']);
    Route::get('/vision-mission', [VisionMissionApiController::class, 'index']);
    Route::post('/suara', [SuaraApiController::class, 'store']); // Kirim aspirasi dari siswa
    Route::get('/finances/public', [FinanceApiController::class, 'index']); // Transparansi keuangan publik

    // --- 3. Area Terautentikasi (Bearer Token / Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {
        // Auth & Profil Pengguna
        Route::get('/user', [AuthController::class, 'me']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Struktur Organisasi
        Route::post('/struktur', [StrukturApiController::class, 'store']);           // Tambah pengurus (ketua/wakil/sekretaris)
        Route::put('/struktur/{id}', [StrukturApiController::class, 'update']);      // Edit pengurus
        Route::delete('/struktur/{id}', [StrukturApiController::class, 'destroy']);  // Hapus pengurus

        // Tracking Laporan Kegiatan
        Route::get('/reports', [ReportApiController::class, 'index']);
        Route::post('/reports', [ReportApiController::class, 'store']);
        Route::patch('/reports/{id}/status', [ReportApiController::class, 'updateStatus']);
        Route::patch('/reports/{id}/purpose', [ReportApiController::class, 'purpose']);   // Purpose oleh ketua divisi
        Route::delete('/reports/{id}', [ReportApiController::class, 'destroy']);           // Hapus laporan

        // Absensi & Event
        Route::get('/events', [EventApiController::class, 'index']);
        Route::get('/events/{id}', [EventApiController::class, 'show']);
        Route::post('/events', [EventApiController::class, 'store']);                // Buat event (sekretaris)
        Route::delete('/events/{id}', [EventApiController::class, 'destroy']);       // Hapus event (sekretaris)
        Route::post('/events/{id}/attend', [EventApiController::class, 'attend']);
        Route::post('/events/{id}/scan', [EventApiController::class, 'scan']);              // Petugas scan barcode pengurus
        Route::get('/events/{id}/attendances', [EventApiController::class, 'attendances']); // Rekap daftar hadir event
        Route::post('/events/{id}/broadcast', [EventApiController::class, 'broadcast']);    // Siaran email event (sekretaris)

        // Manajemen Kas Keuangan OSIS
        Route::get('/finances', [FinanceApiController::class, 'index']);
        Route::post('/finances', [FinanceApiController::class, 'store']);
        Route::patch('/finances/{id}/status', [FinanceApiController::class, 'updateStatus']);
        Route::delete('/finances/{id}', [FinanceApiController::class, 'destroy']);

        // Aspirasi Siswa
        Route::get('/suaras', [SuaraApiController::class, 'index']);
        Route::patch('/suaras/{id}/status', [SuaraApiController::class, 'updateStatus']);

        // Berita & Kegiatan
        Route::post('/berita', [BeritaApiController::class, 'store']);               // Buat berita (anggota_divisi)
        Route::post('/berita/{id}/approve', [BeritaApiController::class, 'approve']); // Approve (sekre/ketua_divisi)
        Route::post('/berita/{id}/reject', [BeritaApiController::class, 'reject']);   // Reject (sekre/ketua_divisi)
        Route::delete('/berita/{id}', [BeritaApiController::class, 'destroy']);       // Hapus berita

        // Pengaturan Periode & Tagline (Ketua, Wakil, Sekretaris)
        Route::post('/vision-mission/hero-config', [VisionMissionApiController::class, 'updateHero']);
        Route::post('/vision-mission', [VisionMissionApiController::class, 'store']);
        Route::put('/vision-mission/{id}', [VisionMissionApiController::class, 'update']);
        Route::delete('/vision-mission/{id}', [VisionMissionApiController::class, 'destroy']);

        // Logbook Aktivitas (Khusus Ketua & Wakil OSIS)
        Route::get('/logbook', [\App\Http\Controllers\Api\LogbookApiController::class, 'index']);
        Route::delete('/logbook/clear', [\App\Http\Controllers\Api\LogbookApiController::class, 'clear']);
    });
});
