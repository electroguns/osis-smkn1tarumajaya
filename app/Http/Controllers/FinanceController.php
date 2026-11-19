<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allowedRoles = [
            'ketua',
            'wakil',
            'sekretaris',
            'bendahara',
            'ketua_divisi_humas',
            'ketua_divisi_agama',
            'ketua_divisi_olahraga',
            'ketua_divisi_artmedia'
        ];

        if (!$user || !in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman Statistik Keuangan.');
        }

        // Mengambil semua data keuangan diurutkan dari yang terbaru
        $finances = Finance::orderBy('tanggal', 'desc')->get();

        // Hitung ringkasan data untuk bagian top cards
        $totalIncome = Finance::where('jenis', 'pemasukan')->sum('nominal');
        $totalExpense = Finance::where('jenis', 'pengeluaran')->sum('nominal');
        $balance = $totalIncome - $totalExpense;

        return view('finance.index', compact('finances', 'totalIncome', 'totalExpense', 'balance'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $allowedRoles = [
            'ketua',
            'wakil',
            'sekretaris',
            'bendahara',
            'ketua_divisi_humas',
            'ketua_divisi_agama',
            'ketua_divisi_olahraga',
            'ketua_divisi_artmedia'
        ];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data keuangan.');
        }

        if (str_contains($user->role, 'ketua_divisi')) {
            $userDivision = str_replace('ketua_divisi_', '', $user->role);

            if ($request->divisi !== $userDivision) {
                return redirect()->back()->withErrors(['divisi' => 'Sebagai Ketua Divisi, Anda hanya boleh menginput keuangan untuk divisi Anda sendiri.']);
            }
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'divisi' => 'required|in:inti,humas,artmedia,olahraga,agama',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:1',
        ]);

        Finance::create($validated);

       return redirect()->back()->withFragment('keuangan')->with('success', 'Data transaksi keuangan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $finance = Finance::findOrFail($id);

        // Ketua, Wakil, Sekretaris, Bendahara - full access
        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'bendahara'];
        if (in_array($user->role, $allowedRoles)) {
            // Allow edit
        }
        // Ketua Divisi - only edit their own division
        elseif (str_contains($user->role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $user->role);
            if ($finance->divisi !== $userDivision) {
                abort(403, 'Anda hanya bisa mengedit data keuangan untuk divisi Anda sendiri.');
            }
        }
        // Others - no access
        else {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data keuangan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'divisi' => 'required|in:inti,humas,artmedia,olahraga,agama',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:1',
        ]);

        // Ketua Divisi cannot change division
        if (str_contains($user->role, 'ketua_divisi_')) {
            $validated['divisi'] = $finance->divisi;
        }

        $finance->update($validated);

       return redirect()->back()->withFragment('keuangan')->with('success', 'Data transaksi keuangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $finance = Finance::findOrFail($id);

        // Ketua, Wakil, Sekretaris, Bendahara - full access
        $allowedRoles = ['ketua', 'wakil', 'sekretaris', 'bendahara'];
        if (in_array($user->role, $allowedRoles)) {
            // Allow delete
        }
        // Ketua Divisi - only delete their own division
        elseif (str_contains($user->role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $user->role);
            if ($finance->divisi !== $userDivision) {
                abort(403, 'Anda hanya bisa menghapus data keuangan untuk divisi Anda sendiri.');
            }
        }
        // Others - no access
        else {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data keuangan.');
        }

        $finance->delete();

        return redirect()->back()->withFragment('keuangan')->with('success', 'Data transaksi keuangan berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $finance = Finance::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Mengetahui,Menyetujui',
        ]);

        $newStatus = $request->input('status');

        if ($newStatus === 'Mengetahui') {
            if ($user->role !== 'bendahara') {
                abort(403, 'Hanya role Bendahara yang memiliki wewenang untuk menandai transaksi sebagai Mengetahui.');
            }

            if ($finance->status !== 'Menunggu') {
                return redirect()->back()->withFragment('keuangan')->withErrors(['status' => 'Transaksi hanya dapat ditandai Mengetahui jika berstatus Menunggu.']);
            }
        } elseif ($newStatus === 'Menyetujui') {
            if (!in_array($user->role, ['ketua', 'wakil'])) {
                abort(403, 'Hanya Ketua OSIS atau Wakil Ketua OSIS yang memiliki wewenang untuk menyetujui transaksi.');
            }

            if ($finance->status !== 'Mengetahui') {
                return redirect()->back()->withFragment('keuangan')->withErrors(['status' => 'Transaksi harus berstatus Mengetahui dari Bendahara terlebih dahulu sebelum disetujui.']);
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

        return redirect()->back()->withFragment('keuangan')->with('success', 'Status transaksi keuangan berhasil diperbarui menjadi ' . $newStatus . '!');
    }
}
