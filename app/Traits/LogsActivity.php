<?php

namespace App\Traits;

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected bool $disableLoggingOnce = false;

    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            if ($model->shouldSkipLogging()) return;

            ActivityLogger::log(
                'TAMBAH',
                $model->getLogModule(),
                $model->getLogDescription('created'),
                $model,
                $model->getLogProperties('created')
            );
        });

        static::updated(function (Model $model) {
            if ($model->shouldSkipLogging()) return;

            $changes = $model->getDirty();
            unset($changes['updated_at'], $changes['remember_token'], $changes['password']);

            if (empty($changes)) return;

            $original = [];
            foreach (array_keys($changes) as $key) {
                $original[$key] = $model->getOriginal($key);
            }

            ActivityLogger::log(
                'UBAH',
                $model->getLogModule(),
                $model->getLogDescription('updated'),
                $model,
                [
                    'changes' => $changes,
                    'original' => $original,
                ]
            );
        });

        static::deleted(function (Model $model) {
            if ($model->shouldSkipLogging()) return;

            ActivityLogger::log(
                'HAPUS',
                $model->getLogModule(),
                $model->getLogDescription('deleted'),
                $model,
                $model->getLogProperties('deleted')
            );
        });
    }

    /**
     * Nonaktifkan logging untuk operasi berikutnya pada instance ini
     */
    public function disableLoggingOnce(): static
    {
        $this->disableLoggingOnce = true;
        return $this;
    }

    /**
     * Cek apakah logging harus dilewati
     */
    public function shouldSkipLogging(): bool
    {
        if (ActivityLogger::isSilenced()) {
            return true;
        }

        if (isset($this->enableLogging) && !$this->enableLogging) {
            return true;
        }

        if ($this->disableLoggingOnce) {
            $this->disableLoggingOnce = false;
            return true;
        }

        return false;
    }

    /**
     * Nama modul default (bisa di-override di Model)
     */
    public function getLogModule(): string
    {
        if (property_exists($this, 'logModule') && !empty($this->logModule)) {
            return $this->logModule;
        }

        return class_basename($this);
    }

    /**
     * Identitas data untuk deskripsi (bisa di-override di Model)
     */
    public function getLogIdentifier(): string
    {
        return $this->keterangan
            ?? $this->title
            ?? $this->judul
            ?? $this->name
            ?? $this->nama
            ?? $this->activity_name
            ?? ('#' . $this->id);
    }

    /**
     * Format deskripsi log aksi (bisa di-override di Model)
     */
    public function getLogDescription(string $event): string
    {
        $module = $this->getLogModule();
        $identifier = $this->getLogIdentifier();

        return match ($event) {
            'created' => "Menambahkan data {$module}: {$identifier}",
            'updated' => "Memperbarui data {$module}: {$identifier}",
            'deleted' => "Menghapus data {$module}: {$identifier}",
            default => "Melakukan aksi {$event} pada {$module}: {$identifier}",
        };
    }

    /**
     * Payload metadata properties saat aksi dibuat atau dihapus
     */
    public function getLogProperties(string $event): ?array
    {
        if ($event === 'deleted') {
            $attributes = $this->getAttributes();
            unset($attributes['password'], $attributes['remember_token']);
            return ['deleted_data' => $attributes];
        }

        return null;
    }
}

