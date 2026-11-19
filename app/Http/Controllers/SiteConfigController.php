<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteConfigController extends Controller
{
    private $configPath;

    public function __construct()
    {
        $this->configPath = storage_path('site_config.json');
    }

    private function getConfig()
    {
        if (!file_exists($this->configPath)) {
            return [
                'hero' => [
                    'background_image' => 'image/hero.png',
                    'badge' => 'Periode 2026',
                    'title' => 'OSIS SMKN 1 Tarumajaya',
                    'subtitle' => 'Membangun generasi pemimpin masa depan yang inovatif, berintegritas, dan siap berkontribusi untuk kemajuan sekolah.',
                    'button_primary_text' => 'Daftar Sekarang',
                    'button_primary_link' => '#pendaftaran',
                    'button_secondary_text' => 'Lihat Struktur',
                    'button_secondary_link' => '#struktur'
                ],
                'contacts' => []
            ];
        }

        $json = file_get_contents($this->configPath);
        return json_decode($json, true);
    }

    private function saveConfig($config)
    {
        file_put_contents($this->configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function updateHero(Request $request)
    {
        $user = Auth::user();

        // Hanya ketua, wakil, sekretaris yang bisa update
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['error' => 'Anda tidak memiliki akses'], 403);
        }

        $config = $this->getConfig();
        $updatedFields = [];

        // Handle file upload
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);
            $config['hero']['background_image'] = 'image/' . $filename;
            $updatedFields[] = 'background gambar';
        } elseif ($request->has('background_image') && $request->filled('background_image')) {
            // Fallback to path input
            $config['hero']['background_image'] = $request->input('background_image');
            $updatedFields[] = 'background gambar';
        }

        // Handle badge (Periode)
        if ($request->has('badge')) {
            $config['hero']['badge'] = trim($request->input('badge'));
            $updatedFields[] = 'periode (' . $config['hero']['badge'] . ')';
        }

        // Handle subtitle (Teks di bawah tulisan OSIS SMKN 1 Tarumajaya)
        if ($request->has('subtitle')) {
            $config['hero']['subtitle'] = trim($request->input('subtitle'));
            $updatedFields[] = 'teks deskripsi hero';
        }

        // Handle title jika diperlukan
        if ($request->has('title')) {
            $config['hero']['title'] = trim($request->input('title'));
            $updatedFields[] = 'judul hero';
        }

        $this->saveConfig($config);

        if (!empty($updatedFields) && class_exists(\App\Services\ActivityLogger::class)) {
            \App\Services\ActivityLogger::log(
                'UBAH',
                'Beranda Utama',
                "Pengurus ({$user->name} - {$user->role}) memperbarui " . implode(', ', $updatedFields) . " pada Hero Beranda",
                null
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Hero berhasil diperbarui',
            'hero' => $config['hero'],
            'path' => $config['hero']['background_image'] ?? null
        ]);
    }

    public function updateContact(Request $request)
    {
        $user = Auth::user();

        // Hanya ketua, wakil, sekretaris yang bisa update
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['error' => 'Anda tidak memiliki akses'], 403);
        }

        $config = $this->getConfig();
        $contactId = $request->input('id');

        // Find and update contact
        foreach ($config['contacts'] as &$contact) {
            if ($contact['id'] == $contactId) {
                // Only update value if provided (inline edit)
                if ($request->has('value')) {
                    $contact['value'] = $request->input('value');
                }
                // Update other fields if provided (full edit)
                if ($request->has('type')) {
                    $contact['type'] = $request->input('type');
                }
                if ($request->has('label')) {
                    $contact['label'] = $request->input('label');
                }
                break;
            }
        }

        $this->saveConfig($config);

        return response()->json(['success' => true, 'message' => 'Kontak berhasil diperbarui']);
    }

    public function storeContact(Request $request)
    {
        $user = Auth::user();

        // Hanya ketua, wakil, sekretaris yang bisa tambah
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['error' => 'Anda tidak memiliki akses'], 403);
        }

        $config = $this->getConfig();

        $newId = count($config['contacts']) + 1;
        $config['contacts'][] = [
            'id' => $newId,
            'type' => $request->input('type'),
            'label' => $request->input('label'),
            'value' => $request->input('value'),
            'order' => count($config['contacts']) + 1
        ];

        $this->saveConfig($config);

        return response()->json(['success' => true, 'message' => 'Kontak berhasil ditambahkan']);
    }

    public function destroyContact(Request $request)
    {
        $user = Auth::user();

        // Hanya ketua, wakil, sekretaris yang bisa hapus
        if (!in_array($user->role, ['ketua', 'wakil', 'sekretaris'])) {
            return response()->json(['error' => 'Anda tidak memiliki akses'], 403);
        }

        $config = $this->getConfig();
        $contactId = $request->input('id');

        // Remove contact
        $config['contacts'] = array_filter($config['contacts'], function($contact) use ($contactId) {
            return $contact['id'] != $contactId;
        });

        // Reorder
        $config['contacts'] = array_values($config['contacts']);
        foreach ($config['contacts'] as $index => &$contact) {
            $contact['order'] = $index + 1;
        }

        $this->saveConfig($config);

        return response()->json(['success' => true, 'message' => 'Kontak berhasil dihapus']);
    }
}
