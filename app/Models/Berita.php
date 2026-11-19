<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Berita extends Model
{
    use LogsActivity;

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image', 'status', 'rejection_reason', 'author_id', 'published_at', 'divisi'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Scope untuk menyaring berita yang sudah publish (bisa diakses publik/guest)
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function statusLabelFor($user = null)
    {
        if (!$user) {
            return $this->status === 'published' ? 'published' : null;
        }

        $role = $user->role;

        // Ketua, Wakil, Sekretaris - bisa lihat semua
        if (in_array($role, ['ketua', 'wakil', 'sekretaris'])) {
            return $this->status;
        }

        // Ketua Divisi - bisa lihat berita divisi mereka sendiri
        if (str_contains($role, 'ketua_divisi_')) {
            $userDivision = str_replace('ketua_divisi_', '', $role);
            if ($this->divisi === $userDivision) {
                return $this->status;
            }
        }

        // Anggota Divisi - bisa lihat draft milik sendiri + published semua
        if (str_contains($role, 'anggota_divisi_')) {
            if ($this->author_id === $user->id) {
                return $this->status;
            }
        }

        return $this->status === 'published' ? 'published' : null;
    }

    // Relasi ke tabel User (Penulis Berita)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getLogModule(): string
    {
        return 'Berita';
    }

    public function getLogDescription(string $event): string
    {
        return match ($event) {
            'created' => "Membuat draf/publikasi berita baru: {$this->title}",
            'updated' => "Memperbarui artikel berita: {$this->title}",
            'deleted' => "Menghapus artikel berita: {$this->title}",
            default => "Melakukan aksi {$event} pada berita: {$this->title}",
        };
    }
}