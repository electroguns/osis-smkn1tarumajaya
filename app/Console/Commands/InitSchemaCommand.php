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

        $needsRebuild = false;
        if (!Schema::hasTable('users') || !Schema::hasTable('beritas')) {
            $needsRebuild = true;
            $this->info('Core tables missing. Initializing database...');
        } else {
            // Cek apakah users.id sudah memiliki AUTO_INCREMENT
            $col = DB::selectOne("SELECT EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'id'");
            $hasAutoIncrement = str_contains(strtolower($col->EXTRA ?? ''), 'auto_increment');
            if (!$hasAutoIncrement) {
                $this->warn('Detected legacy schema without AUTO_INCREMENT. Rebuilding schema for TiDB Cloud compatibility...');
                $needsRebuild = true;
            } else {
                $this->info('Database is healthy and has AUTO_INCREMENT on primary keys.');
                return 0;
            }
        }

        if ($needsRebuild) {
            $sqlPath = database_path('m_osissmkn1tarumajaya.sql');
            if (!file_exists($sqlPath)) {
                $this->error('Initial SQL dump file not found at: ' . $sqlPath);
                return 1;
            }

            // Drop tabel lama jika perlu rebuild agar bersih dan foreign key tidak konflik
            $this->info('Cleaning existing tables...');
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $t) {
                $tName = array_values((array)$t)[0];
                DB::statement("DROP TABLE IF EXISTS `{$tName}`");
            }
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            $this->info('Importing clean TiDB-compatible schema from ' . $sqlPath . '...');
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
