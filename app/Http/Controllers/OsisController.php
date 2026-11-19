<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PendaftarOsis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OsisController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, [
            'ketua',
            'wakil',
            'sekretaris',
            'bendahara',
            'ketua_divisi_humas',
            'anggota_divisi_humas'
        ])) {
            abort(403, 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        $query = PendaftarOsis::where('status', 'approved');

        // 1. Filter Pencarian Teks (Nama)
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        // 2. Filter Berdasarkan Dropdown Dinamis
        $filterType = $request->input('filter_type');
        $filter = '';
        $value = '';

        if ($filterType === 'divisi' && $request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
            $filter = 'divisi';
            $value = $request->divisi;
        } elseif ($filterType === 'kelas' && $request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
            $filter = 'kelas';
            $value = $request->kelas;
        } elseif ($filterType === 'tahun' && $request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
            $filter = 'tahun';
            $value = $request->tahun;
        }

        // 3. Global Default: Jika tidak sedang memfilter 'tahun', batasi data hanya untuk tahun ini
        if ($filterType !== 'tahun') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        // Ambil data untuk tabel
        $approvedMembers = $query->paginate(10)->withQueryString();

        // Data untuk isi dropdown filter (Ambil data tanpa batasan tahun untuk referensi lengkap)
        $divisiList = PendaftarOsis::where('status', 'approved')->distinct()->pluck('divisi')->filter();
        $kelasList  = PendaftarOsis::where('status', 'approved')->distinct()->pluck('kelas')->filter();
        $tahunList  = PendaftarOsis::where('status', 'approved')
            ->selectRaw('YEAR(created_at) as tahun')
            ->pluck('tahun')
            ->unique()
            ->sortDesc();

        $path = 'registration_status.json';
        $isRegistrationOpen = true;

        if (Storage::exists($path)) {
            $statusData = json_decode(Storage::get($path), true);
            $isRegistrationOpen = ($statusData['status'] ?? 'enable') === 'enable';
        }

        return view('osis.approved', compact('approvedMembers', 'divisiList', 'kelasList', 'tahunList', 'filter', 'value', 'isRegistrationOpen'));
    }

    public function history(Request $request)
    {
      
        if (Auth::check() && Str::startsWith(Auth::user()->role, 'anggota_divisi')) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat halaman riwayat ini.');
        }

        $search = $request->input('q');
        $query = PendaftarOsis::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('kelas', 'like', '%' . $search . '%')
                    ->orWhere('divisi', 'like', '%' . $search . '%')
                    ->orWhere('hard_skills', 'like', '%' . $search . '%')
                    ->orWhere('soft_skills', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }
        $pendaftars = $query->latest('created_at')->paginate(6)->withQueryString();
        return view('osis.history', compact('pendaftars'));
    }

    public function showDetail($id)
    {
        $pendaftar = PendaftarOsis::findOrFail($id);

        $analisis = is_string($pendaftar->analisis_ai) ? json_decode($pendaftar->analisis_ai, true) : $pendaftar->analisis_ai;

        return response()->json([
            'nama' => $pendaftar->nama,
            'kelas' => $pendaftar->kelas,
            'motivasi' => $pendaftar->motivasi,
            'analisis' => $analisis['rekomendasi'] ?? 'PERLU DIWAWANCARA'
        ]);
    }

    public function store(Request $request)
    {
        $path = 'registration_status.json';
        if (Storage::exists($path)) {
            $statusData = json_decode(Storage::get($path), true);
            if (($statusData['status'] ?? 'enable') === 'disable') {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, pendaftaran pengurus OSIS saat ini sudah ditutup!'
                ], 403);
            }
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'no_telp' => ['required', 'regex:/^[0-9]{9,15}$/'],
            'divisi' => 'required|in:Ketua,Wakil Ketua,Sekretaris,Bendahara,Humas,ArtMedia,Keagamaan,Olahraga',
            'motivasi' => 'required|string|max:1000',
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
            'HTTP-Referer' => env('APP_URL'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'openai/gpt-4o-mini',
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Anda adalah Sistem Pakar Rekrutmen Pengurus OSIS SMKN 1 Tarumajaya. Tugas Anda adalah menganalisis teks motivasi calon pengurus untuk menghitung probabilitas kelayakan mereka.

                    Sistem penilaian wajib didasarkan pada 5 parameter utama (Skala 1-100 untuk tiap parameter):
                    1. Kesesuaian Divisi (Divisi Pilihan: ' . $request->divisi . '): Seberapa cocok kompetensi/minat pendaftar dengan divisi ini.
                    2. Pengalaman: Adanya rekam jejak organisasi, kepanitiaan, atau proyek sebelumnya.
                    3. Kemampuan Bahasa: Kemampuan komunikasi visual, tulisan, verbal, atau bahasa asing.
                    4. Teamwork: Indikasi kolaborasi, empati, dan kemampuan bekerja dalam kelompok.
                    5. Komitmen: Tingkat dedikasi, tanggung jawab, dan kesiapan meluangkan waktu.

                    RUMUS PROBABILITAS TOTAL:
                    Probabilitas Kelayakan (%) = (Kesesuaian Divisi * 0.30) + (Pengalaman * 0.20) + (Teamwork * 0.20) + (Komitmen * 0.20) + (Kemampuan Bahasa * 0.10)

                    WAJIB Mengembalikan respon dalam format JSON murni tanpa markdown (tanpa ```json) dengan struktur berikut:
                    {
                        "analisis_parameter": {
                            "kesesuaian_divisi": { "skor": 85, "alasan": "Alasan singkat berdasarkan teks" },
                            "pengalaman": { "skor": 70, "alasan": "Alasan singkat berdasarkan teks" },
                            "kemampuan_bahasa": { "skor": 75, "alasan": "Alasan singkat berdasarkan teks" },
                            "teamwork": { "skor": 80, "alasan": "Alasan singkat berdasarkan teks" },
                            "komitmen": { "skor": 90, "alasan": "Alasan singkat berdasarkan teks" }
                        },
                        "probabilitas_total": 80.5,
                        "rekomendasi": "TERIMA / PERLU DIWAWANCARA / TOLAK",
                        "hard_skills": ["Skill A", "Skill B"],
                        "soft_skills": ["Skill C", "Skill D"]
                    }'
                ],
                [
                    'role' => 'user',
                    'content' => "Analisis data pendaftar berikut:\n\nDivisi yang dituju: " . $request->divisi . "\nTeks Motivasi: " . $request->motivasi
                ]
            ],
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal koneksi ke OpenRouter: ' . $response->body()
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['choices'])) {
            return response()->json([
                'success' => false,
                'message' => 'Respon AI tidak valid',
                'debug' => $data
            ], 500);
        }

        $rawContent = $data['choices'][0]['message']['content'];
        $cleanJson = str_replace(['```json', '```'], '', $rawContent);
        $aiContent = json_decode(trim($cleanJson), true);

        if (!is_array($aiContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses analisis AI. Format data tidak valid.'
            ], 500);
        }

        $pendaftar = PendaftarOsis::create([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'divisi' => $request->divisi,
            'motivasi' => $request->motivasi,
            'analisis_ai' => json_encode($aiContent),
            'hard_skills' => json_encode($aiContent['hard_skills'] ?? []),
            'soft_skills' => json_encode($aiContent['soft_skills'] ?? []),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dikirim!',
            'data' => $pendaftar
        ]);
    }

    public function approve($id)
    {
        if (!Auth::check() || !in_array(strtolower(Auth::user()->role), ['ketua', 'wakil'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Ketua atau Wakil yang memiliki otoritas ini.'
            ], 403);
        }

        $pendaftar = PendaftarOsis::findOrFail($id);

        \App\Services\ActivityLogger::log(
            'SETUJUI',
            'Pendaftaran OSIS',
            "Menyetujui pendaftaran calon pengurus OSIS: {$pendaftar->nama} (Kelas {$pendaftar->kelas} - Divisi {$pendaftar->divisi})",
            $pendaftar
        );

        $pendaftar->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Selamat, calon pengurus ' . $pendaftar->nama . ' berhasil di-approve!'
        ]);
    }

    public function reject($id)
    {
        if (!Auth::check() || !in_array(strtolower(Auth::user()->role), ['ketua', 'wakil'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Ketua atau Wakil yang memiliki otoritas ini.'
            ], 403);
        }

        $pendaftar = PendaftarOsis::findOrFail($id);

        \App\Services\ActivityLogger::log(
            'TOLAK',
            'Pendaftaran OSIS',
            "Menolak pendaftaran calon pengurus OSIS: {$pendaftar->nama} (Kelas {$pendaftar->kelas} - Divisi {$pendaftar->divisi})",
            $pendaftar
        );

        $pendaftar->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status calon pengurus ' . $pendaftar->nama . ' diubah menjadi ditolak.'
        ]);
    }

    public function exportApprovedPdf(Request $request)
    {
        $auth = Auth::user();
        if (!$auth || $auth->role !== 'sekretaris') {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh PDF ini.');
        }

        $query = PendaftarOsis::where('status', 'approved');

        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        $filterType = $request->input('filter_type');
        if ($filterType === 'divisi' && $request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        } elseif ($filterType === 'kelas' && $request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        } elseif ($filterType === 'tahun' && $request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($filterType !== 'tahun') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $approvedMembers = $query->get();

        return view('osis.export-approved-pdf', compact('approvedMembers'));
    }

    public function getWhatsAppLink()
    {
        $path = 'whatsapp_links.json';

        if (!Storage::exists($path)) {
            Storage::put($path, json_encode(['default' => 'https://chat.whatsapp.com/AbCdEfGhIjKlMnOpQrStUv']));
        }

        $links = json_decode(Storage::get($path), true);
        $link = $links['default'] ?? 'https://chat.whatsapp.com/AbCdEfGhIjKlMnOpQrStUv';

        if (!str_starts_with($link, 'https://chat.whatsapp.com/')) {
            $link = 'https://chat.whatsapp.com/AbCdEfGhIjKlMnOpQrStUv';
        }

        return $link;
    }

    public function updateWhatsAppLink(Request $request)
    {
        $request->validate([
            'whatsapp_link' => 'required|url'
        ]);

        $link = $request->whatsapp_link;

        if (!str_starts_with($link, 'https://chat.whatsapp.com/')) {
            return back()->withErrors(['whatsapp_link' => 'Link harus berupa undangan grup WhatsApp yang valid.']);
        }

        if (!Auth::check() || !in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas'])) {
            abort(403, 'Anda tidak memiliki izin untuk update link grup WhatsApp.');
        }

        $path = 'whatsapp_links.json';
        Storage::put($path, json_encode(['default' => $link]));

        return redirect()->back()->with('success', 'Link grup WhatsApp berhasil diperbarui!');
    }

    public function toggleRegistration(Request $request)
    {
        if (!Auth::check() || !in_array(strtolower(Auth::user()->role), ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Ketua atau Wakil yang memiliki otoritas ini.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:enable,disable'
        ]);

        $path = 'registration_status.json';
        Storage::put($path, json_encode(['status' => $request->status]));

        $statusLabel = $request->status === 'enable' ? 'Terbuka' : 'Ditutup';
        \App\Services\ActivityLogger::log(
            'STATUS',
            'Pendaftaran OSIS',
            "Mengubah status gerbang pendaftaran calon pengurus OSIS menjadi: {$statusLabel}",
            null,
            ['status' => $request->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran berhasil diubah menjadi: ' . ($request->status === 'enable' ? 'Terbuka' : 'Ditutup')
        ]);
    }
}
