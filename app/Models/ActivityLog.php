<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User pembuat aksi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Morph ke subject data yang diubah
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Label resmi Role dalam Bahasa Indonesia
     */
    public function getRoleLabelAttribute(): string
    {
        $role = $this->user_role ?? ($this->user ? $this->user->role : null);

        $roleLabels = [
            'ketua' => 'Ketua OSIS',
            'wakil' => 'Wakil Ketua OSIS',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'ketua_divisi_humas' => 'Ketua Divisi Humas',
            'ketua_divisi_agama' => 'Ketua Divisi Agama',
            'ketua_divisi_olahraga' => 'Ketua Divisi Olahraga',
            'ketua_divisi_artmedia' => 'Ketua Divisi Art & Media',
            'anggota_divisi_humas' => 'Anggota Divisi Humas',
            'anggota_divisi_agama' => 'Anggota Divisi Agama',
            'anggota_divisi_olahraga' => 'Anggota Divisi Olahraga',
            'anggota_divisi_artmedia' => 'Anggota Divisi Art & Media',
            'admin' => 'Administrator',
            'guest' => 'Tamu / Sistem'
        ];

        return $roleLabels[$role] ?? ($role ? ucwords(str_replace('_', ' ', $role)) : 'Pengurus OSIS');
    }

    /**
     * Kelas Badge Warna untuk Aksi
     */
    public function getActionBadgeClassesAttribute(): array
    {
        $action = strtoupper($this->action);

        return match ($action) {
            'CREATE', 'TAMBAH' => [
                'bg' => 'bg-emerald-500/10 text-emerald-700 border-emerald-200',
                'dot' => 'bg-emerald-500',
                'label' => 'Tambah Data',
            ],
            'UPDATE', 'UBAH' => [
                'bg' => 'bg-amber-500/10 text-amber-700 border-amber-200',
                'dot' => 'bg-amber-500',
                'label' => 'Ubah Data',
            ],
            'DELETE', 'HAPUS' => [
                'bg' => 'bg-rose-500/10 text-rose-700 border-rose-200',
                'dot' => 'bg-rose-500',
                'label' => 'Hapus Data',
            ],
            'APPROVE', 'SETUJUI' => [
                'bg' => 'bg-emerald-500/15 text-emerald-800 border-emerald-300',
                'dot' => 'bg-emerald-600',
                'label' => 'Persetujuan',
            ],
            'REJECT', 'TOLAK' => [
                'bg' => 'bg-red-500/15 text-red-800 border-red-300',
                'dot' => 'bg-red-600',
                'label' => 'Penolakan',
            ],
            'STATUS' => [
                'bg' => 'bg-sky-500/10 text-sky-700 border-sky-200',
                'dot' => 'bg-sky-500',
                'label' => 'Ubah Status',
            ],
            'SIARAN', 'BROADCAST' => [
                'bg' => 'bg-purple-500/10 text-purple-700 border-purple-200',
                'dot' => 'bg-purple-500',
                'label' => 'Siaran Notifikasi',
            ],
            default => [
                'bg' => 'bg-slate-500/10 text-slate-700 border-slate-200',
                'dot' => 'bg-slate-500',
                'label' => ucfirst(strtolower($action)),
            ]
        };
    }

    /**
     * Waktu relatif yang human-friendly
     */
    public function getTimeAgoAttribute(): string
    {
        Carbon::setLocale('id');
        return $this->created_at ? $this->created_at->diffForHumans() : '-';
    }
}

