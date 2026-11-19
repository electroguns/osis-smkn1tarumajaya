<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OsisController;
use App\Http\Controllers\SuaraController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\StrukturController;
use App\Http\Controllers\VisionMissionController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\SiteConfigController;
use App\Http\Controllers\LogbookController;
use App\Models\Arsip;
use App\Models\User;
use App\Models\VisionMission;
use App\Models\Subscriber;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Berita;
use App\Models\Finance;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// =========================================================================
// INTERFACE PUBLIK
// =========================================================================

// --- Landing Page ---
Route::get('/', function () {
    // Jika user sudah login dan mencoba mengakses link dengan parameter ?login, bersihkan parameter dan redirect
    if (Auth::check() && request()->has('login')) {
        return redirect()->to(request()->fullUrlWithoutQuery(['login']));
    }

    $arsipCount = Arsip::count();
    $albumCount = Arsip::distinct('tahun')->count('tahun');
    $users = User::whereNotNull('role')->get();

    $struktur = [
        'ketua' => $users->where('role', 'ketua')->values(),
        'wakil' => $users->where('role', 'wakil')->values(),
        'sekretaris' => $users->where('role', 'sekretaris')->values(),
        'bendahara' => $users->where('role', 'bendahara')->values(),
        'ketua_divisi_humas' => $users->where('role', 'ketua_divisi_humas')->values(),
        'ketua_divisi_agama' => $users->where('role', 'ketua_divisi_agama')->values(),
        'ketua_divisi_olahraga' => $users->where('role', 'ketua_divisi_olahraga')->values(),
        'ketua_divisi_artmedia' => $users->where('role', 'ketua_divisi_artmedia')->values(),
        'anggota_divisi_humas' => $users->where('role', 'anggota_divisi_humas')->values(),
        'anggota_divisi_agama' => $users->where('role', 'anggota_divisi_agama')->values(),
        'anggota_divisi_olahraga' => $users->where('role', 'anggota_divisi_olahraga')->values(),
        'anggota_divisi_artmedia' => $users->where('role', 'anggota_divisi_artmedia')->values(),
    ];

    $anggotaCount = $users->count();

    $programCount = Event::where('category', 'Program')->count();

    $landingVisi = VisionMission::where('category', 'Visi')->get();
    $landingMisi = VisionMission::where('category', 'Misi')->get();

    $latestBeritas = Berita::published()
        ->latest('published_at')
        ->limit(3)
        ->get();

    // Keuangan: Di halaman front-end publik / live preview, hanya transaksi yang telah disetujui Ketua yang muncul
    if (Auth::check() && !request()->has('preview')) {
        // Tampilan Dashboard Administrator: mengambil ringkasan menyeluruh untuk card modul
        $finances = Finance::orderBy('tanggal', 'desc')->get();
        $totalIncome = Finance::where('jenis', 'pemasukan')->sum('nominal');
        $totalExpense = Finance::where('jenis', 'pengeluaran')->sum('nominal');
        $balance = $totalIncome - $totalExpense;
    } else {
        // Tampilan Front-End (Publik / Live Preview): HANYA transaksi yang telah disetujui oleh Ketua ('Menyetujui')
        $finances = Finance::where('status', 'Menyetujui')->orderBy('tanggal', 'desc')->get();
        $totalIncome = Finance::where('status', 'Menyetujui')->where('jenis', 'pemasukan')->sum('nominal');
        $totalExpense = Finance::where('status', 'Menyetujui')->where('jenis', 'pengeluaran')->sum('nominal');
        $balance = $totalIncome - $totalExpense;
    }

    // Load site config from JSON file
    $configPath = storage_path('site_config.json');
    $siteConfig = [];
    if (file_exists($configPath)) {
        $siteConfig = json_decode(file_get_contents($configPath), true);
    }

    $contacts = $siteConfig['contacts'] ?? [];
    $path = 'registration_status.json';
    $isRegistrationOpen = true;

    if (Storage::exists($path)) {
        $statusData = json_decode(Storage::get($path), true);
        $isRegistrationOpen = ($statusData['status'] ?? 'enable') === 'enable';
    }

    return view('welcome', compact('arsipCount', 'albumCount', 'struktur', 'landingVisi', 'landingMisi', 'anggotaCount', 'latestBeritas', 'isRegistrationOpen', 'programCount', 'finances', 'totalIncome', 'totalExpense', 'balance', 'siteConfig', 'contacts'));
});

