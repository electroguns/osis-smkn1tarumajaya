<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiSecurityFirewall
{
    /**
     * Pola serangan SQL Injection & Database Hacking umum
     */
    protected array $sqlInjectionPatterns = [
        '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
        '/\b(union(\s+all)?\s+select)\b/i',
        '/\b(select\s+.+\s+from)\b/i',
        '/\b(insert\s+into.+values)\b/i',
        '/\b(delete\s+from)\b/i',
        '/\b(drop\s+(table|database|view))\b/i',
        '/\b(alter\s+table)\b/i',
        '/\b(information_schema|schema_name|table_schema)\b/i',
        '/\b(sleep|benchmark)\s*\(/i',
        '/\b(into\s+(outfile|dumpfile))\b/i',
        '/\b(load_file)\s*\(/i',
        '/\b(or\s+1\s*=\s*1|or\s+\'1\'\s*=\s*\'1\')\b/i',
        '/<script\b[^>]*>(.*?)<\/script>/is',
    ];

    /**
     * User-Agent bot scraper otomatis yang sering melakukan scanning / data extraction
     */
    protected array $badUserAgents = [
        'scrapy',
        'python-requests',
        'aiohttp',
        'httpclient',
        'wget',
        'go-http-client',
        'sqlmap',
        'nikto',
        'havij',
        'acunetix',
        'dirbuster',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = strtolower($request->userAgent() ?? '');

        // 1. BLOKIR BOT WEB SCRAPING OTOMATIS & TOOL EXPLOITATION
        foreach ($this->badUserAgents as $bot) {
            if (str_contains($userAgent, $bot)) {
                Log::warning("[OSIS FIREWALL] Bot Scraper / Hacking Tool diblokir: IP {$ip}, Agent: {$userAgent}");
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Aktivitas web scraping dan automated scanner tidak diizinkan oleh sistem keamanan OSIS.',
                ], 403);
            }
        }

        // 2. BLOKIR PERCOBAAN PERETASAN DATABASE & SQL INJECTION
        $allInputs = $request->all();
        $queryString = $request->getQueryString() ?? '';

        if ($this->containsMaliciousPayload($allInputs) || $this->isStringMalicious($queryString)) {
            Log::alert("[OSIS FIREWALL] Percobaan SQL Injection / Hacking terdeteksi dan diblokir! IP: {$ip}, Path: {$request->path()}");

            return response()->json([
                'success' => false,
                'message' => 'Permintaan diblokir oleh OSIS Security Shield: Terdeteksi pola payload mencurigakan.',
            ], 403);
        }

        $response = $next($request);

        // 3. TAMBAHKAN HEADER KEAMANAN PADA SETIAP RESPONSE API
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }

    /**
     * Rekursif memeriksa array input request terhadap pola SQL Injection
     */
    protected function containsMaliciousPayload(mixed $data): bool
    {
        if (is_array($data)) {
            foreach ($data as $value) {
                if ($this->containsMaliciousPayload($value)) {
                    return true;
                }
            }
            return false;
        }

        if (is_string($data)) {
            return $this->isStringMalicious($data);
        }

        return false;
    }

    /**
     * Cek apakah string mengandung pola SQL Injection berbahaya
     */
    protected function isStringMalicious(string $input): bool
    {
        if (empty($input)) {
            return false;
        }

        $decoded = urldecode($input);

        // Hanya uji pola-pola berat yang jelas merupakan SQLi
        $strictPatterns = [
            '/\b(union(\s+all)?\s+select)\b/i',
            '/\b(drop\s+table)\b/i',
            '/\b(information_schema)\b/i',
            '/\b(sleep|benchmark)\s*\(/i',
            '/\b(into\s+outfile)\b/i',
            '/\b(load_file)\s*\(/i',
            '/\b(or\s+1\s*=\s*1|or\s+\'1\'\s*=\s*\'1\')\b/i',
            '/;\s*(drop|delete|truncate|alter)\s+/i',
            '/<script\b[^>]*>(.*?)<\/script>/is',
        ];

        foreach ($strictPatterns as $pattern) {
            if (preg_match($pattern, $input) || preg_match($pattern, $decoded)) {
                return true;
            }
        }

        return false;
    }
}
