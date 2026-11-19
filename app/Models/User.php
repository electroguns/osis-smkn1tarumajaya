<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'kelas',
        'jabatan',
        'foto',
        'qr_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Generate token QR unik untuk setiap pengurus OSIS
     */
    public static function generateUniqueQrToken(): string
    {
        do {
            $token = 'OSIS-TRMJ-' . strtoupper(Str::random(10));
        } while (static::where('qr_token', $token)->exists());

        return $token;
    }

    /**
     * Accessor qr_token: jika belum ada, otomatis generate & simpan ke database
     */
    public function getQrTokenAttribute($value)
    {
        if (empty($value)) {
            $token = static::generateUniqueQrToken();
            $this->attributes['qr_token'] = $token;
            if ($this->exists) {
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $this->id)
                    ->update(['qr_token' => $token]);
            }
            return $token;
        }
        return $value;
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->qr_token)) {
                $user->qr_token = static::generateUniqueQrToken();
            }
        });
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function canBeManagedBy(User $auth)
    {
        // Ketua & Wakil punya FULL AKSES ke semua data
        if (in_array($auth->role, ['ketua', 'wakil'])) {
            return true;
        }

        // Ketua Divisi bisa edit anggota di divisinya sendiri dan dirinya sendiri
        if (Str::startsWith($auth->role, 'ketua_divisi_')) {
            $divisiAuth = str_replace('ketua_divisi_', '', $auth->role);

            // Bisa edit diri sendiri
            if ($auth->id === $this->id) {
                return true;
            }

            // Bisa edit anggota di divisi yang sama
            if (Str::startsWith($this->role, 'anggota_divisi_')) {
                $divisiTarget = str_replace('anggota_divisi_', '', $this->role);
                return $divisiAuth === $divisiTarget;
            }
        }

        // Bendahara dan Anggota Divisi HANYA bisa mengedit dirinya sendiri
        return $auth->id === $this->id;
    }

    public function activityReports(): HasMany
    {
        return $this->hasMany(ActivityReport::class, 'user_id', 'id');
    }

    public function getLogModule(): string
    {
        return 'Struktur Pengurus';
    }

    public function getLogDescription(string $event): string
    {
        return match ($event) {
            'created' => "Menambahkan data pengurus baru: {$this->name}",
            'updated' => "Memperbarui data pengurus / role: {$this->name}",
            'deleted' => "Menghapus akun pengurus: {$this->name}",
            default => "Melakukan aksi {$event} pada pengurus: {$this->name}",
        };
    }
}
