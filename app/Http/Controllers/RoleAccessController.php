<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAccessController extends Controller
{
    /**
     * Menampilkan Halaman Matriks RBAC (Role-Based Access Control)
     * Khusus hanya dapat diakses oleh akun Ketua OSIS.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || ($user->role !== 'ketua' && $user->email !== '15240006@nusamandiri.ac.id')) {
            abort(403, 'Akses Terbatas: Halaman Matriks RBAC khusus hanya dapat diakses oleh Ketua OSIS.');
        }

        // Seluruh 12 Jabatan Resmi Kepengurusan yang tercantum di sistem
        $roles = [
            'ketua' => [
                'name' => 'Ketua OSIS',
                'category' => 'BPH',
                'badge' => 'Top Executive',
                'badge_color' => 'bg-rose-100 text-rose-800 border-rose-300',
                'level' => 'Tingkat 1 - Otoritas Tertinggi',
                'official_email' => '15240006@nusamandiri.ac.id',
            ],
            'wakil' => [
                'name' => 'Wakil Ketua OSIS',
                'category' => 'BPH',
                'badge' => 'Co-Executive',
                'badge_color' => 'bg-red-100 text-red-800 border-red-300',
                'level' => 'Tingkat 1 - Wakil Pimpinan',
                'official_email' => '15240007@nusamandiri.ac.id',
            ],
            'sekretaris' => [
                'name' => 'Sekretaris',
                'category' => 'BPH',
                'badge' => 'Administrasi',
                'badge_color' => 'bg-amber-100 text-amber-800 border-amber-300',
                'level' => 'Tingkat 2 - Biro Administrasi',
                'official_email' => 'guntur@surawijaya.com',
            ],
            'bendahara' => [
                'name' => 'Bendahara',
                'category' => 'BPH',
                'badge' => 'Keuangan & Kas',
                'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'level' => 'Tingkat 2 - Biro Keuangan',
                'official_email' => '15240002@nusamandiri.ac.id',
            ],
            'ketua_divisi_humas' => [
                'name' => 'Ketua Divisi Humas',
                'category' => 'Divisi',
                'badge' => 'Pimpinan Divisi',
                'badge_color' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
                'level' => 'Tingkat 3 - Pimpinan Divisi',
                'official_email' => '15240008@nusamandiri.ac.id',
            ],
            'anggota_divisi_humas' => [
                'name' => 'Anggota Divisi Humas',
                'category' => 'Divisi',
                'badge' => 'Staff Operasional',
                'badge_color' => 'bg-blue-100 text-blue-800 border-blue-300',
                'level' => 'Tingkat 4 - Pelaksana Teknis',
                'official_email' => 'anggota@osisbekasi.com',
            ],
            'ketua_divisi_agama' => [
                'name' => 'Ketua Divisi Agama',
                'category' => 'Divisi',
                'badge' => 'Pimpinan Divisi',
                'badge_color' => 'bg-purple-100 text-purple-800 border-purple-300',
                'level' => 'Tingkat 3 - Pimpinan Divisi',
                'official_email' => 'surya@abdi.com',
            ],
            'anggota_divisi_agama' => [
                'name' => 'Anggota Divisi Agama',
                'category' => 'Divisi',
                'badge' => 'Staff Operasional',
                'badge_color' => 'bg-purple-50 text-purple-700 border-purple-200',
                'level' => 'Tingkat 4 - Pelaksana Teknis',
                'official_email' => '-',
            ],
            'ketua_divisi_olahraga' => [
                'name' => 'Ketua Divisi Olahraga',
                'category' => 'Divisi',
                'badge' => 'Pimpinan Divisi',
                'badge_color' => 'bg-orange-100 text-orange-800 border-orange-300',
                'level' => 'Tingkat 3 - Pimpinan Divisi',
                'official_email' => '-',
            ],
            'anggota_divisi_olahraga' => [
                'name' => 'Anggota Divisi Olahraga',
                'category' => 'Divisi',
                'badge' => 'Staff Operasional',
                'badge_color' => 'bg-orange-50 text-orange-700 border-orange-200',
                'level' => 'Tingkat 4 - Pelaksana Teknis',
                'official_email' => '-',
            ],
            'ketua_divisi_artmedia' => [
                'name' => 'Ketua Divisi Art & Media',
                'category' => 'Divisi',
                'badge' => 'Pimpinan Divisi',
                'badge_color' => 'bg-pink-100 text-pink-800 border-pink-300',
                'level' => 'Tingkat 3 - Pimpinan Divisi',
                'official_email' => '-',
            ],
            'anggota_divisi_artmedia' => [
                'name' => 'Anggota Divisi Art & Media',
                'category' => 'Divisi',
                'badge' => 'Staff Operasional',
                'badge_color' => 'bg-pink-50 text-pink-700 border-pink-200',
                'level' => 'Tingkat 4 - Pelaksana Teknis',
                'official_email' => '-',
            ],
        ];

        return view('rbac.index', compact('roles'));
    }
}
