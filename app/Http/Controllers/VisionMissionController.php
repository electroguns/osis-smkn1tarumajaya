<?php

namespace App\Http\Controllers;

use App\Models\VisionMission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VisionMissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!Auth::check() || !in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris'])) {
                    abort(403, 'Anda tidak memiliki hak akses untuk mengelola Visi & Misi.');
                }
                return $next($request);
            }),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = VisionMission::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $visions = $query->latest('created_at')->paginate(10)->withQueryString();

        $configPath = storage_path('site_config.json');
        $siteConfig = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

        return view('visionmission.index', compact('visions', 'siteConfig'));
    }

    public function updateHeroConfig(Request $request)
    {
        $request->validate([
            'badge'    => 'required|string|max:100',
            'subtitle' => 'required|string|max:1000',
        ]);

        $configPath = storage_path('site_config.json');
        $config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];

        $config['hero']['badge'] = trim($request->badge);
        $config['hero']['subtitle'] = trim($request->subtitle);

        file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if (class_exists(\App\Services\ActivityLogger::class)) {
            $user = Auth::user();
            \App\Services\ActivityLogger::log(
                'UBAH',
                'Visi & Misi',
                "Pengurus ({$user->name} - {$user->role}) memperbarui Periode ({$request->badge}) dan Tagline Hero Beranda",
                null
            );
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Periode dan Tagline Beranda berhasil diperbarui!',
                'hero' => $config['hero']
            ]);
        }

        return redirect()->route('visionmission.index')->with('success', 'Periode dan Tagline Beranda berhasil diperbarui!');
    }

    public function create()
    {
        return view('visionmission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'required|string|in:Visi,Misi',
            'description' => 'required|string',
        ]);

        VisionMission::create($request->only('category', 'description'));

        return redirect()->route('visionmission.index')->with('success', 'Vision and Mission created successfully.');
    }

    public function edit(VisionMission $visionmission)
    {
        return view('visionmission.edit', compact('visionmission'));
    }

    public function update(Request $request, VisionMission $visionmission)
    {
        $request->validate([
            'category'    => 'required|string|in:Visi,Misi',
            'description' => 'required|string',
        ]);

        $visionmission->update($request->only('category', 'description'));

        return redirect()->route('visionmission.index')->with('success', 'Vision and Mission updated successfully.');
    }

    public function destroy(VisionMission $visionmission)
    {
        try {
            $visionmission->delete();
            return redirect()->route('visionmission.index')
                ->with('success', 'Vision and Mission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('visionmission.index')
                ->with('error', 'Gagal menghapus data.');
        }
    }
}
