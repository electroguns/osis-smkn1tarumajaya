<?php

namespace App\Http\Controllers;

use App\Models\Suara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SuaraController extends Controller
{
    private function normalizeText($text)
    {
        $text = strtolower($text);

        // angka -> huruf
        $replace = [
            '0' => 'o',
            '1' => 'i',
            '3' => 'e',
            '4' => 'a',
            '5' => 's',
            '7' => 't',
            '@' => 'a',
            '$' => 's',
        ];

        $text = strtr($text, $replace);

        // hapus simbol/spasi/titik/dash
        $text = preg_replace('/[^a-z]/', '', $text);

        // huruf berulang
        $text = preg_replace('/(.)\\1+/', '$1', $text);

        return trim($text);
    }

    private $blacklist = [
        'anjing',
        'anjg',
        'anj',
        'njing',
        'bangsat',
        'bangst',
        'kontol',
        'kntl',
        'itil',
        'ittil',
        'memek',
        'mmk',
        'goblok',
        'gblk',
        'tolol',
        'tlol',
        'bego',
        'bgo',
        'idiot',
        'bgst',
        'ngentot',
        'ngntt',
        'asu',
        'babi',
        'dungu',
        'geblek',
        'plenger',
    ];

    private function fuzzyMatch($text, $word)
    {
        $text = strtolower($text);

        // normalisasi angka jadi huruf
        $replace = [
            '0' => 'o',
            '1' => 'i',
            '3' => 'e',
            '4' => 'a',
            '5' => 's',
            '7' => 't',
        ];
        $text = strtr($text, $replace);

        // huruf-huruf berurutan dengan noise di antaranya
        $pattern = implode('[^a-z]*', str_split($word));
        if (preg_match("/$pattern/i", $text)) {
            return true;
        }

        // toleransi typo 1–2 huruf
        return levenshtein($text, $word) <= 3;
    }

    public function store(Request $request)
    {
        // 1. Validasi Data
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string',
            'rating'   => 'nullable|integer|min:1|max:10',
            'saran'    => 'required|string|min:5|max:1000',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $normalized = $this->normalizeText($request->saran);

        foreach ($this->blacklist as $badword) {
            $normalizedBadword = $this->normalizeText($badword);

            // exact normalized
            if (str_contains($normalized, $normalizedBadword)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bahasa tidak pantas terdeteksi.'
                ], 422);
            }

            // fuzzy match
            if ($this->fuzzyMatch($request->saran, $badword)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bahasa tidak pantas terdeteksi.'
                ], 422);
            }
        }

        $aiResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
            'HTTP-Referer' => env('APP_URL'),

        ])->post('https://openrouter.ai/api/v1/chat/completions', [

            'model' => 'openai/gpt-4o-mini',
            'temperature' => 0,
            'max_tokens' => 80,
            'response_format' => [
                'type' => 'json_object'
            ],
            'messages' => [
                [
                    'role' => 'system',
                    'content' => '
        Anda adalah sistem moderasi komentar Bahasa Indonesia.

        Tugas:
        Deteksi apakah teks mengandung:
        - Kata kasar
        - Hinaan
        - Sarkasme menghina
        - Sindiran toxic
        - Kalimat merendahkan
        - Bullying
        - Pelecehan verbal
        - Bahasa vulgar
        - Typo disengaja untuk kata toxic
        - Singkatan toxic

        JANGAN tandai kritik sopan, saran, atau opini biasa sebagai toxic.

        Komentar tetap dianggap AMAN jika:
        - Mengandung kritik konstruktif
        - Mengandung masukan sopan
        - Tidak menyerang individu
        - Tidak merendahkan pihak lain

        Contoh AMAN:
        - Acara sudah bagus tetapi perlu lebih terorganisir.
        - Saya kurang setuju dengan keputusan tersebut.
        - Semoga koordinasi panitia bisa lebih baik.
        - Terima kasih atas kerja kerasnya.

        Contoh TOXIC:
        - Panitianya goblok.
        - Acara ini kacau banget, bikin malu.
        - OSIS nya payah banget.
        - Tolol sekali keputusan seperti ini.
        - Hebat ya, bisa seburuk ini acaranya.
        - Wah keren, telat lagi seperti biasa.
        - kntl
        - anj1ng

        Jika mengandung unsur toxic/sarkas/hinaan:
        "is_toxic": true

        Jika aman:
        "is_toxic": false

        Balas HANYA JSON valid.

        Format:
        {
        "is_toxic": true,
        "score": 0-100,
        "reason": "Alasan spesifik"
        }
        '
                ],
                [
                    'role' => 'user',
                    'content' => $request->saran
                ]
            ]
        ]);

        // cek request AI gagal
        if ($aiResponse->failed()) {

            return response()->json([
                'status' => 'error',
                'message' => 'AI moderation gagal'
            ], 500);
        }

        // ambil json response
        $aiData = $aiResponse->json();

        // ambil content AI
        $content = $aiData['choices'][0]['message']['content'] ?? null;

        // Handle jika content array
        if (is_array($content)) {

            $content = $content[0]['text'] ?? '';
        }

        if (!$content) {

            return response()->json([
                'status' => 'error',
                'message' => 'AI tidak mengembalikan konten'
            ], 500);
        }

        // bersihkan markdown
        $cleanJson = str_replace(['```json', '```'], '', $content);

        // decode jadi array
        $moderation = json_decode(trim($cleanJson), true);

        // fallback kalau json invalid
        if (!$moderation) {

            return response()->json([
                'status' => 'error',
                'message' => 'Format respon AI tidak valid',
                'raw' => $content,
                'decoded' => $aiData
            ], 500);
        }

        // blok toxic
        if (($moderation['is_toxic'] ?? false) === true) {

            return response()->json([
                'status' => 'error',
                'message' => 'Pesan mengandung kata atau kalimat tidak pantas.',
                'ai_reason' => $moderation['reason'] ?? null,
                'score' => $moderation['score'] ?? 0
            ], 422);
        }

        try {
            // 2. Simpan ke Database
            Suara::create([
                'kategori' => $request->kategori,
                'rating'   => $request->kategori === 'apresiasi' ? $request->rating : null,
                'saran'    => $request->saran,
                'status'   => 'pending',
            ]);

            // 3. Response Berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Suara berhasil terkirim! Terima kasih.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server.'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, [
            'ketua',
            'wakil',
            'sekretaris',
            'ketua_divisi_humas',
            'anggota_divisi_humas'
        ])) {
            abort(403);
        }

        $query = Suara::query();

        $search = $request->input('search') ?? $request->input('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('saran', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('rating', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $suaras = $query->latest('created_at')->paginate(1)->withQueryString();

        $total = Suara::count();
        $pending = Suara::where('status', 'pending')->count();
        $onProgress = Suara::where('status', 'on_progress')->count();
        $resolved = Suara::where('status', 'resolved')->count();

        return view('suara.index', compact(
            'suaras',
            'total',
            'pending',
            'onProgress',
            'resolved'
        ));
    }

    public function exportPdf(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'sekretaris') {
            abort(403, 'Akses ditolak. Hanya Sekretaris yang dapat mengunduh PDF.');
        }

        $query = Suara::query();

        $search = $request->input('search') ?? $request->input('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('saran', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('rating', 'like', '%' . $search . '%');
            });
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal Mulai
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Filter Tanggal Selesai
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $suaras = $query->latest()->get();

        $filters = [
            'search'    => $search ?? 'Semua', 
            'kategori'  => $request->kategori ?? 'Semua',
            'status'    => $request->status ?? 'Semua',
            'tgl_mulai' => $request->start_date ? date('d M Y', strtotime($request->start_date)) : '-',
            'tgl_akhir' => $request->end_date ? date('d M Y', strtotime($request->end_date)) : '-',
        ];

        $pdf = Pdf::loadView('suara.pdf', compact('suaras', 'filters'))
            ->setPaper('a4', 'landscape'); 

        return $pdf->download('Laporan_Aspirasi_Siswa_' . now()->format('Ymd_His') . '.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'ketua_divisi_humas') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:on_progress,resolved,pending'
        ]);

        $suara = Suara::findOrFail($id);

        if ($suara->status === 'resolved') {
            return back()->with(
                'error',
                'Aspirasi yang sudah resolved tidak dapat diubah lagi.'
            );
        }

        $suara->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Status berhasil diperbarui.'
        );
    }
}
