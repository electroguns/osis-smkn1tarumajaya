<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    /**
     * List all activity reports.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = ActivityReport::with('user:id,name,role,foto')->latest();

        // If user is from a divisi (not Ketua/Wakil/Sekretaris), filter by their division or their own reports
        $isPimpinan = in_array($user->role, ['ketua', 'wakil', 'sekretaris']);
        if (!$isPimpinan) {
            $userDivisi = str_replace(['ketua_divisi_', 'anggota_divisi_'], '', $user->role);
            $query->where('division', $userDivisi);
        }

        $reports = $query->get();

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }

    /**
     * Store new activity report. Ketua/Wakil CANNOT create reports.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Ketua & Wakil tidak bisa menginput laporan (web: abort 403)
        if (in_array($user->role, ['ketua', 'wakil'])) {
            return response()->json(['success' => false, 'message' => 'Ketua dan Wakil tidak memiliki akses untuk menambah laporan.'], 403);
        }

        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'document_link' => 'required|url',
            'pic' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'division' => 'required|string|max:50',
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = 'Pending';

        $report = ActivityReport::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Laporan kegiatan berhasil diajukan.',
            'data' => $report,
        ], 201);
    }

    /**
     * Update report approval status.
     * Web rule: HANYA sekretaris yang bisa approve (bukan ketua/wakil di web).
     */
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        // Mirror web: hanya sekretaris yang approve (via mobile API kita izinkan sekretaris)
        if ($user->role !== 'sekretaris') {
            return response()->json(['success' => false, 'message' => 'Hanya Sekretaris yang berhak menyetujui laporan.'], 403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'rejection_reason' => 'nullable|string',
        ]);

        $report = ActivityReport::findOrFail($id);
        $report->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan kegiatan berhasil diperbarui.',
            'data' => $report,
        ]);
    }

    /**
     * Purpose report (Ketua Divisi only — naikan ke Sekretaris).
     */
    public function purpose(Request $request, $id)
    {
        $user = $request->user();
        if (!str_starts_with($user->role, 'ketua_divisi_')) {
            return response()->json(['success' => false, 'message' => 'Hanya Ketua Divisi yang dapat mem-purpose laporan.'], 403);
        }

        $report = ActivityReport::findOrFail($id);
        $report->update(['status' => 'purpose', 'type' => 'purpose']);

        return response()->json(['success' => true, 'message' => 'Laporan berhasil dipurpose ke Sekretaris.', 'data' => $report]);
    }

    /**
     * Delete a report (sekretaris/bendahara/owner).
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $report = ActivityReport::findOrFail($id);

        $canDelete = in_array($user->role, ['sekretaris', 'bendahara']) || $report->user_id === $user->id;
        if (!$canDelete) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak untuk menghapus laporan ini.'], 403);
        }

        $report->delete();
        return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus.']);
    }
}
