<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Data Suara Guest User OSIS SMKN 1 Tarumajaya. Lihat semua suara, saran, dan aspirasi yang telah disampaikan oleh pengunjung melalui formulir suara kami. Data ini membantu kami memahami kebutuhan dan harapan komunitas sekolah untuk terus meningkatkan layanan dan program kerja OSIS." />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Aspirasi Siswa - OSIS SMKN 1 Tarumajaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .liquid-glass-card {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border: 1px solid rgba(255, 255, 255, 0.85) !important;
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(255, 255, 255, 0.5) inset !important;
        }

        .liquid-glass-subcard {
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(14px) !important;
            -webkit-backdrop-filter: blur(14px) !important;
            border: 1px solid rgba(255, 255, 255, 0.75) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.03) !important;
        }

        .liquid-glass-input {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s ease;
        }

        .liquid-glass-input:focus {
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: #f43f5e !important;
            box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.12) !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased text-slate-800">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    @php
        $authUser = Auth::user();
        $authRole = $authUser ? $authUser->role : null;

        $isPimpinan = in_array($authRole, ['ketua', 'wakil']);
        $isBph = in_array($authRole, ['sekretaris', 'bendahara']);
        $isDivisi = \Illuminate\Support\Str::contains($authRole ?? '', 'divisi');

        $user = $authUser;
        $canUpdateStatus = $user && in_array($user->role, ['ketua_divisi_humas']);
        $canExport = $user && in_array($user->role, ['sekretaris']);
        $canAnalytics = $user && in_array($user->role, ['ketua', 'wakil']);
    @endphp

    <div class="max-w-6xl mx-auto px-4 md:px-6 py-8 md:py-12 pt-20 md:pt-12">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8 md:mb-12">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <a href="{{ url('/') }}" aria-label="Landing Page"
                        class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider hover:bg-rose-500/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" fill="none"
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
                    <button type="button" onclick="openModal('infoModalSuara')"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                        title="Klik untuk melihat informasi hak akses halaman ini">
                        <span class="w-4 h-4 rounded-full bg-rose-200 text-rose-700 group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                        <span>Info Hak Akses</span>
                    </button>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
                    Aspirasi <span class="text-rose-600">Siswa</span>
                </h1>
                <p class="text-slate-500 text-base md:text-lg max-w-md leading-relaxed">
                    Lihat semua suara, saran, dan aspirasi yang telah disampaikan oleh pengunjung.
                </p>
            </div>
        </div>

        {{-- ================= POPUP MODAL HAK AKSES ASPIRASI SISWA ================= --}}
        <div id="infoModalSuara"
            class="fixed inset-0 top-0 left-0 w-full h-full bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-100/90 relative max-h-[90vh] overflow-y-auto custom-time-scroll animate-in fade-in zoom-in duration-200">
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                Informasi Hak Akses Aspirasi Siswa
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ketentuan wewenang dan alur tindak lanjut aspirasi khusus pada halaman Aspirasi Siswa ini.
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="closeModal('infoModalSuara')"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                                    <span>Memantau seluruh kritik, saran, & aspirasi yang disampaikan siswa.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Meninjau ringkasan statistik (Total, Pending, On Progress, Resolved).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Mengawasi responsivitas tindak lanjut penanganan keluhan siswa.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Mode Pengawasan: Tidak mengubah status tindak lanjut langsung (wewenang Kadiv Humas).</span>
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
                                    <span><b>Sekretaris</b>: Wewenang eksklusif mengunduh dokumen <b>Export PDF Laporan Aspirasi Siswa</b>.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Meninjau seluruh aspirasi siswa dengan filter kategori & rentang tanggal.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Memantau perkembangan tindak lanjut masalah siswa untuk arsip sekolah.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Tidak berwenang memperbarui status tindak lanjut aspirasi secara sepihak.</span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
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
                                    <span><b>Ketua Divisi Humas</b>: Wewenang eksekutif mengubah status tindak lanjut (Pending, On Progress, Resolved).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span><b>Anggota Humas</b>: Meneliti & mendiskusikan aspirasi siswa untuk evaluasi program OSIS.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Aspirasi yang sudah <i>Resolved</i> otomatis dikunci dan tidak dapat diubah kembali.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Divisi Non-Humas: Dibatasi aksesnya dari pengelolaan data internal aspirasi ini.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="pt-2">
                    <button type="button" onclick="closeModal('infoModalSuara')"
                        class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition text-sm">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div
                class="mb-8 flex items-center gap-4 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-emerald-800 backdrop-blur-md">
                <div class="bg-emerald-500 text-white p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-semibold text-sm md:text-base">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Flash Message Error --}}
        @if (session('error'))
            <div
                class="mb-8 flex items-center gap-4 rounded-3xl bg-rose-500/10 border border-rose-500/20 p-4 text-rose-800 backdrop-blur-md">
                <div class="bg-rose-500 text-white p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-semibold text-sm md:text-base">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Analytics Cards --}}
        @if ($canAnalytics)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 md:mb-10">

                <div
                    class="liquid-glass-card relative overflow-hidden rounded-3xl p-6 transition-all duration-300 hover:shadow-[0_20px_40px_rgba(244,63,94,0.08)] hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Aspirasi</h4>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $total }}</p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-500/10 text-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 11h-6a2 2 0 01-2-2V3m6 4h1a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2h1" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="liquid-glass-card relative overflow-hidden rounded-3xl p-6 transition-all duration-300 hover:shadow-[0_20px_40px_rgba(245,158,11,0.08)] hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">On Progress</h4>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $onProgress }}</p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/10 text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="liquid-glass-card relative overflow-hidden rounded-3xl p-6 transition-all duration-300 hover:shadow-[0_20px_40px_rgba(16,185,129,0.08)] hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Resolved</h4>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $resolved }}</p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        {{-- Unified Liquid Glass Filter & Search Card --}}
        <div class="liquid-glass-card relative z-30 rounded-[2.5rem] p-6 md:p-8 mb-8">
            <form action="{{ route('suara.index') }}" method="GET" class="space-y-5">
                {{-- Card Header: Title & Action Badges --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100/80">
                    <div class="flex items-center gap-2.5">
                        <div class="h-8 w-8 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-800">Filter & Pencarian Aspirasi</h3>
                            <p class="text-xs text-slate-400">Temukan aspirasi berdasarkan kata kunci, kategori, status, dan tanggal</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        {{-- Tombol Reset Filter jika ada filter aktif --}}
                        @if (request()->anyFilled(['search', 'kategori', 'status', 'start_date', 'end_date']))
                            <a href="{{ route('suara.index') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition border border-rose-200/60 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Reset Filter</span>
                            </a>
                        @endif

                        @if ($canExport)
                            <a href="{{ route('suara.export.pdf', request()->query()) }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-bold text-white shadow-md shadow-rose-200 transition-all hover:-translate-y-0.5 active:translate-y-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Export PDF</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Row 1: Search Keyword (col-span-6), Kategori (col-span-3), Status (col-span-3) --}}
                <div class="relative z-20 grid grid-cols-1 md:grid-cols-12 gap-4">
                    {{-- Search Input (col-span-6) --}}
                    <div class="md:col-span-6">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Cari Kata Kunci</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') ?? request('q') }}" placeholder="Cari isi suara atau pesan..."
                                class="liquid-glass-input w-full rounded-2xl px-4 py-3 pl-11 text-sm font-semibold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 min-h-[46px]">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Kategori Select (col-span-3) --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kategori</label>
                        <select name="kategori"
                            class="liquid-glass-input w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 min-h-[46px]">
                            <option value="">Semua Kategori</option>
                            <option value="apresiasi" {{ request('kategori') == 'apresiasi' ? 'selected' : '' }}>Apresiasi</option>
                            <option value="kritik" {{ request('kategori') == 'kritik' ? 'selected' : '' }}>Kritik / Saran</option>
                        </select>
                    </div>

                    {{-- Status Select (col-span-3) --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Status</label>
                        <select name="status"
                            class="liquid-glass-input w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 min-h-[46px]">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on_progress" {{ request('status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2: Dari Tanggal (col-span-4), Sampai Tanggal (col-span-4), Tombol Terapkan (col-span-4) --}}
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    {{-- Dari Tanggal (col-span-4) --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Pilih Tanggal Mulai..."
                            class="liquid-glass-input w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 min-h-[46px]">
                    </div>

                    {{-- Sampai Tanggal (col-span-4) --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="Pilih Tanggal Selesai..."
                            class="liquid-glass-input w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 min-h-[46px]">
                    </div>

                    {{-- Tombol Terapkan Filter (col-span-4) --}}
                    <div class="md:col-span-4">
                        <button type="submit" aria-label="Cari dan Filter Data"
                            class="w-full h-[46px] rounded-2xl bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-rose-500/25 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Container with Liquid Glass --}}
        <div class="liquid-glass-card relative z-10 overflow-hidden rounded-[2.5rem] border border-white/80 shadow-[0_20px_50px_rgba(0,0,0,0.04)] mb-8">
            {{-- Desktop View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100/80 bg-slate-50/50">
                            <th class="px-8 py-5 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 w-20">ID</th>
                            <th class="px-8 py-5 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 w-44">Kategori</th>
                            <th class="px-8 py-5 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">Saran / Pesan</th>
                            <th class="px-8 py-5 text-center text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 w-40">Tanggal</th>
                            <th class="px-8 py-5 text-center text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 w-44">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100/70">
                        @forelse ($suaras as $suara)
                            <tr class="group hover:bg-rose-50/40 transition-colors duration-200">
                                <td class="px-8 py-5 text-sm font-bold text-slate-400">
                                    #{{ $suara->id }}
                                </td>

                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ strtolower($suara->kategori) === 'apresiasi' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200/50' : 'bg-amber-50 text-amber-600 border border-amber-200/50' }}">
                                        {{ $suara->kategori }}
                                    </span>
                                </td>

                                <td class="px-8 py-5 text-sm text-slate-700 font-medium leading-relaxed">
                                    @if (strtolower($suara->kategori) === 'apresiasi')
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex items-center gap-1 text-amber-400" title="Rating: {{ $suara->rating }}">
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20"
                                                        fill="{{ $i <= $suara->rating ? 'currentColor' : '#E2E8F0' }}">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span class="text-xs text-slate-400 font-bold ml-1.5">({{ $suara->rating }}/10)</span>
                                            </div>
                                            <p class="text-slate-600 italic">"{{ $suara->saran }}"</p>
                                        </div>
                                    @else
                                        <p class="text-slate-700 leading-relaxed">{{ $suara->saran }}</p>
                                    @endif
                                </td>

                                <td class="px-8 py-5 text-sm text-slate-500 text-center font-medium">
                                    {{ $suara->created_at->format('d M Y') }}
                                </td>

                                <td class="px-8 py-5 text-center">
                                    @if ($canUpdateStatus)
                                        <form action="{{ route('suara.status', $suara->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="rounded-xl border border-slate-200 bg-white/90 px-3 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-rose-500 shadow-sm cursor-pointer">
                                                <option value="pending" {{ $suara->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="on_progress" {{ $suara->status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                                <option value="resolved" {{ $suara->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                            {{ $suara->status == 'pending'
                                                ? 'bg-amber-100/70 text-amber-700 border border-amber-200/50'
                                                : ($suara->status == 'on_progress'
                                                    ? 'bg-sky-100/70 text-sky-700 border border-sky-200/50'
                                                    : 'bg-emerald-100/70 text-emerald-700 border border-emerald-200/50') }}">
                                            {{ ucwords(str_replace('_', ' ', $suara->status)) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="h-16 w-16 bg-rose-500/10 rounded-full flex items-center justify-center text-rose-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2 2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-600 font-bold text-base">Belum Ada Data Aspirasi</p>
                                        <p class="text-slate-400 text-sm max-w-sm">Tidak ada suara siswa yang cocok dengan filter pencarian yang Anda pilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile View --}}
            <div class="md:hidden p-5 space-y-4">
                @forelse ($suaras as $suara)
                    <div class="liquid-glass-subcard rounded-2xl p-5 border border-white/80 shadow-sm flex flex-col gap-3">
                        {{-- Header Card (ID dan Kategori) --}}
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">#{{ $suara->id }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ strtolower($suara->kategori) === 'apresiasi' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $suara->kategori }}
                            </span>
                        </div>

                        {{-- Body Card (Saran / Pesan & Rating Bintang) --}}
                        <div class="text-sm text-slate-700 font-medium leading-relaxed">
                            @if (strtolower($suara->kategori) === 'apresiasi')
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex flex-wrap items-center gap-0.5 text-amber-400" title="Rating: {{ $suara->rating }}">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                viewBox="0 0 20 20"
                                                fill="{{ $i <= $suara->rating ? 'currentColor' : '#E2E8F0' }}">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-slate-400 font-bold ml-1">({{ $suara->rating }}/10)</span>
                                    </div>
                                    <p class="text-slate-600 italic">"{{ $suara->saran }}"</p>
                                </div>
                            @else
                                <p class="text-slate-700 leading-relaxed">{{ $suara->saran }}</p>
                            @endif
                        </div>

                        {{-- Footer Card (Tanggal & Status) --}}
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-medium">{{ $suara->created_at->format('d M Y') }}</span>
                            @if ($canUpdateStatus)
                                <form action="{{ route('suara.status', $suara->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-rose-500 shadow-sm cursor-pointer">
                                        <option value="pending" {{ $suara->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="on_progress" {{ $suara->status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                        <option value="resolved" {{ $suara->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </form>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $suara->status == 'pending'
                                        ? 'bg-amber-100/80 text-amber-700'
                                        : ($suara->status == 'on_progress'
                                            ? 'bg-sky-100/80 text-sky-700'
                                            : 'bg-emerald-100/80 text-emerald-700') }}">
                                    {{ ucwords(str_replace('_', ' ', $suara->status)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 font-medium">
                        Belum ada data aspirasi yang cocok.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Footer Total Data & Pagination --}}
        <div class="mt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <p class="text-slate-400 text-sm italic text-center md:text-left">
                Total <span class="font-bold text-slate-600">{{ $suaras->total() }}</span> Suara Tersimpan
            </p>
            <div>
                {{ $suaras->links() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/infomodal.min.js') }}"></script>
    <script src="{{ asset('js/custom-ui.js') }}"></script>
</body>

</html>