// routes/web.php
Route::get('/tes-dini', function () {
    return view('dini'); // Ini maksudnya: kalau buka /dini, tampilkan file dini.blade.php
});

// --- Berita & Publikasi ---
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
Route::get('/struktur-organisasi', [StrukturController::class, 'landingPage']);

// --- Newsletter / Subscriber ---
Route::post('/subscribe-bell', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    Subscriber::firstOrCreate(['email' => $request->email]);
    return response()->json(['message' => 'Berhasil aktifkan notifikasi!']);
});
Route::post('/unsubscribe-bell', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $subscriber = Subscriber::where('email', $request->email)->first();
    if ($subscriber) {
        $subscriber->delete();
        return response()->json(['message' => 'Berhasil berhenti berlangganan.']);
    }
    return response()->json(['message' => 'Email tidak ditemukan.'], 404);
});

// --- Interaksi OSIS (Pendaftaran & Kotak Suara) ---
Route::post('/osis/daftar', [OsisController::class, 'store'])->name('osis.store');
Route::post('/osis/suara', [SuaraController::class, 'store'])->name('suara.store');
Route::post('/osis/suara/kirim', [SuaraController::class, 'store'])->name('suara.kirim');

// =========================================================================
// INTERFACE PENGURUS OSIS
// =========================================================================
Route::middleware('auth')->group(function () {

    // --- Manajemen Pendaftaran OSIS ---
    Route::prefix('admin/osis')->name('admin.osis.')->group(function () {

        // Halaman Utama & Riwayat
        Route::get('/approved-members', [OsisController::class, 'index'])->name('approved');
        Route::get('/calon', [OsisController::class, 'history'])->name('history');

        // Detail Siswa
        Route::get('/{id}/detail', [OsisController::class, 'showDetail'])->name('detail');

        // Aksi Persetujuan (Approve / Reject)
        Route::post('/{id}/approve', [OsisController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [OsisController::class, 'reject'])->name('reject');

        // Kontrol Pendaftaran (Toggle)
        Route::post('/toggle-registration', [OsisController::class, 'toggleRegistration'])->name('toggle');

        // Integrasi WhatsApp Link
        Route::get('/whatsapp-link', [OsisController::class, 'getWhatsAppLink'])->name('whatsapp.get');
        Route::post('/whatsapp-link', [OsisController::class, 'updateWhatsAppLink'])->name('whatsapp.update');

        // Laporan / Export
        Route::get('/approved/export-pdf', [OsisController::class, 'exportApprovedPdf'])->name('approved.exportPdf');
    });

    // --- Manajemen Event & Broadcast ---
    Route::prefix('attendance')->name('event.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/store', [EventController::class, 'store'])->name('store');
    });
    Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');
    Route::post('/event/{id}/broadcast', [EventController::class, 'broadcastEmail'])->name('event.broadcast');

    // --- Absensi & Scanner (Sisi Admin) ---
    Route::get('/admin/attendance/scan/{event}', [AttendanceController::class, 'scan']);
    Route::post('/admin/attendance/scan/{event}', [AttendanceController::class, 'store']);
    Route::get('/attendance/scan/{event}', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('/attendance/scan/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report/{event}', [AttendanceController::class, 'report'])->name('attendance.report');

    // --- Konten Berita & Arsip ---
    Route::resource('berita', BeritaController::class)->except(['index', 'show'])->parameters(['berita' => 'berita']);

    // --- Arsip ---
    Route::get('/arsip/create', [ArsipController::class, 'create'])->name('arsip.create');
    Route::post('/arsip/store', [ArsipController::class, 'store'])->name('arsip.store');
    Route::post('/arsip/delete-multiple', [ArsipController::class, 'deleteMultiple']);

    // --- Master Data (Struktur & Visi Misi) ---
    Route::resource('struktur', StrukturController::class);
    Route::post('/visionmission/hero-config', [VisionMissionController::class, 'updateHeroConfig'])->name('visionmission.update-hero');
    Route::resource('visionmission', VisionMissionController::class)->except(['show']);

    // --- Manajemen Kotak Suara & Aspirasi Siswa ---
    Route::get('/admin/suara', [SuaraController::class, 'index'])->name('suara.index');
    Route::patch('/admin/suara/{id}/status', [SuaraController::class, 'updateStatus'])->name('suara.status');
    Route::get('/admin/suara/export-pdf', [SuaraController::class, 'exportPdf'])->name('suara.export.pdf');

    // --- Berita ---
    Route::post('/berita/{berita}/approve', [BeritaController::class, 'approve'])->name('berita.approve');
    Route::post('/berita/{berita}/reject', [BeritaController::class, 'reject'])->name('berita.reject');

    // --- Manajemen Keuangan ---
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/keuangan', [FinanceController::class, 'store'])->name('finance.store');
    Route::put('/keuangan/{id}', [FinanceController::class, 'update'])->name('finance.update');
    Route::patch('/keuangan/{id}/status', [FinanceController::class, 'updateStatus'])->name('finance.status');
    Route::delete('/keuangan/{id}', [FinanceController::class, 'destroy'])->name('finance.destroy');

    // --- Manajemen Konfigurasi Site (AJAX Inline) ---
    Route::post('/api/site-config/hero', [SiteConfigController::class, 'updateHero'])->name('site-config.update-hero');
    Route::post('/api/site-config/contacts', [SiteConfigController::class, 'storeContact'])->name('site-config.store-contact');
    Route::put('/api/site-config/contacts/{id}', [SiteConfigController::class, 'updateContact'])->name('site-config.update-contact');
    Route::delete('/api/site-config/contacts/{id}', [SiteConfigController::class, 'destroyContact'])->name('site-config.destroy-contact');

    // --- Logbook Aktivitas Pengurus (Audit Trail) ---
    Route::get('/admin/logbook', [LogbookController::class, 'index'])->name('logbook.index');
    Route::get('/admin/logbook/{id}/detail', [LogbookController::class, 'show'])->name('logbook.show');
    Route::delete('/admin/logbook/clear', [LogbookController::class, 'clear'])->name('logbook.clear');

    // Halaman Utama Tracking Laporan
    Route::get('/tracking-laporan', [ActivityReportController::class, 'index'])->name('reports.index');

    // Fitur CRUD Laporan
    Route::post('/tracking-laporan', [ActivityReportController::class, 'store'])->name('reports.store');
    Route::delete('/tracking-laporan/{id}', [ActivityReportController::class, 'destroy'])->name('reports.destroy');
    Route::put('/tracking-laporan/{id}', [ActivityReportController::class, 'update'])->name('reports.update');

    // Fitur Khusus Approval Sekretaris
    Route::patch('/tracking-laporan/{id}/purpose', [ActivityReportController::class, 'purpose'])->name('reports.purpose');
    Route::patch('/tracking-laporan/{id}/approve', [ActivityReportController::class, 'approve'])->name('reports.approve');
    Route::patch('/tracking-laporan/{id}/reject', [ActivityReportController::class, 'reject'])->name('reports.reject');
});

