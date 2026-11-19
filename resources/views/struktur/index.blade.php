<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Portal resmi OSIS SMKN 1 Tarumajaya. Temukan berita terbaru, program kerja, dan informasi penting lainnya seputar organisasi siswa kami. Bergabunglah untuk menjadi bagian dari perubahan positif di sekolah!" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Struktur Pengurus</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .liquid-glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.04);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    @php
        $authUser = Auth::user();
        $authRole = $authUser ? $authUser->role : null;

        $isPimpinan = in_array($authRole, ['ketua', 'wakil']);
        $isBph = in_array($authRole, ['sekretaris', 'bendahara']);
        $isDivisi = \Illuminate\Support\Str::contains($authRole ?? '', 'divisi');

        $roleLabels = [
            'ketua' => 'Ketua OSIS',
            'wakil' => 'Wakil Ketua OSIS',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'ketua_divisi_humas' => 'Ketua Divisi Humas',
            'ketua_divisi_agama' => 'Ketua Divisi Agama',
            'ketua_divisi_olahraga' => 'Ketua Divisi Olahraga',
            'ketua_divisi_artmedia' => 'Ketua Divisi Art & Media',
            'anggota_divisi_humas' => 'Anggota Divisi Humas',
            'anggota_divisi_agama' => 'Anggota Divisi Agama',
            'anggota_divisi_olahraga' => 'Anggota Divisi Olahraga',
            'anggota_divisi_artmedia' => 'Anggota Divisi Art & Media',
        ];

        $currentRoleLabel = $roleLabels[$authRole] ?? ucwords(str_replace('_', ' ', $authRole ?? 'Tamu'));
    @endphp

    <div class="max-w-6xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- ================= HEADER SECTION ================= --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <a href="{{ url('/') }}" aria-label="Landing Page"
                        class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider hover:bg-rose-500/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>

                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider">
                        <span>Admin Panel</span>
                    </div>

                    {{-- Tombol Info Hak Akses Halaman --}}
                    <button type="button" onclick="openModal('infoModalPengurus')"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                        title="Klik untuk melihat informasi hak akses halaman ini">
                        <span class="w-4 h-4 rounded-full bg-rose-200 text-rose-700 group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                        <span>Info Hak Akses</span>
                    </button>
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900">
                    Struktur <span class="text-rose-600">Pengurus</span>
                </h1>
                <p class="text-slate-500 text-base sm:text-lg max-w-xl leading-relaxed">
                    Kelola data anggota, jabatan, dan akses sistem dalam satu panel kendali.
                </p>
            </div>

            @if ($authUser && in_array($authUser->role, ['ketua', 'wakil', 'sekretaris']))
                <a href="{{ route('struktur.create') }}" aria-label="Tambah Struktur Pengurus"
                    class="group relative w-full md:w-auto inline-flex items-center justify-center gap-3 px-6 md:px-8 py-4 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white text-sm md:text-base font-bold rounded-2xl md:rounded-[20px] transition-all duration-300 active:scale-[0.98] shadow-lg shadow-rose-500/25 shrink-0">
                    <span class="relative z-10">Tambah Pengurus</span>
                    <div class="relative z-10 h-7 w-7 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </a>
            @endif
        </div>

        {{-- ================= POPUP MODAL HAK AKSES HALAMAN INI ================= --}}
        <div id="infoModalPengurus"
            class="fixed inset-0 top-0 left-0 w-full h-full bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-100/90 relative max-h-[90vh] overflow-y-auto custom-time-scroll animate-in fade-in zoom-in duration-200">
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                Informasi Hak Akses Halaman Ini
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ketentuan wewenang dan batasan akses khusus pada halaman Struktur Pengurus.
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="closeModal('infoModalPengurus')"
                        class="h-9 w-9 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition flex items-center justify-center shrink-0"
                        title="Tutup Modal">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- CARD KESAMPING DI DALAM MODAL (3 KOLOM) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">

                    {{-- CARD 1: KETUA & WAKIL --}}
                    <div class="rounded-2xl p-5 transition-all flex flex-col justify-between {{ $isPimpinan ? 'border-2 border-rose-500 bg-gradient-to-br from-white via-rose-50/70 to-white shadow-lg shadow-rose-500/15' : 'bg-slate-50/80 border border-slate-200/80' }}">
                        <div>
                            {{-- Top Bar: Icon & Badge --}}
                            <div class="flex items-center justify-between mb-3 pb-2.5 border-b {{ $isPimpinan ? 'border-rose-200/60' : 'border-slate-200/60' }}">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $isPimpinan ? 'bg-rose-500 text-white shadow-md' : 'bg-slate-200 text-slate-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>

                                @if ($isPimpinan)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-600 text-white text-[10px] font-extrabold uppercase shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Role Anda
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200/80 text-slate-600 text-[10px] font-bold"><svg class="h-3 w-3 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>Peran Lain</span>
                                @endif
                            </div>

                            {{-- Nama Role --}}
                            <h3 class="font-extrabold text-base text-slate-900 leading-tight mb-3">
                                Ketua & Wakil
                            </h3>

                            <ul class="space-y-2 text-xs leading-relaxed text-slate-600 pt-2 border-t {{ $isPimpinan ? 'border-rose-200/60' : 'border-slate-200/60' }}">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Melihat seluruh data pengurus semua divisi.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Menambah dan mendaftarkan pengurus baru.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Mengedit data profil & jabatan seluruh pengurus.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Menghapus data pengurus dari sistem.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Melihat & mencetak seluruh ID Card pengurus.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- CARD 2: SEKRETARIS & BENDAHARA --}}
                    <div class="rounded-2xl p-5 transition-all flex flex-col justify-between {{ $isBph ? 'border-2 border-rose-500 bg-gradient-to-br from-white via-rose-50/70 to-white shadow-lg shadow-rose-500/15' : 'bg-slate-50/80 border border-slate-200/80' }}">
                        <div>
                            {{-- Top Bar: Icon & Badge --}}
                            <div class="flex items-center justify-between mb-3 pb-2.5 border-b {{ $isBph ? 'border-rose-200/60' : 'border-slate-200/60' }}">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $isBph ? 'bg-rose-500 text-white shadow-md' : 'bg-slate-200 text-slate-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                @if ($isBph)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-600 text-white text-[10px] font-extrabold uppercase shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Role Anda
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200/80 text-slate-600 text-[10px] font-bold"><svg class="h-3 w-3 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>Peran Lain</span>
                                @endif
                            </div>

                            {{-- Nama Role --}}
                            <h3 class="font-extrabold text-base text-slate-900 leading-tight mb-3">
                                Sekretaris & Bendahara
                            </h3>

                            <ul class="space-y-2 text-xs leading-relaxed text-slate-600 pt-2 border-t {{ $isBph ? 'border-rose-200/60' : 'border-slate-200/60' }}">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Melihat data profil mandiri & data Pimpinan (Ketua/Wakil).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Hanya berhak mengubah data profil mereka sendiri.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Melihat ID Card profil diri sendiri & Pimpinan.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Tidak berhak mengubah atau menghapus data pengurus lain.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- CARD 3: KADIV & ANGGOTA (DIVISI) --}}
                    <div class="rounded-2xl p-5 transition-all flex flex-col justify-between {{ $isDivisi ? 'border-2 border-rose-500 bg-gradient-to-br from-white via-rose-50/70 to-white shadow-lg shadow-rose-500/15' : 'bg-slate-50/80 border border-slate-200/80' }}">
                        <div>
                            {{-- Top Bar: Icon & Badge --}}
                            <div class="flex items-center justify-between mb-3 pb-2.5 border-b {{ $isDivisi ? 'border-rose-200/60' : 'border-slate-200/60' }}">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $isDivisi ? 'bg-rose-500 text-white shadow-md' : 'bg-slate-200 text-slate-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>

                                @if ($isDivisi)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-600 text-white text-[10px] font-extrabold uppercase shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Role Anda
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200/80 text-slate-600 text-[10px] font-bold"><svg class="h-3 w-3 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>Peran Lain</span>
                                @endif
                            </div>

                            {{-- Nama Role --}}
                            <h3 class="font-extrabold text-base text-slate-900 leading-tight mb-3">
                                Kadiv & Anggota
                            </h3>

                            <ul class="space-y-2 text-xs leading-relaxed text-slate-600 pt-2 border-t {{ $isDivisi ? 'border-rose-200/60' : 'border-slate-200/60' }}">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Hanya dapat melihat data pengurus di divisi masing-masing.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Hanya berhak mengubah data profil mereka sendiri.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Melihat ID Card rekan pengurus dalam divisi yang sama.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Tidak berhak mengakses data pengurus di luar divisinya.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="pt-2">
                    <button type="button" onclick="closeModal('infoModalPengurus')"
                        class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition text-sm">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        {{-- ================= SEARCH FORM ================= --}}
        <x-search-form :action="route('struktur.index')" placeholder="Cari nama atau email pengurus..." />

        @if (session('success'))
            <div
                class="mb-8 flex items-center gap-4 rounded-3xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-700 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-500 text-white p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ================= TABEL DATA PENGURUS ================= --}}
        <div class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/75 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.05)] backdrop-blur-xl mb-8">
            
            {{-- DESKTOP TABLE VIEW --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-white/40">
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Data Pengurus
                            </th>
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Email
                            </th>
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 text-center">
                                Kelas
                            </th>
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Jabatan
                            </th>
                            <th class="px-8 py-6 text-center text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50">
                        @forelse ($users as $item)
                            <tr class="hover:bg-rose-50/30 transition">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        @if ($item->foto)
                                            <img src="{{ asset('storage/struktural/' . $item->foto) }}"
                                                alt="{{ $item->name }}"
                                                class="h-11 w-11 rounded-full object-cover ring-2 ring-white shadow-sm">
                                        @else
                                            <div
                                                class="h-11 w-11 rounded-full bg-gradient-to-br from-rose-500 to-[#571319] text-white flex items-center justify-center font-bold ring-2 ring-white shadow-sm">
                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <h3 class="font-bold text-slate-800">
                                                {{ $item->name }}
                                            </h3>
                                            <p class="text-xs text-slate-400">
                                                Terdaftar: {{ $item->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-sm text-slate-500">
                                    {{ $item->email }}
                                </td>

                                <td class="text-center py-5">
                                    <span class="px-3 py-1 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700">
                                        {{ $item->kelas }}
                                    </span>
                                </td>

                                <td class="px-8 py-5 text-sm font-semibold text-rose-600">
                                    {{ ucwords(str_replace('_', ' ', $item->role)) }}
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- ID Card --}}
                                        <a href="{{ route('struktur.show', $item->id) }}" title="Lihat ID Card"
                                            class="p-2 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a3 3 0 100-6 3 3 0 000 6z" />
                                            </svg>
                                        </a>

                                        @if ($item->canBeManagedBy(Auth::user()))
                                            {{-- Edit --}}
                                            <a href="{{ route('struktur.edit', $item->id) }}" title="Edit Pengurus"
                                                class="p-2 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Hapus --}}
                                            <form action="{{ route('struktur.destroy', $item->id) }}" method="POST" class="inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Pengurus"
                                                    class="p-2 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    Belum ada data pengurus yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW --}}
            <div class="md:hidden p-4 space-y-4">
                @forelse ($users as $item)
                    <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-sm">
                        <div class="flex items-start gap-4">
                            @if ($item->foto)
                                <img src="{{ asset('storage/struktural/' . $item->foto) }}"
                                    class="h-12 w-12 rounded-full object-cover ring-2 ring-white shadow-sm">
                            @else
                                <div
                                    class="h-12 w-12 rounded-full bg-gradient-to-br from-rose-500 to-[#571319] text-white flex items-center justify-center font-bold text-base ring-2 ring-white shadow-sm">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-base text-slate-800 leading-tight">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-xs text-slate-400 truncate mt-0.5">
                                    {{ $item->email }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600">
                                        Kelas {{ $item->kelas }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-[11px] font-bold text-rose-700">
                                        {{ ucwords(str_replace('_', ' ', $item->role)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <a href="{{ route('struktur.show', $item->id) }}" title="Lihat ID Card"
                                class="p-2 rounded-xl bg-emerald-500 text-white active:scale-95 transition shadow-sm text-xs font-bold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a3 3 0 100-6 3 3 0 000 6z" />
                                </svg>
                                <span>ID Card</span>
                            </a>

                            @if ($item->canBeManagedBy(Auth::user()))
                                <a href="{{ route('struktur.edit', $item->id) }}" title="Edit Pengurus"
                                    class="p-2 rounded-xl bg-sky-500 text-white active:scale-95 transition shadow-sm text-xs font-bold flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('struktur.destroy', $item->id) }}" method="POST" class="inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Pengurus"
                                        class="p-2 rounded-xl bg-rose-600 text-white active:scale-95 transition shadow-sm text-xs font-bold flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-400 text-sm">
                        Belum ada data pengurus yang cocok.
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Footer Total Data --}}
        <p class="mt-8 text-center text-rose-500 font-semibold text-sm">
            Total {{ $users->total() }} Pengurus Terdaftar
        </p>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>

    <script src="{{ asset('js/struktur.min.js') }}"></script>
    <script src="{{ asset('js/infomodal.min.js') }}"></script>
</body>

</html>
