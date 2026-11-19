<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Illuminate\Http\Request;

class FinanceApiController extends Controller
{
    private $allowedRoles = [
        'ketua',
        'wakil',
        'sekretaris',
        'bendahara',
        'ketua_divisi_humas',
        'ketua_divisi_agama',
        'ketua_divisi_olahraga',
        'ketua_divisi_artmedia',
    ];

    /**
     * List all transactions with summary (matches web FinanceController::index).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // If authenticated via /api/finances, enforce role access
        if ($user && !in_array($user->role, $this->allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk membuka data Keuangan.'
            ], 403);
        }

        // Transactions list
        if ($user) {
            $finances = Finance::orderBy('tanggal', 'desc')->get();
        } else {
            // Public endpoint
            $finances = Finance::where('status', 'Menyetujui')->orderBy('tanggal', 'desc')->get();
        }

        $totalIncome = Finance::where('jenis', 'pemasukan')->sum('nominal');
        $totalExpense = Finance::where('jenis', 'pengeluaran')->sum('nominal');
        $balance = $totalIncome - $totalExpense;

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'saldo' => (int) $balance,
                    'total_pemasukan' => (int) $totalIncome,
                    'total_pengeluaran' => (int) $totalExpense,
                ],
                'transactions' => $finances,
            ],
        ]);
    }

    /**
     * Store new financial transaction.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $this->allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk menambah data keuangan.'
            ], 403);
        }

        if (str_contains($user->role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $user->role);
            if ($request->divisi !== $userDivision) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sebagai Ketua Divisi, Anda hanya boleh menginput keuangan untuk divisi Anda sendiri.'
                ], 422);
            }
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'divisi' => 'required|in:inti,humas,artmedia,olahraga,agama',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:1',
        ]);

        $validated['status'] = 'Menunggu';

        $finance = Finance::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi keuangan berhasil ditambahkan!',
            'data' => $finance,
        ], 201);
    }

    /**
     * Update 2-step approval status (Bendahara -> Mengetahui, Ketua/Wakil -> Menyetujui).
     */
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => 'required|in:Mengetahui,Menyetujui',
        ]);

        $finance = Finance::findOrFail($id);
        $newStatus = $request->input('status');

        if ($newStatus === 'Mengetahui') {
            if ($user->role !== 'bendahara') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya role Bendahara yang memiliki wewenang untuk menandai transaksi sebagai Mengetahui.'
                ], 403);
            }

            if ($finance->status !== 'Menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi hanya dapat ditandai Mengetahui jika berstatus Menunggu.'
                ], 422);
            }
        } elseif ($newStatus === 'Menyetujui') {
            if (!in_array($user->role, ['ketua', 'wakil'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Ketua OSIS atau Wakil Ketua OSIS yang memiliki wewenang untuk menyetujui transaksi.'
                ], 403);
            }

            if ($finance->status !== 'Mengetahui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi harus berstatus Mengetahui dari Bendahara terlebih dahulu sebelum disetujui.'
                ], 422);
            }
        }

        $nom = number_format($finance->nominal, 0, ',', '.');
        if ($newStatus === 'Menyetujui') {
            \App\Services\ActivityLogger::log(
                'SETUJUI',
                'Keuangan',
                "Ketua/Wakil OSIS ({$user->name}) menyetujui transaksi keuangan: {$finance->keterangan} (Rp {$nom})",
                $finance,
                ['status_sebelum' => $finance->status, 'status_baru' => $newStatus]
            );
        } else {
            \App\Services\ActivityLogger::log(
                'STATUS',
                'Keuangan',
                "Bendahara ({$user->name}) menandai 'Mengetahui' pada transaksi keuangan: {$finance->keterangan} (Rp {$nom})",
                $finance,
                ['status_sebelum' => $finance->status, 'status_baru' => $newStatus]
            );
        }

        $finance->disableLoggingOnce()->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi keuangan berhasil diperbarui menjadi ' . $newStatus . '!',
            'data' => $finance,
        ]);
    }

    /**
     * Delete financial transaction.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $finance = Finance::findOrFail($id);

        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'bendahara'];
        if (in_array($user->role, $allowedRoles)) {
            // Full access delete
        } elseif (str_contains($user->role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $user->role);
            if ($finance->divisi !== $userDivision) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya bisa menghapus data keuangan untuk divisi Anda sendiri.'
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data keuangan.'
            ], 403);
        }

        $finance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi keuangan berhasil dihapus!',
        ]);
    }
}
