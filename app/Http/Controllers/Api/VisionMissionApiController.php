<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisionMission;
use Illuminate\Http\Request;

class VisionMissionApiController extends Controller
{
    /**
     * Get vision & mission and site tagline.
     */
    public function index()
    {
        $configPath = storage_path('site_config.json');
        $siteConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

        $visi = VisionMission::where('category', 'Visi')->get(['id', 'category', 'description']);
        $misi = VisionMission::where('category', 'Misi')->get(['id', 'category', 'description']);

        return response()->json([
            'success' => true,
            'data' => [
                'periode' => $siteConfig['hero']['badge'] ?? 'Periode 2026',
                'tagline' => $siteConfig['hero']['subtitle'] ?? 'Membangun generasi pemimpin masa depan yang inovatif, berintegritas, dan siap berkontribusi untuk kemajuan sekolah.',
                'visi' => $visi,
                'misi' => $misi,
            ],
        ]);
    }

    /**
     * Create new Vision or Mission (Ketua, Wakil, Sekretaris only).
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Ketua, Wakil, atau Sekretaris yang dapat menambah Visi & Misi.'], 403);
        }

        $validated = $request->validate([
            'category'    => 'required|string|in:Visi,Misi',
            'description' => 'required|string|max:1000',
        ]);

        $item = VisionMission::create($validated);

        if (class_exists(\App\Services\ActivityLogger::class)) {
            \App\Services\ActivityLogger::log(
                'TAMBAH',
                'Visi & Misi',
                "Pengurus ({$user->name}) menambahkan {$validated['category']}: {$validated['description']}",
                null
            );
        }

        return response()->json([
            'success' => true,
            'message' => "{$validated['category']} berhasil ditambahkan.",
            'data'    => $item,
        ]);
    }

    /**
     * Update existing Vision or Mission (Ketua, Wakil, Sekretaris only).
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Ketua, Wakil, atau Sekretaris yang dapat mengedit Visi & Misi.'], 403);
        }

        $item = VisionMission::find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data Visi/Misi tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'category'    => 'required|string|in:Visi,Misi',
            'description' => 'required|string|max:1000',
        ]);

        $item->update($validated);

        if (class_exists(\App\Services\ActivityLogger::class)) {
            \App\Services\ActivityLogger::log(
                'UBAH',
                'Visi & Misi',
                "Pengurus ({$user->name}) memperbarui {$validated['category']} #{$id}: {$validated['description']}",
                null
            );
        }

        return response()->json([
            'success' => true,
            'message' => "{$validated['category']} berhasil diperbarui.",
            'data'    => $item,
        ]);
    }

    /**
     * Delete Vision or Mission (Ketua, Wakil, Sekretaris only).
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Ketua, Wakil, atau Sekretaris yang dapat menghapus Visi & Misi.'], 403);
        }

        $item = VisionMission::find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data Visi/Misi tidak ditemukan.'], 404);
        }

        $category = $item->category;
        $item->delete();

        if (class_exists(\App\Services\ActivityLogger::class)) {
            \App\Services\ActivityLogger::log(
                'HAPUS',
                'Visi & Misi',
                "Pengurus ({$user->name}) menghapus {$category} #{$id}",
                null
            );
        }

        return response()->json([
            'success' => true,
            'message' => "{$category} berhasil dihapus.",
        ]);
    }

    /**
     * Update tagline & periode (Ketua, Wakil, Sekretaris only).
     */
    public function updateHero(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Ketua, Wakil, atau Sekretaris yang dapat mengubah tagline/periode.'], 403);
        }

        $validated = $request->validate([
            'badge' => 'required|string|max:50',
            'subtitle' => 'required|string|max:500',
        ]);

        $configPath = storage_path('site_config.json');
        $siteConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

        $siteConfig['hero']['badge'] = $validated['badge'];
        $siteConfig['hero']['subtitle'] = $validated['subtitle'];

        file_put_contents($configPath, json_encode($siteConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if (class_exists(\App\Services\ActivityLogger::class)) {
            \App\Services\ActivityLogger::log(
                'UBAH',
                'Visi & Misi',
                "Pengurus ({$user->name}) memperbarui Tagline dan Periode Beranda",
                null
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Periode dan tagline beranda berhasil diperbarui.',
            'data' => [
                'badge' => $validated['badge'],
                'subtitle' => $validated['subtitle'],
            ],
        ]);
    }
}
