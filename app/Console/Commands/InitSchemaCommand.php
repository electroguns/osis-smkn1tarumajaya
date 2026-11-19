<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
    protected $description = 'Import initial SQL schema if database tables do not exist';

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

        // Cek apakah tabel utama sudah ada
        if (Schema::hasTable('users') && Schema::hasTable('beritas')) {
            $this->info('Database already has core tables (users, beritas). Skipping initial dump.');
            return 0;
        }

        $sqlPath = database_path('m_osissmkn1tarumajaya.sql');
        if (!file_exists($sqlPath)) {
            $this->error('Initial SQL dump file not found at: ' . $sqlPath);
            return 1;
        }

        $this->info('Importing schema from ' . $sqlPath . '...');
        $sql = file_get_contents($sqlPath);

        // Pisahkan menjadi statement individual
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
        $errorCount = 0;

        foreach ($statements as $stmt) {
            try {
                DB::unprepared($stmt);
                $successCount++;
            } catch (\Throwable $e) {
                // Abaikan jika tabel atau index sudah ada
                $msg = $e->getMessage();
                if (str_contains($msg, 'already exists') || str_contains($msg, 'Duplicate')) {
                    $successCount++;
                } else {
                    $this->warn('Notice on statement: ' . substr(trim($stmt), 0, 60) . '... -> ' . $msg);
                    $errorCount++;
                }
            }
        }

        $this->info("Import completed: {$successCount} statements executed successfully ({$errorCount} non-critical warnings).");
        return 0;
    }
}
