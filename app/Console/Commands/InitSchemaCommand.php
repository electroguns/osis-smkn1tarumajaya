<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class InitSchemaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:init-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import initial SQL schema with AUTO_INCREMENT support for TiDB Cloud';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking database connectivity and schema...');

        try {
            DB::connection()->getPdo();
            $this->info('Database connection established successfully.');
        } catch (\Throwable $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
            return 1;
        }

        // Jika tabel users sudah ada, JANGAN PERNAH ubah atau reset database!
        // Data struktur pengurus dan user yang telah diedit pengguna terjaga 100% aman.
        if (Schema::hasTable('users')) {
            $this->info('Database core tables exist. Preserving existing user data and structure.');
            return 0;
        }

        $sqlPath = database_path('m_osissmkn1tarumajaya.sql');
        if (!file_exists($sqlPath)) {
            $this->error('Initial SQL dump file not found at: ' . $sqlPath);
            return 1;
        }

        $this->info('Initializing brand-new database from ' . $sqlPath . '...');
        $sql = file_get_contents($sqlPath);

        $statements = [];
        $current = [];
            foreach (explode("\n", $sql) as $line) {
                $trimmed = trim($line);
                if (empty($trimmed) || 
                    str_starts_with($trimmed, '--') || 
                    str_starts_with($trimmed, '/*') || 
                    str_starts_with($trimmed, 'SET SQL_MODE') || 
                    str_starts_with($trimmed, 'START TRANSACTION') || 
                    str_starts_with($trimmed, 'SET time_zone') || 
                    str_starts_with($trimmed, 'COMMIT')) {
                    continue;
                }
                $current[] = $line;
                if (str_ends_with($trimmed, ';')) {
                    $statements[] = implode("\n", $current);
                    $current = [];
                }
            }

            $successCount = 0;
            foreach ($statements as $stmt) {
                try {
                    DB::unprepared($stmt);
                    $successCount++;
                } catch (\Throwable $e) {
                    $msg = $e->getMessage();
                    if (!str_contains($msg, 'already exists') && !str_contains($msg, 'Duplicate')) {
                        $this->warn('Notice: ' . substr(trim($stmt), 0, 50) . '... -> ' . $msg);
                    }
                }
            }

            $this->info("Import completed: {$successCount} statements executed.");

            // Jalankan migrasi tambahan yang ada di database/migrations
            $this->info('Running pending migrations...');
            Artisan::call('migrate', ['--force' => true]);
            $this->info('Pending migrations executed.');
        }

        return 0;
    }
}
