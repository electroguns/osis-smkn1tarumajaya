<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogbookApiController extends Controller
{
    /**
     * Get Logbook Activities & Statistics (Ketua & Wakil only).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['ketua', 'wakil'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akses terbatas. Halaman Logbook hanya dapat diakses oleh Ketua dan Wakil Ketua OSIS.',
            ], 403);
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

        // 4. Filter Berdasarkan Rentang Waktu
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

        $logs = $query->latest('id')->take(100)->get();

        // Metrik Statistik
        $totalActivities = ActivityLog::count();
        $todayActivities = ActivityLog::whereDate('created_at', Carbon::today())->count();
        $weekActivities = ActivityLog::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $activePengurusCount = ActivityLog::whereNotNull('user_id')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->distinct('user_id')
            ->count('user_id');

        $modulesList = ActivityLog::distinct()->pluck('module')->filter()->values();
        $actionsList = ActivityLog::distinct()->pluck('action')->filter()->values();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_activities' => $totalActivities,
                    'today_activities' => $todayActivities,
                    'week_activities' => $weekActivities,
                    'active_pengurus_count' => $activePengurusCount,
                ],
                'modules' => $modulesList,
                'actions' => $actionsList,
                'logs' => $logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'user_id' => $log->user_id,
                        'user_name' => $log->user_name,
                        'user_role' => $log->user_role,
                        'action' => $log->action,
                        'module' => $log->module,
                        'description' => $log->description,
                        'ip_address' => $log->ip_address,
                        'created_at' => $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : null,
                        'created_at_human' => $log->created_at ? $log->created_at->diffForHumans() : null,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Clear all activity logs (Ketua only).
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'ketua') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Ketua OSIS yang berhak menghapus seluruh riwayat logbook.',
            ], 403);
        }

        ActivityLog::truncate();

        if (class_exists(\App\Services\ActivityLogger::class)) {
            \App\Services\ActivityLogger::log(
                'BERSIHKAN',
                'Logbook',
                "Ketua OSIS ({$user->name}) mengosongkan seluruh riwayat logbook aktivitas pengurus.",
                null
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Seluruh riwayat logbook aktivitas berhasil dibersihkan.',
        ]);
    }
}
