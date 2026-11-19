<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityLogger
{
    protected static bool $silenced = false;

    /**
     * Catat aktivitas baru ke logbook
     *
     * @param string $action Jenis aksi: TAMBAH, UBAH, HAPUS, SETUJUI, TOLAK, STATUS, SIARAN
     * @param string $module Nama Modul: Keuangan, Berita, Agenda & Event, Pendaftaran OSIS, dll.
     * @param string $description Deskripsi ringkas & jelas mengenai apa yang terjadi
     * @param mixed $subject Model terkait (opsional)
     * @param array|null $properties Data tambahan / snapshot perubahan (opsional)
     * @param mixed $user User spesifik (default: Auth::user())
     * @return ActivityLog|null
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        mixed $subject = null,
        ?array $properties = null,
        mixed $user = null
    ): ?ActivityLog {
        if (self::$silenced) {
            return null;
        }

        try {
            $currentUser = $user ?? Auth::user();

            $userId = $currentUser ? $currentUser->id : null;
            $userName = $currentUser ? $currentUser->name : 'Sistem / Tamu';
            $userRole = $currentUser ? $currentUser->role : 'guest';

            $subjectType = null;
            $subjectId = null;

            if ($subject && is_object($subject)) {
                $subjectType = get_class($subject);
                $subjectId = $subject->id ?? null;
            }

            $ipAddress = null;
            $userAgent = null;

            if (request()) {
                $ipAddress = request()->ip();
                $userAgent = request()->userAgent();
            }

            return ActivityLog::create([
                'user_id' => $userId,
                'user_name' => $userName,
                'user_role' => $userRole,
                'action' => strtoupper($action),
                'module' => $module,
                'description' => $description,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'properties' => $properties,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);
        } catch (Throwable $e) {
            Log::error('ActivityLogger failed to record activity: ' . $e->getMessage(), [
                'exception' => $e,
                'action' => $action,
                'module' => $module
            ]);
            return null;
        }
    }

    /**
     * Jalankan callback tanpa memicu log otomatis
     */
    public static function withoutLogging(callable $callback): mixed
    {
        $previousState = self::$silenced;
        self::$silenced = true;

        try {
            return $callback();
        } finally {
            self::$silenced = $previousState;
        }
    }

    /**
     * Cek apakah logging sedang diheningkan
     */
    public static function isSilenced(): bool
    {
        return self::$silenced;
    }
}