Route::get('/health', function () {
    $dbStatus = 'disconnected';
    $dbError = null;
    $tables = [];
    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
        $rawTables = DB::select('SHOW TABLES');
        foreach ($rawTables as $t) {
            $tables[] = array_values((array)$t)[0];
        }
    } catch (\Throwable $e) {
        $dbError = $e->getMessage();
    }
    return response()->json([
        'status' => 'ok',
        'php' => PHP_VERSION,
        'app_env' => config('app.env'),
        'database' => [
            'status' => $dbStatus,
            'driver' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'database' => config('database.connections.mysql.database'),
            'tables_count' => count($tables),
            'tables' => $tables,
            'error' => $dbError,
        ]
    ]);
});

Route::get('/debug-home', function () {
    try {
        $arsipCount = \App\Models\Arsip::count();
        $albumCount = \App\Models\Arsip::distinct('tahun')->count('tahun');
        $users = \App\Models\User::whereNotNull('role')->get();
        $struktur = [
            'ketua' => $users->where('role', 'ketua')->values(),
            'wakil' => $users->where('role', 'wakil')->values(),
            'sekretaris' => $users->where('role', 'sekretaris')->values(),
            'bendahara' => $users->where('role', 'bendahara')->values(),
        ];
        $anggotaCount = $users->count();
        $programCount = \App\Models\Event::where('category', 'Program')->count();
        $landingVisi = \App\Models\VisionMission::where('category', 'Visi')->get();
        $landingMisi = \App\Models\VisionMission::where('category', 'Misi')->get();
        $latestBeritas = \App\Models\Berita::published()->latest('published_at')->limit(3)->get();
        $finances = \App\Models\Finance::where('status', 'Menyetujui')->orderBy('tanggal', 'desc')->get();
        $totalIncome = 0;
        $totalExpense = 0;
        $balance = 0;
        $siteConfig = [];
        $contacts = [];
        $isRegistrationOpen = true;

        $html = view('welcome', compact('arsipCount', 'albumCount', 'struktur', 'landingVisi', 'landingMisi', 'anggotaCount', 'latestBeritas', 'isRegistrationOpen', 'programCount', 'finances', 'totalIncome', 'totalExpense', 'balance', 'siteConfig', 'contacts'))->render();
        return response('SUCCESS: VIEW RENDERED: ' . strlen($html) . ' bytes');
    } catch (\Throwable $e) {
        return response()->json([
            'error_class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 200);
    }
});

Route::get('/debug-fix-test', function () {
    $rebuildOutput = null;
    if (request('rebuild') == '1') {
        \Illuminate\Support\Facades\Artisan::call('db:init-schema');
        $rebuildOutput = \Illuminate\Support\Facades\Artisan::output();
    }
    $col = DB::selectOne("SELECT COLUMN_NAME, COLUMN_TYPE, EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'id'");
    return response()->json([
        'rebuild_output' => $rebuildOutput,
        'users_id_column' => $col,
        'has_auto_increment' => str_contains(strtolower($col->EXTRA ?? ''), 'auto_increment')
    ]);
});

Route::get('/debug-struktur', function () {
    $results = [];
    
    // 1. Test render create view as Ketua
    try {
        $user = \App\Models\User::first();
        Auth::login($user);
        $view = view('struktur.create')->render();
        $results['view_create'] = 'OK (' . strlen($view) . ' bytes)';
    } catch (\Throwable $e) {
        $results['view_create_error'] = [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }

    // 2. Test render index view as Ketua
    try {
        $req = \Illuminate\Http\Request::create('/struktur', 'GET');
        $resp = app(\App\Http\Controllers\StrukturController::class)->index($req);
        $results['view_index'] = 'OK';
    } catch (\Throwable $e) {
        $results['view_index_error'] = [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }

    // 3. Test store method
    try {
        $req = \Illuminate\Http\Request::create('/struktur', 'POST', [
            'name' => 'Diagnostik User',
            'email' => 'diag_' . time() . '@test.com',
            'kelas' => 'XII TKJ',
            'jabatan' => 'anggota_divisi_humas',
            'password' => 'password123'
        ]);
        $resp = app(\App\Http\Controllers\StrukturController::class)->store($req);
        $results['store_action'] = 'OK (Redirected to ' . $resp->getTargetUrl() . ')';
    } catch (\Throwable $e) {
        $results['store_action_error'] = [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::view('/exp', 'osis.export-approved-pdf')->name('export-pdf');

require __DIR__ . '/auth.php';
