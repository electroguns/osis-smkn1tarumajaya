<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Finance extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'finances';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'divisi',
        'jenis',
        'nominal',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'integer',
    ];

    public function getLogModule(): string
    {
        return 'Keuangan';
    }

    public function getLogDescription(string $event): string
    {
        $nom = number_format($this->nominal, 0, ',', '.');
        $jenis = ucfirst($this->jenis);

        return match ($event) {
            'created' => "Menambahkan transaksi kas ({$jenis}): {$this->keterangan} sebesar Rp {$nom}",
            'updated' => "Memperbarui transaksi kas: {$this->keterangan} ({$jenis} - Rp {$nom})",
            'deleted' => "Menghapus transaksi kas: {$this->keterangan} ({$jenis} - Rp {$nom})",
            default => parent::getLogDescription($event),
        };
    }
}