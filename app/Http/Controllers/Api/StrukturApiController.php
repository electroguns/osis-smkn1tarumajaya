<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StrukturApiController extends Controller
{
    /**
     * Get grouped list of organizational structure.
     */
    public function index()
    {
        $users = User::whereNotNull('role')->get()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'kelas' => $u->kelas,
                'jabatan' => $u->jabatan ?? ucwords(str_replace('_', ' ', $u->role)),
                'foto' => $u->foto ? asset('storage/struktural/' . $u->foto) : asset('image/fotoosis.png'),
            ];
        });

        $divisions = [
            'humas' => 'Humas & Kemitraan',
            'agama' => 'Kerohanian & Keagamaan',
            'olahraga' => 'Olahraga & Kesehatan',
            'artmedia' => 'Art, Media & Kreatif',
        ];

        $divisiData = [];
        foreach ($divisions as $key => $title) {
            $divisiData[$key] = [
                'title' => $title,
                'ketua' => $users->where('role', 'ketua_divisi_' . $key)->values(),
                'anggota' => $users->where('role', 'anggota_divisi_' . $key)->values(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ketua' => $users->where('role', 'ketua')->first(),
                'wakil' => $users->where('role', 'wakil')->first(),
                'sekretaris' => $users->where('role', 'sekretaris')->values(),
                'bendahara' => $users->where('role', 'bendahara')->values(),
                'divisi' => $divisiData,
                'total_pengurus' => $users->count(),
            ],
        ]);
    }

    /**
     * Store new pengurus (ketua, wakil, sekretaris only).
     */
    public function store(Request $request)
    {
        $authUser = $request->user();
        if (!in_array($authUser->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menambah pengurus.'], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'kelas'    => 'required|string|max:100',
            'jabatan'  => ['required', 'in:ketua,wakil,sekretaris,bendahara,ketua_divisi_humas,ketua_divisi_agama,ketua_divisi_olahraga,ketua_divisi_artmedia,anggota_divisi_humas,anggota_divisi_agama,anggota_divisi_olahraga,anggota_divisi_artmedia'],
            'password' => 'required|string|min:6',
        ]);

        $uniqueRoles = ['ketua', 'wakil', 'ketua_divisi_humas', 'ketua_divisi_agama', 'ketua_divisi_olahraga', 'ketua_divisi_artmedia'];
        if (in_array($validated['jabatan'], $uniqueRoles) && User::where('role', $validated['jabatan'])->exists()) {
            return response()->json(['success' => false, 'message' => ucwords(str_replace('_', ' ', $validated['jabatan'])) . ' sudah terisi!'], 422);
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['jabatan'],
            'kelas'    => $validated['kelas'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['success' => true, 'message' => 'Data pengurus berhasil ditambahkan.', 'data' => $user], 201);
    }

    /**
     * Delete pengurus (ketua/wakil = semua; self = diri sendiri).
     */
    public function destroy(Request $request, $id)
    {
        $authUser   = $request->user();
        $targetUser = User::findOrFail($id);

        $isPimpinan = in_array($authUser->role, ['ketua', 'wakil']);
        $isSelf     = $authUser->id === $targetUser->id;

        if (!$isPimpinan && !$isSelf) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus pengurus ini.'], 403);
        }

        $targetUser->delete();
        return response()->json(['success' => true, 'message' => 'Data pengurus berhasil dihapus.']);
    }

    /**
     * Update member profile (with role access enforcement).
     */
    public function update(Request $request, $id)
    {
        $authUser = $request->user();
        $targetUser = User::findOrFail($id);

        $isPimpinan = in_array($authUser->role, ['ketua', 'wakil']);
        $isSekretaris = $authUser->role === 'sekretaris';
        $isKetuaDivisi = str_starts_with($authUser->role, 'ketua_divisi_');
        $isSelf = $authUser->id == $targetUser->id;

        // canBeManagedBy mirror
        if ($isPimpinan) {
            // full access
        } elseif ($isKetuaDivisi) {
            $divisiAuth = str_replace('ketua_divisi_', '', $authUser->role);
            $divisiTarget = str_replace(['ketua_divisi_', 'anggota_divisi_'], '', $targetUser->role);
            if (!$isSelf && $divisiAuth !== $divisiTarget) {
                return response()->json(['success' => false, 'message' => 'Anda hanya bisa mengubah anggota di divisi Anda.'], 403);
            }
        } elseif (!$isSelf) {
            return response()->json(['success' => false, 'message' => 'Anda hanya berhak mengubah profil Anda sendiri.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
        ]);

        if ($isPimpinan && $request->has('role')) {
            $validated['role'] = $request->role;
        }

        $targetUser->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pengurus berhasil diperbarui.',
            'user' => $targetUser,
        ]);
    }
}
