<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'scanned_at',
        'status',
        'notes'
    ];

    // Mengonversi kolom scanned_at menjadi instance Carbon secara otomatis
    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Relasi: Data absen ini milik Event yang mana
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi: Data absen ini milik Pengurus/User siapa
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}