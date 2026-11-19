<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $listTahun = Arsip::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $selectedYear = $request->query('tahun');

        $query = Arsip::orderBy('tahun', 'desc');
        if ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }

        $arsips = $query->get();

        return view('layouts.data_arsip', compact('arsips', 'listTahun', 'selectedYear'));
    }

    public function create()
    {
        $user = Auth::user();

        $allowedRoles = [
            'ketua',
            'wakil',
            'sekretaris',
            'ketua_divisi_humas',
            'ketua_divisi_artmedia',
            'anggota_divisi_humas',
            'anggota_divisi_artmedia'
        ];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan arsip.');
        }

        return view('arsip.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Ketua, Wakil, Sekretaris - CRUD
        // Kadiv humas & artmedia - CRUD
        // Anggota divisi humas & artmedia - CR (Create, Read)
        $allowedRoles = [
            'ketua',
            'wakil',
            'sekretaris',
            'ketua_divisi_humas',
            'ketua_divisi_artmedia',
            'anggota_divisi_humas',
            'anggota_divisi_artmedia'
        ];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan arsip.');
        }

        $request->validate([
            'judul' => 'required',
            'tahun' => 'required|digits:4',
            'foto' => 'required|image|max:10240',
            'caption' => 'nullable|string',
        ]);

        try {
            $file = $request->file('foto');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();

            $targetPath = storage_path('app/public/arsip');

            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0755, true);
            }

            $file->move($targetPath, $filename);

            \App\Models\Arsip::create([
                'judul' => $request->judul,
                'tahun' => $request->tahun,
                'foto' => $filename,
                'caption' => $request->caption ?? $request->judul,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('arsip.index')->with('success', 'Kenangan berhasil disimpan!');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan arsip: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan arsip.');
        }
    }

    public function deleteMultiple(Request $request)
    {
        $user = Auth::user();

        // Ketua, Wakil, Sekretaris - CRUD
        // Kadiv humas & artmedia - CRUD
        $allowedRoles = [
            'ketua',
            'wakil',
            'sekretaris',
            'ketua_divisi_humas',
            'ketua_divisi_artmedia'
        ];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus arsip.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:arsips,id',
        ]);

        $arsips = Arsip::whereIn('id', $request->ids)->get();

        foreach ($arsips as $arsip) {
            if ($arsip->foto) {
                Storage::delete('public/arsip/' . $arsip->foto);
            }
            $arsip->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Beberapa arsip berhasil dihapus.'
        ]);
    }
}
