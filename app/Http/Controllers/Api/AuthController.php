<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle mobile login and issue Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi yang Anda masukkan salah.',
            ], 401);
        }

        $deviceName = $request->device_name ?? 'Android_Device_' . date('YmdHis');
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kelas' => $user->kelas,
                'jabatan' => $user->jabatan,
                'qr_token' => $user->qr_token,
                'foto' => $user->foto ? asset('storage/struktural/' . $user->foto) : asset('image/fotoosis.png'),
                'authority' => $this->getRoleAuthority($user->role),
            ],
        ]);
    }

    /**
     * Get authenticated user profile.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kelas' => $user->kelas,
                'jabatan' => $user->jabatan,
                'qr_token' => $user->qr_token,
                'foto' => $user->foto ? asset('storage/struktural/' . $user->foto) : asset('image/fotoosis.png'),
                'authority' => $this->getRoleAuthority($user->role),
            ],
        ]);
    }

    /**
     * Logout and revoke current token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Sesi telah diakhiri.',
        ]);
    }

    /**
     * Helper to map role authorities exactly matching the web hak akses.
     */
    /**
     * Helper to map role authorities exactly matching the web hak akses.
     * Ref: StrukturController, FinanceController, ActivityReportController,
     *      EventController, SuaraController, BeritaController.
     */
    private function getRoleAuthority(?string $role): array
    {
        $isPimpinan     = in_array($role, ['ketua', 'wakil']);
        $isSekretaris   = $role === 'sekretaris';
        $isBendahara    = $role === 'bendahara';
        $isBph          = $isSekretaris || $isBendahara;
        $isKetuaDivisi  = str_starts_with($role ?? '', 'ketua_divisi_');
        $isAnggotaDivisi= str_starts_with($role ?? '', 'anggota_divisi_');
        $isDivisi       = $isKetuaDivisi || $isAnggotaDivisi;

        // Extract divisi name (humas / agama / olahraga / artmedia)
        $divisiName = '';
        if ($isKetuaDivisi) {
            $divisiName = str_replace('ketua_divisi_', '', $role ?? '');
        } elseif ($isAnggotaDivisi) {
            $divisiName = str_replace('anggota_divisi_', '', $role ?? '');
        }

        return [
            // Role flags
            'is_pimpinan'       => $isPimpinan,
            'is_sekretaris'     => $isSekretaris,
            'is_bendahara'      => $isBendahara,
            'is_bph'            => $isBph,
            'is_ketua_divisi'   => $isKetuaDivisi,
            'is_anggota_divisi' => $isAnggotaDivisi,
            'is_divisi'         => $isDivisi,
            'divisi_name'       => $divisiName,

            // Label
            'level' => match (true) {
                $isPimpinan   => 'Ketua & Wakil (Akses Penuh)',
                $isSekretaris => 'Sekretaris',
                $isBendahara  => 'Bendahara',
                $isKetuaDivisi => 'Ketua Divisi ' . ucfirst($divisiName),
                $isAnggotaDivisi => 'Anggota Divisi ' . ucfirst($divisiName),
                default => 'Pengurus OSIS',
            },

            // Struktur Pengurus
            // Tambah: ketua, wakil, sekretaris
            'can_add_pengurus'    => in_array($role, ['ketua', 'wakil', 'sekretaris']),
            // Manage all (edit/hapus semua): ketua, wakil
            'can_manage_all_pengurus' => $isPimpinan,
            // Manage own divisi: ketua_divisi_* bisa edit anggota divisinya
            'can_manage_own_divisi_pengurus' => $isKetuaDivisi,

            // Tracking Laporan
            // Tambah laporan: BUKAN ketua/wakil (semua kecuali pimpinan)
            'can_add_report'      => !$isPimpinan,
            // Purpose laporan: hanya ketua_divisi_*
            'can_purpose_report'  => $isKetuaDivisi,
            // Approve/Reject laporan: hanya sekretaris (web) — API menggunakan sekretaris
            'can_approve_reports' => $isSekretaris,

            // Keuangan
            // Lihat keuangan: ketua, wakil, sekre, bendahara, semua ketua_divisi
            'can_view_finance'    => $isPimpinan || $isBph || $isKetuaDivisi,
            // Tambah/Edit transaksi: ketua, wakil, sekre, bendahara, ketua_divisi_*
            'can_add_finance'     => $isPimpinan || $isBph || $isKetuaDivisi,
            // Tanda "Mengetahui": HANYA bendahara
            'can_mengetahui_finance' => $isBendahara,
            // Tanda "Menyetujui": HANYA ketua/wakil
            'can_menyetujui_finance' => $isPimpinan,

            // Event & Absensi
            // Buat/Hapus event: hanya sekretaris
            'can_manage_events'   => $isSekretaris,
            // Petugas Scanner Barcode: Pimpinan, Sekretaris, dan Divisi Humas
            'can_scan_attendance' => $isPimpinan || $isSekretaris || $role === 'ketua_divisi_humas' || $role === 'anggota_divisi_humas' || $role === 'anggota_humas',
            // Presensi: semua bisa check-in
            'can_attend_event'    => true,

            // Aspirasi Siswa
            // Lihat aspirasi: ketua, wakil, sekretaris, ketua_divisi_humas, anggota_divisi_humas
            'can_view_aspirasi'   => $isPimpinan || $isSekretaris
                || $role === 'ketua_divisi_humas'
                || $role === 'anggota_divisi_humas',
            // Update status aspirasi: HANYA ketua_divisi_humas
            'can_update_aspirasi_status' => $role === 'ketua_divisi_humas',

            // Berita & Kegiatan
            // Buat berita: HANYA anggota_divisi_*
            'can_create_berita'   => $isAnggotaDivisi,
            // Approve/Reject berita: sekretaris=semua; ketua_divisi=divisinya
            'can_approve_berita'  => $isSekretaris || $isKetuaDivisi,
            // Hapus berita: ketua/wakil/sekre=semua; ketua_divisi=divisinya; anggota=milik sendiri
            'can_delete_berita'   => $isPimpinan || $isSekretaris || $isKetuaDivisi || $isAnggotaDivisi,

            // Visi & Misi
            'can_edit_vision_mission' => in_array($role, ['ketua', 'wakil', 'sekretaris']),

            // Logbook Aktivitas (Khusus Ketua & Wakil OSIS)
            'can_view_logbook' => in_array($role, ['ketua', 'wakil']),
        ];
    }
}
