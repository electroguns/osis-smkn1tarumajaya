<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Finance;
use App\Models\User;
use App\Models\VisionMission;
use App\Models\Berita;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    /**
     * Get complete dashboard payload for mobile app.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Site configuration
        $configPath = storage_path('site_config.json');
        $siteConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

        // Statistics
        $anggotaCount = User::whereNotNull('role')->count();
        $programCount = Event::where('category', 'Program')->count();

        // Finance summary
        $totalIncome = Finance::where('jenis', 'pemasukan')->where('status', 'Menyetujui')->sum('nominal');
        $totalExpense = Finance::where('jenis', 'pengeluaran')->where('status', 'Menyetujui')->sum('nominal');
        $balance = $totalIncome - $totalExpense;

        // Visi & Misi
        $landingVisi = VisionMission::where('category', 'Visi')->get(['id', 'category', 'description']);
        $landingMisi = VisionMission::where('category', 'Misi')->get(['id', 'category', 'description']);

        // Latest Beritas
        $beritas = Berita::published()->latest('published_at')->limit(5)->get()->map(function ($b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'slug' => $b->slug,
                'excerpt' => $b->excerpt ?? substr(strip_tags($b->content), 0, 100) . '...',
                'category' => $b->category,
                'published_at' => $b->published_at ? $b->published_at->format('d M Y') : null,
                'image_url' => $b->image ? asset('storage/' . $b->image) : asset('image/hero.png'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'hero' => [
                    'badge' => $siteConfig['hero']['badge'] ?? 'Periode 2026',
                    'title' => strip_tags($siteConfig['hero']['title'] ?? 'OSIS SMKN 1 Tarumajaya'),
                    'subtitle' => $siteConfig['hero']['subtitle'] ?? 'Membangun generasi pemimpin masa depan yang inovatif, berintegritas, dan siap berkontribusi untuk kemajuan sekolah.',
                ],
                'stats' => [
                    'total_anggota' => $anggotaCount,
                    'total_program' => $programCount,
                    'kas_saldo' => (float) $balance,
                    'total_pemasukan' => (float) $totalIncome,
                    'total_pengeluaran' => (float) $totalExpense,
                ],
                'visi' => $landingVisi,
                'misi' => $landingMisi,
                'beritas' => $beritas,
            ],
        ]);
    }
}
