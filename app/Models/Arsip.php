<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Arsip extends Model
{
    use LogsActivity;

    protected $fillable = ['judul', 'tahun', 'foto', 'caption'];

    public function getLogModule(): string
    {
        return 'Arsip Kenangan';
    }

    public function getLogDescription(string $event): string
    {
        $id = $this->judul ?? ('Tahun ' . $this->tahun);
        return match ($event) {
            'created' => "Mengunggah foto arsip dokumentasi: {$id}",
            'updated' => "Memperbarui data arsip dokumentasi: {$id}",
            'deleted' => "Menghapus foto arsip dokumentasi: {$id}",
            default => "Melakukan aksi {$event} pada arsip dokumentasi",
        };
    }
}
