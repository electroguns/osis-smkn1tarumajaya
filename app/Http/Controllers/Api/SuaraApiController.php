<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Suara;
use Illuminate\Http\Request;

class SuaraApiController extends Controller
{
    /**
     * List all student feedback (Restricted to Humas & Pimpinan/Sekretaris).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $allowedRoles = [
            'ketua',
            'wakil',
            'sekretaris',
            'ketua_divisi_humas',
            'anggota_divisi_humas',
        ];

        if (!$user || !in_array($user->role, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Modul Suara Siswa hanya dapat diakses oleh Pimpinan, Sekretaris, dan Divisi Humas.'
            ], 403);
        }

        $query = Suara::query();

        $search = $request->input('search') ?? $request->input('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('saran', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suaras = $query->latest('created_at')->get();

        $total = Suara::count();
        $pending = Suara::where('status', 'pending')->count();
        $onProgress = Suara::where('status', 'on_progress')->count();
        $resolved = Suara::where('status', 'resolved')->count();

        return response()->json([
            'success' => true,
            'data' => $suaras,
            'summary' => [
                'total' => $total,
                'pending' => $pending,
                'on_progress' => $onProgress,
                'resolved' => $resolved,
            ],
        ]);
    }

    /**
     * Public endpoint: Student submits feedback.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'saran' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:10',
        ]);

        $validated['status'] = 'pending';
        $suara = Suara::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih, aspirasi Anda berhasil dikirim ke pengurus OSIS.',
            'data' => $suara,
        ], 201);
    }

    /**
     * Update feedback status (Restricted to Ketua Divisi Humas only).
     */
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'ketua_divisi_humas') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Ketua Divisi Humas yang memiliki wewenang mengubah status aspirasi.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:on_progress,resolved,pending',
        ]);

        $suara = Suara::findOrFail($id);
        $suara->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status aspirasi berhasil diperbarui menjadi ' . $request->status . '.',
            'data' => $suara,
        ]);
    }
}
