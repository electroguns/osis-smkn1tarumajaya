<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class BeritaApiController extends Controller
{
    /**
     * List news with role-based filtering (matches web BeritaController::index).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->input('q') ?? $request->input('search');

        if (!$user) {
            // Guest: only published
            $query = Berita::published();
        } else {
            $role = $user->role;

            // Ketua, Wakil, Sekretaris - see ALL news (drafts & published)
            if (in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
                $query = Berita::query();
            }
            // Ketua Divisi - see news for their division (draft & published)
            elseif (str_contains($role, 'ketua_divisi_')) {
                $userDivision = str_replace('ketua_divisi_', '', $role);
                $query = Berita::where('divisi', $userDivision);
            }
            // Anggota Divisi - see published news + own drafts
            elseif (str_contains($role, 'anggota_divisi_')) {
                $query = Berita::where(function ($q) use ($user) {
                    $q->where('status', 'published')
                        ->orWhere('author_id', $user->id);
                });
            }
            // Other roles - only published
            else {
                $query = Berita::published();
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $beritas = $query->latest('created_at')->get()->map(function ($b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'slug' => $b->slug,
                'excerpt' => $b->excerpt ?? substr(strip_tags($b->content), 0, 120) . '...',
                'content' => $b->content,
                'status' => $b->status,
                'divisi' => $b->divisi,
                'rejection_reason' => $b->rejection_reason,
                'author_id' => $b->author_id,
                'author' => $b->author ? $b->author->name : ($b->user ? $b->user->name : 'Pengurus OSIS'),
                'published_at' => $b->published_at ? $b->published_at->format('d M Y') : null,
                'created_at' => $b->created_at ? $b->created_at->format('d M Y H:i') : null,
                'image_url' => $b->image ? asset('storage/berita/' . $b->image) : asset('image/hero.png'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $beritas,
        ]);
    }

    /**
     * Show single news detail.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $b = Berita::findOrFail($id);

        if ($b->status !== 'published') {
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan atau belum dipublikasikan.'], 404);
            }

            $role = $user->role;
            if (!in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
                if (str_contains($role, 'ketua_divisi_')) {
                    $userDivision = str_replace('ketua_divisi_', '', $role);
                    if ($b->divisi !== $userDivision) {
                        return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
                    }
                } elseif (str_contains($role, 'anggota_divisi_')) {
                    if ($b->author_id !== $user->id) {
                        return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $b->id,
                'title' => $b->title,
                'slug' => $b->slug,
                'excerpt' => $b->excerpt,
                'content' => $b->content,
                'status' => $b->status,
                'divisi' => $b->divisi,
                'rejection_reason' => $b->rejection_reason,
                'author_id' => $b->author_id,
                'author' => $b->author ? $b->author->name : 'Pengurus OSIS',
                'published_at' => $b->published_at ? $b->published_at->format('d F Y') : null,
                'created_at' => $b->created_at ? $b->created_at->format('d M Y H:i') : null,
                'image_url' => $b->image ? asset('storage/berita/' . $b->image) : asset('image/hero.png'),
            ],
        ]);
    }

    /**
     * Store new draft news (Anggota Divisi only).
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!str_contains($user->role ?? '', 'anggota_divisi_')) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Anggota Divisi yang dapat membuat berita baru.'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'divisi' => 'required|in:inti,humas,artmedia,olahraga,agama',
        ]);

        $slug = Str::slug($request->title);
        $count = Berita::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $filename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = storage_path('app/public/berita');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
        }

        $berita = Berita::create([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'slug' => $slug,
            'status' => 'draft',
            'published_at' => null,
            'author_id' => $user->id,
            'image' => $filename,
            'divisi' => $request->divisi,
        ]);

        // Kirim notifikasi ke Ketua Divisi dan Sekretaris
        $kadivRole = 'ketua_divisi_' . $request->divisi;
        $approvers = User::where('role', $kadivRole)
            ->orWhere('role', 'sekretaris')
            ->get();

        if ($approvers->isNotEmpty()) {
            Notification::send(
                $approvers,
                new \App\Notifications\BeritaMenungguPersetujuanNotification($berita, $user)
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dibuat dengan status Draft. Menunggu persetujuan Kadiv/Sekretaris.',
            'data' => $berita,
        ], 201);
    }

    /**
     * Approve news (Sekretaris or Ketua Divisi).
     */
    public function approve(Request $request, $id)
    {
        $user = $request->user();
        $berita = Berita::findOrFail($id);
        $role = $user->role;

        if ($role === 'sekretaris') {
            // Sekretaris can approve all news
        } elseif (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi !== $userDivision) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menyetujui berita divisi lain.'
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki wewenang untuk menyetujui berita.'
            ], 403);
        }

        \App\Services\ActivityLogger::log(
            'SETUJUI',
            'Berita',
            "Menyetujui dan mempublikasikan artikel berita: {$berita->title}",
            $berita
        );

        $berita->disableLoggingOnce()->update([
            'status' => 'published',
            'rejection_reason' => null,
            'published_at' => now(),
        ]);

        if ($berita->author) {
            $berita->author->notify(new \App\Notifications\BeritaApprovedNotification($berita));
        }

        $subscribers = Subscriber::all();
        if ($subscribers->isNotEmpty()) {
            Notification::send($subscribers, new \App\Notifications\BeritaTerbaruNotification($berita));
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita telah disetujui, dipublikasikan, dan author telah dinotifikasi!',
            'data' => $berita,
        ]);
    }

    /**
     * Reject news (Sekretaris or Ketua Divisi).
     */
    public function reject(Request $request, $id)
    {
        $user = $request->user();
        $berita = Berita::findOrFail($id);
        $role = $user->role;

        if ($role === 'sekretaris') {
            // Sekretaris can reject all
        } elseif (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi !== $userDivision) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menolak berita divisi lain.'
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki wewenang untuk menolak berita.'
            ], 403);
        }

        $request->validate([
            'alasan' => 'required|string|max:1000',
        ]);

        \App\Services\ActivityLogger::log(
            'TOLAK',
            'Berita',
            "Menolak artikel berita '{$berita->title}' dengan alasan: {$request->alasan}",
            $berita,
            ['alasan' => $request->alasan]
        );

        $berita->disableLoggingOnce()->update([
            'status' => 'draft',
            'rejection_reason' => $request->alasan,
            'published_at' => null,
        ]);

        if ($berita->author) {
            $berita->author->notify(new \App\Notifications\BeritaRejectedNotification($berita, $request->alasan));
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita telah ditolak dan dikembalikan ke draft.',
            'data' => $berita,
        ]);
    }

    /**
     * Delete news.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $berita = Berita::findOrFail($id);
        $role = $user->role;

        if (in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
            // Can delete all
        } elseif (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi !== $userDivision) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya dapat menghapus berita dari divisi Anda sendiri.'
                ], 403);
            }
        } elseif (str_contains($role, 'anggota_divisi_')) {
            if ($berita->author_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya dapat menghapus berita yang Anda buat sendiri.'
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus berita.'
            ], 403);
        }

        if ($berita->image) {
            Storage::delete('public/berita/' . $berita->image);
        }

        $berita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus secara permanen.',
        ]);
    }
}
