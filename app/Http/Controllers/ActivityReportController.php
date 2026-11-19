<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityReport;
use App\Notifications\ActivityReportNotification;
use App\Models\User;

class ActivityReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('q');

        $query = ActivityReport::with('user');

        // Filter data berdasarkan hak akses role
        if (in_array($user->role, ['sekretaris', 'bendahara', 'ketua', 'wakil'])) {
            // Full access, tidak perlu filter where tambahan
        } elseif (str_contains($user->role, '_divisi_')) {
            $division = explode('_divisi_', $user->role)[1];
            $query->where(function ($q) use ($user, $division) {
                $q->where('division', 'like', '%' . $division)
                    ->orWhere('pic', $user->name);
            });
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('pic', $user->name);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('activity_name', 'like', "%{$search}%")
                    ->orWhere('pic', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $allUsers = User::select('id', 'name', 'role')->get();
        $roleGrouped = $allUsers->groupBy('role');

        return view('reports.tracking-laporan', compact('reports', 'roleGrouped'));
    }

    public function store(Request $request)
    {
        if (in_array(Auth::user()->role, ['ketua', 'wakil'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah laporan.');
        }

        $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'document_link' => 'required|url',
            'pic'           => 'required|string|max:255',
            'division'      => 'required|string|max:100',
        ]);

        $report = ActivityReport::create([
            'activity_name' => $request->activity_name,
            'activity_date' => $request->activity_date,
            'document_link' => $request->document_link,
            'pic'           => $request->pic,
            'type'          => 'unpurpose',
            'division'      => $request->division,
            'user_id'       => Auth::id(),
            'status'        => 'draft',
        ]);

        $user = Auth::user();

        $parts = explode('_', $user->role);
        $division = end($parts);

        $ketuaDivisi = User::where(
            'role',
            'ketua_divisi_' . $division
        )->first();

        if ($ketuaDivisi && $ketuaDivisi->id !== $user->id) {
            $ketuaDivisi->notify(
                new ActivityReportNotification($report, 'draft')
            );
        }

        return redirect()->back()->with(
            'success',
            'Laporan berhasil disimpan dan menunggu Purpose Ketua Divisimu.'
        );
    }

    public function update(Request $request, $id)
    {
        $report = ActivityReport::findOrFail($id);

        if (!$report->canManage(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah laporan ini.');
        }

        $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'division'      => 'required|string|max:100',
            'pic'           => 'required|string|max:100',
            'document_link' => 'required|url',
        ]);

        $report->update([
            'activity_name' => $request->activity_name,
            'activity_date' => $request->activity_date,
            'division'      => $request->division,
            'pic'           => $request->pic,
            'document_link' => $request->document_link,
        ]);

        if ($report->status === 'rejected') {
            $report->status = 'draft';
            $report->save();
        }

        $userRole = $report->user->role;
        if (str_contains($userRole, '_divisi_')) {
            $division = explode('_divisi_', $userRole)[1];
            $ketuaDivisi = User::where(
                'role',
                'ketua_divisi_' . $division
            )->first();

            if ($ketuaDivisi) {
                $ketuaDivisi->notify(
                    new ActivityReportNotification($report, 'draft')
                );
            }
        }
        return redirect()->back()->with(
            'success',
            'Data laporan berhasil diperbarui!'
        );
    }

    public function purpose($id)
    {
        $report = ActivityReport::findOrFail($id);

        if (!str_contains(Auth::user()->role, 'ketua_divisi_')) {
            abort(403, 'Hanya Ketua Divisi yang dapat mem-purpose laporan.');
        }

        $report->update([
            'status' => 'purpose',
            'type'   => 'purpose',
        ]);

        // Notifikasi Sekretaris
        $sekretaris = User::where('role', 'sekretaris')->first();

        if ($sekretaris) {
            $sekretaris->notify(
                new ActivityReportNotification($report, 'purpose')
            );
        }

        return redirect()->back()
            ->with('success', 'Laporan berhasil dipurpose.');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'sekretaris') {
            abort(403);
        }

        $report = ActivityReport::findOrFail($id);

        if ($report->status !== 'purpose') {
            return redirect()->back()
                ->with('error', 'Laporan harus dipurpose dulu.');
        }

        \App\Services\ActivityLogger::log(
            'SETUJUI',
            'Tracking Laporan',
            "Sekretaris (" . Auth::user()->name . ") menyetujui dokumen/laporan: {$report->activity_name}",
            $report
        );

        $report->disableLoggingOnce()->update([
            'status' => 'approved'
        ]);

        // Email ke pembuat laporan
        $report->user->notify(
            new ActivityReportNotification($report, 'approved')
        );

        return redirect()->back()
            ->with('success', 'Laporan berhasil di-approve.');
    }

    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'sekretaris') {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $report = ActivityReport::findOrFail($id);

        \App\Services\ActivityLogger::log(
            'TOLAK',
            'Tracking Laporan',
            "Sekretaris (" . Auth::user()->name . ") menolak dokumen/laporan: {$report->activity_name} (Alasan: {$request->rejection_reason})",
            $report,
            ['alasan' => $request->rejection_reason]
        );

        $report->disableLoggingOnce()->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Email ke pembuat laporan
        $report->user->notify(
            new ActivityReportNotification($report, 'rejected')
        );

        return redirect()->back()
            ->with('success', 'Laporan berhasil ditolak.');
    }

    public function destroy($id)
    {
        $report = ActivityReport::findOrFail($id);
        $user = Auth::user();

        if (in_array($user->role, ['sekretaris', 'bendahara']) || $report->user_id === $user->id) {
            $report->delete();
            return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
        }

        abort(403, 'Anda tidak memiliki hak untuk menghapus laporan ini.');
    }
}
