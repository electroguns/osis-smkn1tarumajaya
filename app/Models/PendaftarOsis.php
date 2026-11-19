<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftarOsis extends Model
{
    protected $table = 'pendaftaran_osis';
    
    protected $fillable = ['nama', 'kelas', 'email', 'no_telp', 'divisi', 'motivasi', 'analisis_ai', 'hard_skills', 'soft_skills', 'status'];

    protected $casts = [
        'analisis_ai' => 'array',
        'hard_skills' => 'array',
        'soft_skills' => 'array',
    ];
}
