<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\User;
use App\Notifications\BeritaDirevisiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscriber;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $user = Auth::user();

        if (!$user) {
            // Guest: hanya melihat yang sudah published
            $query = Berita::published();
        } else {
            $role = $user->role;

            // Ketua, Wakil, Sekretaris - bisa lihat SEMUA berita
            if (in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
                $query = Berita::query();
            }
            // Ketua Divisi - bisa lihat berita divisi mereka sendiri (draft & published)
            elseif (str_contains($role, 'ketua_divisi_')) {
                $userDivision = str_replace('ketua_divisi_', '', $role);
                $query = Berita::where('divisi', $userDivision);
            }
            // Anggota Divisi - bisa lihat published semua + draft milik sendiri
            elseif (str_contains($role, 'anggota_divisi_')) {
                $query = Berita::where(function ($q) use ($user) {
                    $q->where('status', 'published')
                        ->orWhere('author_id', $user->id);
                });
            }
            // Role lain - hanya published
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

        $beritas = $query->latest('created_at')->paginate(6)->withQueryString();

        return view('berita.index', compact('beritas'));
    }

    public function create()
    {
        // Semua Anggota Divisi bisa membuat berita baru
        if (!str_contains(Auth::user()->role, 'anggota_divisi_')) {
            abort(403, 'Hanya Anggota Divisi yang dapat membuat berita baru.');
        }
        return view('berita.create');
    }

    public function store(Request $request)
    {
        $auth = Auth::user();

        if (!str_contains($auth->role, 'anggota_divisi_')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat berita.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'divisi' => 'required|in:inti,humas,artmedia,olahraga,agama',
        ]);

        try {
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

            // Get user's division from role
            $userDivision = str_replace('anggota_divisi_', '', $auth->role);

            $berita = Berita::create([
                'title' => $request->title,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'slug' => $slug,
                'status' => 'draft',
                'published_at' => null,
                'author_id' => $auth->id,
                'image' => $filename,
                'divisi' => $request->divisi,
            ]);

            // kirim notif ke Kadiv Divisi yang sesuai dan Sekretaris
            $kadivRole = 'ketua_divisi_' . $request->divisi;
            $approvers = User::where('role', $kadivRole)
                ->orWhere('role', 'sekretaris')
                ->get();

            if ($approvers->isNotEmpty()) {
                Notification::send(
                    $approvers,
                    new \App\Notifications\BeritaMenungguPersetujuanNotification(
                        $berita,
                        $auth
                    )
                );
            }

            return redirect()
                ->route('berita.index')
                ->with('success', 'Berita berhasil dibuat dengan status Draft. Menunggu persetujuan Kadiv/Sekretaris.');
        } catch (\Exception $e) {
            return back()
                ->withErrors([
                    'error' => 'Terjadi kesalahan saat menyimpan berita.'
                ])
                ->withInput();
        }
    }

    public function show(Berita $berita)
    {
        $user = Auth::user();

        // Proteksi jika mencoba mengakses URL langsung ke berita berstatus draft
        if ($berita->status !== 'published') {
            if (!$user) {
                abort(404);
            }

            $role = $user->role;

            // Ketua, Wakil, Sekretaris - bisa akses semua draft
            if (!in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
                // Ketua Divisi - bisa akses draft divisi sendiri
                if (str_contains($role, 'ketua_divisi_')) {
                    $userDivision = str_replace('ketua_divisi_', '', $role);
                    if ($berita->divisi !== $userDivision) {
                        abort(404);
                    }
                }
                // Anggota Divisi - bisa akses draft milik sendiri
                elseif (str_contains($role, 'anggota_divisi_')) {
                    if ($berita->author_id !== $user->id) {
                        abort(404);
                    }
                }
                else {
                    abort(404);
                }
            }
        }

        $suggestions = Berita::published()
            ->where('id', '!=', $berita->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('berita.show', compact('berita', 'suggestions'));
    }

    public function edit(Berita $berita)
    {
        $user = Auth::user();
        $role = $user->role;

        // Ketua, Wakil, Sekretaris - bisa edit semua berita
        if (in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
            return view('berita.edit', compact('berita'));
        }

        // Ketua Divisi - bisa edit berita divisi mereka sendiri
        if (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi === $userDivision) {
                return view('berita.edit', compact('berita'));
            }
            abort(403, 'Anda tidak memiliki akses untuk menyunting berita ini.');
        }

        // Anggota Divisi - hanya bisa edit berita miliknya sendiri
        if (str_contains($role, 'anggota_divisi_')) {
            if ($berita->author_id === $user->id) {
                return view('berita.edit', compact('berita'));
            }
            abort(403, 'Anda tidak memiliki akses untuk menyunting berita ini.');
        }

        abort(403, 'Anda tidak memiliki hak akses untuk menyunting berita ini.');
    }

    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'published_at' => 'nullable|date',
            'status' => 'in:draft,published',
        ]);

        $updateData = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'published_at' => $request->published_at,
            'status' => $request->status,
        ];

        $userAktif = Auth::user();

        // handle upload image baru
        if ($request->hasFile('image')) {
            if ($berita->image) {
                @unlink(storage_path('app/public/berita/' . $berita->image));
            }

            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = storage_path('app/public/berita');

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $file->move($path, $filename);
            $updateData['image'] = $filename;
        }

        if (str_contains($userAktif->role, 'anggota_divisi_')) {
            $updateData['rejection_reason'] = null;

            // Hanya kirim notif jika berita masih draft (belum published)
            if ($berita->status === 'draft') {
                $kadivRole = 'ketua_divisi_' . $berita->divisi;
                $kadiv = User::where('role', $kadivRole)->first();

                if ($kadiv) {
                    $kadiv->notify(new BeritaDirevisiNotification($berita, $userAktif));
                }
            }
        }

        $berita->update($updateData);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        $user = Auth::user();
        $role = $user->role;

        // Ketua, Wakil, Sekretaris: bisa hapus semua berita
        if (in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
            // Allow deletion
        }
        // Kadiv Divisi: bisa hapus berita dari divisi sendiri
        elseif (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi !== $userDivision) {
                abort(403, 'Anda hanya dapat menghapus berita dari divisi Anda sendiri.');
            }
        }
        // Anggota Divisi: bisa hapus berita milik sendiri
        elseif (str_contains($role, 'anggota_divisi_')) {
            if ($berita->author_id !== $user->id) {
                abort(403, 'Anda hanya dapat menghapus berita yang Anda buat sendiri.');
            }
        }
        else {
            abort(403, 'Anda tidak memiliki akses untuk menghapus berita.');
        }

        if ($berita->image) {
            Storage::delete('public/berita/' . $berita->image);
        }

        $berita->delete();
        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus secara permanen.');
    }

    /**
     * 🟢 Fitur: Approve Berita (Kadiv Divisi & Sekretaris)
     */
    public function approve(Berita $berita)
    {
        $user = Auth::user();
        $role = $user->role;

        // Sekretaris bisa approve semua berita
        if ($role === 'sekretaris') {
            // Allow approve
        }
        // Ketua Divisi hanya bisa approve berita divisi mereka sendiri
        elseif (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi !== $userDivision) {
                abort(403, 'Anda tidak memiliki wewenang untuk menyetujui berita divisi lain.');
            }
        }
        else {
            abort(403, 'Anda tidak memiliki wewenang untuk menyetujui berita.');
        }

        \App\Services\ActivityLogger::log(
            'SETUJUI',
            'Berita',
            "Menyetujui dan mempublikasikan artikel berita: {$berita->title}",
            $berita
        );

        $berita->disableLoggingOnce()->update([
            'title' => $berita->title,
            'content' => $berita->content,
            'status' => 'published',
            'rejection_reason' => null,
            'published_at' => now()
        ]);

        // 1. KIRIM EMAIL KE AUTHOR BERITA
        if ($berita->author) {
            $berita->author->notify(new \App\Notifications\BeritaApprovedNotification($berita));
        }

        // 2. Kirim notifikasi email ke subscribers umum
        $subscribers = Subscriber::all();
        if ($subscribers->isNotEmpty()) {
            Notification::send($subscribers, new \App\Notifications\BeritaTerbaruNotification($berita));
        }

        return redirect()->route('berita.index')->with('success', 'Berita telah disetujui, dipublikasikan, dan author telah dinotifikasi!');
    }

    /**
     * 🔴 Fitur: Reject Berita (Kadiv Divisi & Sekretaris)
     */
    public function reject(Request $request, Berita $berita)
    {
        $user = Auth::user();
        $role = $user->role;

        // Sekretaris bisa reject semua berita
        if ($role === 'sekretaris') {
            // Allow reject
        }
        // Ketua Divisi hanya bisa reject berita divisi mereka sendiri
        elseif (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($berita->divisi !== $userDivision) {
                abort(403, 'Anda tidak memiliki wewenang untuk menolak berita divisi lain.');
            }
        }
        else {
            abort(403, 'Anda tidak memiliki wewenang untuk menolak berita.');
        }

        // Validasi alasan penolakan wajib diisi
        $request->validate([
            'alasan' => 'required|string|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi agar penulis tahu apa yang harus diperbaiki.'
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
            'published_at' => null
        ]);

        // KIRIM EMAIL KE AUTHOR BERITA BESERTA ALASANNYA
        if ($berita->author) {
            $berita->author->notify(new \App\Notifications\BeritaRejectedNotification($berita, $request->alasan));
        }

        return redirect()->route('berita.index')
            ->with('success', 'Berita telah ditolak dan dikembalikan ke draft.')
            ->with('last_rejected_reason', $request->alasan);
    }
}
