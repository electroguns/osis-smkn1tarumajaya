<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Halaman Utama Logbook Aktivitas Pengurus (Khusus Ketua & Wakil OSIS)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Otoritas hanya untuk Ketua dan Wakil Ketua OSIS
        if (!$user || !in_array($user->role, ['ketua', 'wakil'])) {
            abort(403, 'Akses terbatas. Halaman Logbook hanya dapat diakses oleh Ketua OSIS dan Wakil Ketua OSIS.');
        }

        $query = ActivityLog::with('user');

        // 1. Filter Pencarian Teks
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('user_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('module', 'like', "%{$q}%")
                    ->orWhere('action', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%");
            });
        }

        // 2. Filter Berdasarkan Modul
        if ($request->filled('module') && $request->module !== 'all') {
            $query->where('module', $request->module);
        }

        // 3. Filter Berdasarkan Aksi
        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', strtoupper($request->action));
        }

        // 4. Filter Berdasarkan Role Pengurus
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('user_role', $request->role);
        }

        // 5. Filter Rentang Waktu
        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->period === '7_days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            } elseif ($request->period === '30_days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            } elseif ($request->period === 'this_month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        // Pagination
        $logs = $query->latest('id')->paginate(15)->withQueryString();

        // Metrik Statistik untuk Header Cards
        $totalActivities = ActivityLog::count();
        $todayActivities = ActivityLog::whereDate('created_at', Carbon::today())->count();
        $weekActivities = ActivityLog::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        $activePengurusCount = ActivityLog::whereNotNull('user_id')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->distinct('user_id')
            ->count('user_id');

        $topActor = ActivityLog::selectRaw('user_name, user_role, count(*) as total')
            ->whereNotNull('user_name')
            ->where('user_role', '!=', 'guest')
            ->groupBy('user_name', 'user_role')
            ->orderByDesc('total')
            ->first();

        // Opsi-opsi filter dinamis dari data logbook
        $modulesList = ActivityLog::distinct()->pluck('module')->filter()->values();
        $actionsList = ActivityLog::distinct()->pluck('action')->filter()->values();

        $roleLabels = [
            'ketua' => 'Ketua OSIS',
            'wakil' => 'Wakil Ketua OSIS',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'ketua_divisi_humas' => 'Ketua Divisi Humas',
            'ketua_divisi_agama' => 'Ketua Divisi Agama',
            'ketua_divisi_olahraga' => 'Ketua Divisi Olahraga',
            'ketua_divisi_artmedia' => 'Ketua Divisi Art & Media',
            'anggota_divisi_humas' => 'Anggota Divisi Humas',
            'anggota_divisi_agama' => 'Anggota Divisi Agama',
            'anggota_divisi_olahraga' => 'Anggota Divisi Olahraga',
            'anggota_divisi_artmedia' => 'Anggota Divisi Art & Media',
        ];

        return view('logbook.index', compact(
            'logs',
            'totalActivities',
            'todayActivities',
            'weekActivities',
            'activePengurusCount',
            'topActor',
            'modulesList',
            'actionsList',
            'roleLabels'
        ));
    }

    /**
     * API / Detail modal satu logbook
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['ketua', 'wakil'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $log = ActivityLog::with('user')->findOrFail($id);

        return response()->json([
            'id' => $log->id,
            'user_name' => $log->user_name,
            'user_role' => $log->user_role,
            'role_label' => $log->role_label,
            'action' => $log->action,
            'action_badge' => $log->action_badge_classes,
            'module' => $log->module,
            'description' => $log->description,
            'properties' => $log->properties,
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'created_at' => $log->created_at ? $log->created_at->translatedFormat('d F Y, H:i:s') . ' WIB' : '-',
            'time_ago' => $log->time_ago,
        ]);
    }

    /**
     * Bersihkan riwayat logbook (Khusus Ketua OSIS)
     */
    public function clear(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'ketua') {
            abort(403, 'Hanya Ketua OSIS yang berhak membersihkan riwayat logbook.');
        }

        $scope = $request->input('scope', 'all');

        if ($scope === 'all' || $scope === '0') {
            ActivityLog::truncate();
            return redirect()->route('logbook.index')
                ->with('success', 'Semua riwayat logbook aktivitas pengurus telah dibersihkan total.');
        }

        $days = (int) $scope;
        if ($days > 0) {
            $deletedCount = ActivityLog::where('created_at', '<', Carbon::now()->subDays($days))->delete();
            if ($deletedCount > 0) {
                $msg = "Berhasil membersihkan {$deletedCount} catatan logbook yang lebih lama dari {$days} hari.";
                ActivityLogger::log(
                    'HAPUS',
                    'Sistem',
                    "Ketua OSIS ({$user->name}) membersihkan {$deletedCount} catatan logbook (> {$days} hari)"
                );
            } else {
                $msg = "Tidak ditemukan catatan logbook yang lebih lama dari {$days} hari.";
            }
            return redirect()->route('logbook.index')->with('success', $msg);
        }

        return redirect()->route('logbook.index');
    }
}

