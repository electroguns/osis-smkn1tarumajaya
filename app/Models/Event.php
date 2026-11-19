<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;
use App\Traits\LogsActivity;

class Event extends Model
{
    use HasFactory, LogsActivity;

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'title',
        'category',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'batas_waktu_absen',
        'latitude',
        'longitude',
        'is_geofencing',
        'reminder_sent'
    ];

    public function getLogModule(): string
    {
        return 'Agenda & Event';
    }

    public function getLogDescription(string $event): string
    {
        return match ($event) {
            'created' => "Membuat agenda/event baru: {$this->title}",
            'updated' => "Memperbarui data agenda/event: {$this->title}",
            'deleted' => "Menghapus agenda/event: {$this->title}",
            default => "Melakukan aksi {$event} pada agenda: {$this->title}",
        };
    }

    /**
     * Relasi: Satu Event mempunyai banyak data absensi (Hadir, Terlambat, dll)
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}