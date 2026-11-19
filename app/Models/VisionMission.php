<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class VisionMission extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category', // e.g., 'Visi' or 'Misi'
        'description',
    ];

    public function getLogModule(): string
    {
        return 'Visi & Misi';
    }

    public function getLogDescription(string $event): string
    {
        $desc = Str::limit($this->description, 60);
        return match ($event) {
            'created' => "Menambahkan butir {$this->category}: \"{$desc}\"",
            'updated' => "Memperbarui butir {$this->category}: \"{$desc}\"",
            'deleted' => "Menghapus butir {$this->category}: \"{$desc}\"",
            default => "Melakukan aksi {$event} pada {$this->category}",
        };
    }
}