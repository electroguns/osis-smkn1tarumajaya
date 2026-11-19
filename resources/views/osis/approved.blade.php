<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Anggota OSIS Diterima | Dashboard</title>
        @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    @php
        $authUser = Auth::user();
        $authRole = $authUser ? $authUser->role : null;

        $isPimpinan = in_array($authRole, ['ketua', 'wakil']);
        $isBph = in_array($authRole, ['sekretaris', 'bendahara']);
        $isDivisi = \Illuminate\Support\Str::contains($authRole ?? '', 'divisi');
    @endphp

    <div class="max-w-6xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
            <div class="space-y-2">
                <div class="flex flex-wrap gap-2">
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
                            class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider">
                            <span>Seleksi Selesai</span>
                        </div>

                        {{-- Tombol Info Hak Akses Halaman --}}
                        <button type="button" onclick="openModal('infoModalApproved')"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                            title="Klik untuk melihat informasi hak akses halaman ini">
                            <span class="w-4 h-4 rounded-full bg-rose-200 text-rose-700 group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                            <span>Info Hak Akses</span>
                        </button>

                        @if (Auth::check() && in_array(strtolower(Auth::user()->role), ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas']))
                            <div class="flex items-center pl-2 border-l border-slate-300 ml-2">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" id="togglePendaftaran" class="sr-only peer"
                                        {{ $isRegistrationOpen ? 'checked' : '' }}
                                        onchange="handleToggleRegistration(this)">
                                    <div
                                        class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500">
                                    </div>
                                    <span id="textToggle"
                                        class="ml-2 text-xs font-semibold {{ $isRegistrationOpen ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $isRegistrationOpen ? 'Pendaftaran: Terbuka' : 'Pendaftaran: Ditutup' }}
                                    </span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
                <h1 class="text-5xl font-black tracking-tight text-slate-900">
                    Anggota <span class="text-rose-600">Diterima</span>
                </h1>
                <p class="text-slate-500 text-lg max-w-xl leading-relaxed">
                    Daftar calon pengurus yang telah disetujui. Hubungi mereka secara langsung untuk koordinasi langkah
                    berikutnya.
                </p>
            </div>

            @if (Auth::user() &&
                    in_array(Auth::user()->role, [
                        'ketua',
                        'wakil',
                        'sekretaris',
                        'bendahara',
                        'ketua_divisi_humas',
                        'ketua_divisi_agama',
                        'ketua_divisi_artmedia',
                        'ketua_divisi_olahraga',
                    ]))
                <div class="shrink-0">
                    <a href="{{ route('admin.osis.history') }}"
                        class="group inline-flex items-center gap-2.5 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-rose-500/20 hover:shadow-xl hover:shadow-rose-500/30 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Daftar Calon Osis</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- ================= POPUP MODAL HAK AKSES ANGGOTA DITERIMA ================= --}}
        <div id="infoModalApproved"
            class="fixed inset-0 top-0 left-0 w-full h-full bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-100/90 relative max-h-[90vh] overflow-y-auto custom-time-scroll animate-in fade-in zoom-in duration-200">
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                Informasi Hak Akses Anggota Diterima
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ketentuan wewenang dan pengelolaan data calon anggota OSIS yang telah resmi disetujui.
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="closeModal('infoModalApproved')"
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
                                    <span>Memantau seluruh daftar calon anggota OSIS yang telah resmi disetujui (Approved).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Otoritas mengontrol status buka/tutup form pendaftaran OSIS.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Memfilter data pendaftar berdasarkan divisi, kelas, dan angkatan tahun.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Memantau tautan undangan WhatsApp calon pengurus terpilih.</span>
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
                                    <span><b>Sekretaris</b>: Wewenang eksklusif mengunduh dokumen <b>Export PDF Data Anggota Diterima</b>.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Otoritas membuka & menutup form pendaftaran pengurus baru (Sekretaris).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Meninjau biodata, kontak, dan penempatan divisi anggota terpilih.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Akses arsip riwayat data pendaftar dari tahun-tahun sebelumnya.</span>
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
                                    <span><b>Divisi Humas</b>: Mengelola komunikasi pendaftar, koordinasi calon anggota, & link grup WA.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span><b>Ketua Divisi Humas</b>: Wewenang kontrol toggle membuka & menutup form pendaftaran.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Divisi Non-Humas: Dibatasi aksesnya dari data internal kelulusan seleksi ini.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Terbatas dari mengunduh rekap PDF resmi (wewenang Sekretaris).</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="pt-2">
                    <button type="button" onclick="closeModal('infoModalApproved')"
                        class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition text-sm">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        <div class="mb-8 relative z-30" style="background: rgba(255,255,255,0.75); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.85); border-radius: 2.5rem; padding: 1.5rem 2rem; box-shadow: 0 20px 50px -12px rgba(0,0,0,0.04);">
            <form action="{{ route('admin.osis.approved') }}" method="GET" class="space-y-4">
                {{-- Row 1: Search + Filter Type + Submit --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    {{-- Cari Anggota (col-span-5) --}}
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Cari Anggota</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama anggota..."
                                class="w-full rounded-2xl px-4 py-3 pl-11 text-sm font-semibold text-slate-800 outline-none transition min-h-[46px]"
                                style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Kriteria Filter (col-span-4) --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kriteria Filter</label>
                        <select name="filter_type" id="filterTypeSelect"
                            class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition min-h-[46px] cursor-pointer"
                            style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 14px rgba(0,0,0,0.02);"
                            onchange="toggleFilterFields(this.value)">
                            <option value="">Semua (Tahun Ini)</option>
                            <option value="divisi" {{ request('filter_type') == 'divisi' ? 'selected' : '' }}>Divisi</option>
                            <option value="kelas" {{ request('filter_type') == 'kelas' ? 'selected' : '' }}>Kelas</option>
                            <option value="tahun" {{ request('filter_type') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                        </select>
                    </div>

                    {{-- Submit Button (col-span-3) --}}
                    <div class="md:col-span-3">
                        <button type="submit" aria-label="Cari dan Filter Data"
                            class="w-full h-[46px] rounded-2xl bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg transition-all"
                            style="box-shadow: 0 8px 25px rgba(225,29,72,0.25);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>

                {{-- Row 2: Optional value field (shown by JS) --}}
                <div class="hidden" id="valueLabelContainer">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-5">
                            <label id="valueLabel" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nilai Opsi</label>

                            <select name="divisi" id="divisiField"
                                class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition min-h-[46px] cursor-pointer hidden"
                                style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisiList as $divisi)
                                    <option value="{{ $divisi }}" {{ request('divisi') == $divisi ? 'selected' : '' }}>{{ $divisi }}</option>
                                @endforeach
                            </select>

                            <select name="kelas" id="kelasField"
                                class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition min-h-[46px] cursor-pointer hidden"
                                style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>

                            <select name="tahun" id="tahunField"
                                class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition min-h-[46px] cursor-pointer hidden"
                                style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="mb-6 flex justify-between items-center gap-4">
            <div>
                @if (request()->anyFilled(['q', 'filter_type', 'divisi', 'kelas', 'tahun']))
                    <a href="{{ route('admin.osis.approved') }}"
                        class="inline-flex items-center text-xs text-red-600 font-medium bg-red-50 hover:bg-red-100 px-3 py-2 rounded-xl h-[38px] transition-colors border border-red-200 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Hapus Filter
                    </a>
                @endif
            </div>

            @if (Auth::user() && in_array(Auth::user()->role, ['sekretaris']))
                <a href="{{ route('admin.osis.approved.exportPdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm rounded-xl hover:bg-red-700 font-semibold h-[38px] transition-all duration-200 shadow-[0_4px_12px_rgba(220,38,38,0.15)] hover:shadow-[0_4px_20px_rgba(220,38,38,0.25)] hover:-translate-y-0.5 active:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export PDF</span>
                </a>
            @endif
        </div>

        {{-- Table Card Panel --}}
        <div
            class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/70 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Data Anggota</th>
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Kelas</th>
                            <th class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Divisi</th>
                            @if (Auth::user() && in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas']))
                                <th
                                    class="px-8 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 text-right">
                                    Hubungi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($approvedMembers as $member)
                            <tr class="group hover:bg-rose-50/30 transition-colors duration-200">
                                {{-- Kolom Nama & Avatar Inisial --}}
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-rose-100 uppercase shrink-0">
                                            {{ strtoupper(substr($member->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 capitalize">{{ $member->nama }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-medium">Status: Lolos Seleksi
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom Kelas --}}
                                <td class="px-8 py-5 text-sm font-semibold text-slate-600">
                                    <span
                                        class="px-3 py-1 rounded-xl bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200/40">
                                        {{ $member->kelas }}
                                    </span>
                                </td>

                                {{-- Kolom Divisi --}}
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-1.5 text-sm font-bold text-rose-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        {{ $member->divisi }}
                                    </span>
                                </td>

                                {{-- Kolom Aksi WhatsApp --}}
                                @if (Auth::user() && in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas']))
                                    <td class="px-8 py-5 text-right">
                                        <a href="https://wa.me/62{{ $member->no_telp }}?text=Selamat+{{ urlencode($member->nama) }}!+Anda+telah+diterima+sebagai+anggota+OSIS+di+divisi+{{ urlencode($member->divisi) }}."
                                            target="_blank"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all duration-200 transform active:scale-95 shadow-md shadow-emerald-600/10 hover:shadow-lg hover:shadow-emerald-600/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span>Kirim WA</span>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-semibold text-lg">Belum ada anggota yang
                                            disetujui (Approved).</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-8 text-center text-slate-400 text-sm italic">
            Total {{ $approvedMembers->total() }} Pengurus Resmi Terdata
        </p>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $approvedMembers->links() }}
        </div>
    </div>

    <script src="{{ asset('js/infomodal.min.js') }}"></script>
    <script>
        function toggleFilterFields(value) {
            const container = document.getElementById('valueLabelContainer');
            const label = document.getElementById('valueLabel');
            const fDivisi = document.getElementById('divisiField');
            const fKelas = document.getElementById('kelasField');
            const fTahun = document.getElementById('tahunField');

            fDivisi.classList.add('hidden');
            fKelas.classList.add('hidden');
            fTahun.classList.add('hidden');

            if (value === 'divisi') {
                container.classList.remove('hidden');
                fDivisi.classList.remove('hidden');
                label.innerText = "Pilih Divisi";
            } else if (value === 'kelas') {
                container.classList.remove('hidden');
                fKelas.classList.remove('hidden');
                label.innerText = "Pilih Kelas";
            } else if (value === 'tahun') {
                container.classList.remove('hidden');
                fTahun.classList.remove('hidden');
                label.innerText = "Pilih Tahun";
            } else {
                container.classList.add('hidden');
            }

            if (window.syncCustomUI) {
                window.syncCustomUI();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const currentFilter = document.getElementById('filterTypeSelect').value;
            toggleFilterFields(currentFilter);
        });

        function handleToggleRegistration(checkbox) {
            const status = checkbox.checked ? 'enable' : 'disable';
            const textToggle = document.getElementById('textToggle');

            textToggle.innerText = 'Memproses...';
            textToggle.className = 'ml-2 text-xs font-semibold text-slate-500';

            fetch("{{ route('admin.osis.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (status === 'enable') {
                            textToggle.innerText = 'Pendaftaran: Terbuka';
                            textToggle.className = 'ml-2 text-xs font-semibold text-emerald-600';
                        } else {
                            textToggle.innerText = 'Pendaftaran: Ditutup';
                            textToggle.className = 'ml-2 text-xs font-semibold text-rose-600';
                        }
                    } else {
                        alert('Gagal mengubah status pendaftaran: ' + data.message);
                        checkbox.checked = !checkbox.checked;
                        resetToggleText(checkbox.checked);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi sistem.');
                    checkbox.checked = !checkbox.checked;
                    resetToggleText(checkbox.checked);
                });
        }

        function resetToggleText(isChecked) {
            const textToggle = document.getElementById('textToggle');
            if (isChecked) {
                textToggle.innerText = 'Pendaftaran: Terbuka';
                textToggle.className = 'ml-2 text-xs font-semibold text-emerald-600';
            } else {
                textToggle.innerText = 'Pendaftaran: Ditutup';
                textToggle.className = 'ml-2 text-xs font-semibold text-rose-600';
            }
        }
    </script>
    <script src="{{ asset('js/custom-ui.js') }}"></script>
</body>

</html>
