<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Suara extends Model
{
    use LogsActivity;

    protected $table = 'suaras';
    
    protected $fillable = ['rating', 'kategori', 'saran','status'];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function getLogModule(): string
    {
        return 'Kotak Aspirasi';
    }

    public function getLogDescription(string $event): string
    {
        $kat = ucfirst($this->kategori ?? 'Umum');
        return match ($event) {
            'created' => "Siswa mengirim aspirasi baru (Kategori: {$kat})",
            'updated' => "Mengubah aspirasi siswa {$kat} menjadi status: {$this->status}",
            'deleted' => "Menghapus pesan aspirasi siswa ({$kat})",
            default => "Melakukan aksi {$event} pada kotak aspirasi",
        };
    }
}
