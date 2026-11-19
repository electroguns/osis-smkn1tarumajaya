<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class ActivityReport extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'activity_reports';

    protected $fillable = [
        'activity_name',
        'activity_date',
        'document_link',
        'pic',
        'type',
        'status',
        'division',
        'user_id',
        'rejection_reason',
    ];

    public function getLogModule(): string
    {
        return 'Tracking Laporan';
    }

    public function getLogDescription(string $event): string
    {
        return match ($event) {
            'created' => "Mengunggah dokumen/laporan baru: {$this->activity_name} ({$this->type})",
            'updated' => "Memperbarui dokumen/laporan: {$this->activity_name}",
            'deleted' => "Menghapus dokumen/laporan: {$this->activity_name}",
            default => "Melakukan aksi {$event} pada laporan: {$this->activity_name}",
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function canManage($user)
    {
        return
            $user->role === 'sekretaris' ||
            $user->role === 'bendahara' ||
            $this->user_id === $user->id ||
            $user->role === 'ketua_divisi_' . $this->division;
    }
}
