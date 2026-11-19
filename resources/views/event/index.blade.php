<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event Kegiatan OSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
                    <button type="button" onclick="openModal('infoModalEvent')"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                        title="Klik untuk melihat informasi hak akses halaman ini">
                        <span class="w-4 h-4 rounded-full bg-rose-200 text-rose-700 group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                        <span>Info Hak Akses</span>
                    </button>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
                    Absensi <span class="text-rose-600">Event</span>
                </h1>
                <p class="text-slate-500 text-base md:text-lg max-w-md leading-relaxed">
                    Kelola kegiatan, jalankan scanner, dan pantau rekap kehadiran pengurus OSIS.
                </p>
            </div>

            @if (Auth::user() && Auth::user()->role === 'sekretaris')
                <a href="{{ route('event.create') }}" aria-label="Tambah Event Baru"
                    class="group relative w-full md:w-auto inline-flex items-center justify-center gap-3 px-6 py-4 bg-rose-600 text-white text-base font-bold rounded-2xl transition-all duration-300 hover:bg-rose-700 active:scale-[0.98] hover:shadow-[0_20px_40px_-15px_rgba(2,132,199,0.4)] overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white/10 via-white/0 to-white/10 transition-opacity duration-500">
                    </div>
                    <span class="relative z-10">Tambah Event Baru</span>
                    <div
                        class="relative z-10 h-7 w-7 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </a>
            @endif
        </div>

        {{-- ================= POPUP MODAL HAK AKSES ABSENSI EVENT ================= --}}
        <div id="infoModalEvent"
            class="fixed inset-0 top-0 left-0 w-full h-full bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-100/90 relative max-h-[90vh] overflow-y-auto custom-time-scroll animate-in fade-in zoom-in duration-200">
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                Informasi Hak Akses Absensi Event
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ketentuan wewenang dan alur presensi kegiatan khusus pada halaman Absensi Event ini.
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="closeModal('infoModalEvent')"
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
                                    <span>Memantau seluruh agenda kegiatan & event yang diadakan OSIS.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Meninjau rekapitulasi kehadiran (absensi) pengurus OSIS pada tiap event.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Akses mengoperasikan Scanner QR / Barcode absensi saat hari acara.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Mode Pengawasan: Tidak berwenang membuat atau menghapus kegiatan (wewenang Sekretaris).</span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
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
                                    <span><b>Sekretaris</b>: Wewenang penuh membuat event baru, batas waktu absen, & koordinat lokasi.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Menjalankan Scanner QR / Barcode presensi kehadiran pengurus di lokasi kegiatan.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Mengirim broadcast email reminder jadwal acara ke seluruh pengurus OSIS.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Menghapus kegiatan serta meninjau seluruh data rekap kehadiran peserta.</span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
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
                                    <span>Melihat daftar jadwal agenda kegiatan OSIS dan rekapitulasi kehadiran.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span><b>Divisi Humas</b>: Wewenang khusus sebagai operator Scanner Barcode presensi di lokasi.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Divisi Non-Humas: Sebagai peserta acara dan dibatasi dari pengoperasian scanner.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Tidak memiliki akses membuat atau menghapus kegiatan event.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="pt-2">
                    <button type="button" onclick="closeModal('infoModalEvent')"
                        class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition text-sm">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        {{-- Search Form --}}
        <x-search-form :action="route('event.index')" placeholder="Cari event..." />

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div
                class="mb-8 flex items-center gap-4 rounded-3xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-700">
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
                class="mb-8 flex items-center gap-4 rounded-3xl bg-rose-50 border border-rose-100 p-4 text-rose-700">
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

        {{-- --- MODE MOBILE (CARD VIEW) --- --}}
        <div class="block md:hidden space-y-4">
            @forelse($events as $event)
                @php
                    $catLower = strtolower(trim($event->category ?? ''));
                    if (str_contains($catLower, 'program')) {
                        $catText = 'text-emerald-700';
                        $catBg = 'bg-emerald-50';
                        $catDot = 'bg-emerald-500';
                    } elseif (str_contains($catLower, 'rapat')) {
                        $catText = 'text-indigo-700';
                        $catBg = 'bg-indigo-50';
                        $catDot = 'bg-indigo-500';
                    } elseif ($catLower === 'lainnya') {
                        $catText = 'text-amber-700';
                        $catBg = 'bg-amber-50';
                        $catDot = 'bg-amber-500';
                    } elseif (str_contains($catLower, 'lomba') || str_contains($catLower, 'kompetisi')) {
                        $catText = 'text-rose-700';
                        $catBg = 'bg-rose-50';
                        $catDot = 'bg-rose-500';
                    } elseif (str_contains($catLower, 'seminar') || str_contains($catLower, 'workshop')) {
                        $catText = 'text-sky-700';
                        $catBg = 'bg-sky-50';
                        $catDot = 'bg-sky-500';
                    } else {
                        $catText = 'text-purple-700';
                        $catBg = 'bg-purple-50';
                        $catDot = 'bg-purple-500';
                    }
                @endphp
                <div
                    class="rounded-3xl border border-white/60 bg-white/70 p-5 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.02)] backdrop-blur-xl">

                    {{-- Detail Atas --}}
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 text-xs font-bold {{ $catText }} {{ $catBg }} px-2.5 py-1 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full {{ $catDot }}"></span>
                                {{ $event->category }}
                            </span>
                            @if ($event->is_geofencing)
                                <span
                                    class="text-[11px] font-bold bg-amber-500/10 text-amber-700 px-2.5 py-1 rounded-full">📍
                                    Wajib di Sekolah (GPS)</span>
                            @else
                                <span
                                    class="text-[11px] font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">🌐
                                    Bebas (Di Mana Saja)</span>
                            @endif
                        </div>

                        <h2 class="font-extrabold text-xl text-slate-800 tracking-tight leading-snug">
                            {{ $event->title }}
                        </h2>

                        <div class="space-y-1 text-xs font-medium text-slate-500">
                            <div class="flex items-center gap-2">
                                📅 <span>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 font-mono font-semibold text-slate-600">
                                🕒 <span>{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                    {{-- Garis Pembatas Internal --}}
                    <div class="my-4 border-t border-slate-100"></div>

                    {{-- Panel Tombol Aksi Mobile --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Aksi Kegiatan</span>
                        <div class="flex items-center gap-2">

                            {{-- Scan QR --}}
                            @if (Auth::user() && in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas', 'anggota_humas']))
                                @php
                                    $today = \Carbon\Carbon::today()->toDateString();
                                    $eventDate = $event->event_date;
                                    $dateFormatted = \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y');
                                    
                                    if ($today < $eventDate) {
                                        $statusHari = 'sebelum';
                                        $btnTitle = "Presensi Belum Dibuka ({$dateFormatted})";
                                        $btnStyle = "bg-sky-500/10 hover:bg-sky-500 text-sky-600 hover:text-white";
                                    } elseif ($today > $eventDate) {
                                        $statusHari = 'setelah';
                                        $btnTitle = "Presensi Sudah Berakhir";
                                        $btnStyle = "bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600";
                                    } else {
                                        $statusHari = 'hari_ini';
                                        $btnTitle = "Scan QR (Hari Acara - Siap Absen)";
                                        $btnStyle = "bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white";
                                    }
                                @endphp
                                <a href="{{ route('attendance.scan', $event->id) }}"
                                    title="{{ $btnTitle }}"
                                    data-status="{{ $statusHari }}"
                                    data-title="{{ $event->title }}"
                                    data-date="{{ $dateFormatted }}"
                                    class="btn-scan-qr flex items-center justify-center {{ $btnStyle }} rounded-xl transition shadow-sm h-[36px] w-[36px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Data Kehadiran --}}
                            <a href="{{ route('attendance.report', $event->id) }}" title="Data Kehadiran"
                                class="flex items-center justify-center bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white rounded-xl transition shadow-sm h-[36px] w-[36px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0 1 12 3m0 0c2.917 0 5.747.294 8.5.862m-21 1.402v11.494c0 1.242 1.008 2.25 2.25 2.25h1.371" />
                                </svg>
                            </a>

                            {{-- Broadcast --}}
                            @if (Auth::user() && Auth::user()->role === 'sekretaris')
                                <form action="{{ route('event.broadcast', $event->id) }}" method="POST"
                                    class="inline form-broadcast">
                                    @csrf
                                    <button type="submit" title="Broadcast Email"
                                        class="flex items-center justify-center bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-white p-2 rounded-xl transition shadow-sm h-[32px] w-[32px] md:h-[32px] md:w-[32px] lg:h-[36px] lg:w-[36px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0l-7.5-4.615a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('event.destroy', $event->id) }}" method="POST"
                                    class="inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Event"
                                        class="flex items-center justify-center bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white p-2 rounded-xl transition shadow-sm h-[32px] w-[32px] md:h-[32px] md:w-[32px] lg:h-[36px] lg:w-[36px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-white/60 bg-white/70 p-8 text-center text-slate-400 font-medium">
                    Belum ada event kegiatan yang dibuat.
                </div>
            @endforelse
        </div>

        {{-- --- MODE DESKTOP (TABLE VIEW) --- --}}
        <div
            class="hidden md:block relative overflow-hidden rounded-[32px] border border-white/60 bg-white/70 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Nama Kegiatan</th>
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Kategori</th>
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Tanggal</th>
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Waktu Absen</th>
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Lokasi Presensi</th>
                            <th
                                class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 text-center w-40">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($events as $event)
                            @php
                                $catLower = strtolower(trim($event->category ?? ''));
                                if (str_contains($catLower, 'program')) {
                                    $catText = 'text-emerald-700';
                                    $catBg = 'bg-emerald-50';
                                    $catDot = 'bg-emerald-500';
                                } elseif (str_contains($catLower, 'rapat')) {
                                    $catText = 'text-indigo-700';
                                    $catBg = 'bg-indigo-50';
                                    $catDot = 'bg-indigo-500';
                                } elseif ($catLower === 'lainnya') {
                                    $catText = 'text-amber-700';
                                    $catBg = 'bg-amber-50';
                                    $catDot = 'bg-amber-500';
                                } elseif (str_contains($catLower, 'lomba') || str_contains($catLower, 'kompetisi')) {
                                    $catText = 'text-rose-700';
                                    $catBg = 'bg-rose-50';
                                    $catDot = 'bg-rose-500';
                                } elseif (str_contains($catLower, 'seminar') || str_contains($catLower, 'workshop')) {
                                    $catText = 'text-sky-700';
                                    $catBg = 'bg-sky-50';
                                    $catDot = 'bg-sky-500';
                                } else {
                                    $catText = 'text-purple-700';
                                    $catBg = 'bg-purple-50';
                                    $catDot = 'bg-purple-500';
                                }
                            @endphp
                            <tr class="group hover:bg-rose-50/50 transition-colors duration-200">
                                <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $event->title }}</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 text-sm font-bold {{ $catText }}">
                                        <span class="h-2 w-2 rounded-full {{ $catDot }}"></span>
                                        {{ $event->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600 font-medium">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-5 text-xs font-mono text-slate-500 font-semibold">
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} WIB
                                </td>
                                <td class="px-6 py-5 text-sm font-medium">
                                    @if ($event->is_geofencing)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-700 border border-amber-200/60">
                                            📍 Wajib di Sekolah (GPS)
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/60">
                                            🌐 Bebas (Di Mana Saja)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if (Auth::user() && in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas', 'anggota_humas']))
                                            @php
                                                $today = \Carbon\Carbon::today()->toDateString();
                                                $eventDate = $event->event_date;
                                                $dateFormatted = \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y');
                                                
                                                if ($today < $eventDate) {
                                                    $statusHari = 'sebelum';
                                                    $btnTitle = "Presensi Belum Dibuka ({$dateFormatted})";
                                                    $btnStyle = "bg-sky-500/10 hover:bg-sky-500 text-sky-600 hover:text-white";
                                                } elseif ($today > $eventDate) {
                                                    $statusHari = 'setelah';
                                                    $btnTitle = "Presensi Sudah Berakhir";
                                                    $btnStyle = "bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600";
                                                } else {
                                                    $statusHari = 'hari_ini';
                                                    $btnTitle = "Scan QR (Hari Acara - Siap Absen)";
                                                    $btnStyle = "bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white";
                                                }
                                            @endphp
                                            <a href="{{ route('attendance.scan', $event->id) }}"
                                                title="{{ $btnTitle }}"
                                                data-status="{{ $statusHari }}"
                                                data-title="{{ $event->title }}"
                                                data-date="{{ $dateFormatted }}"
                                                class="btn-scan-qr flex items-center justify-center {{ $btnStyle }} p-2 rounded-xl transition shadow-sm h-[32px] w-[32px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z" />
                                                </svg>
                                            </a>
                                        @endif

                                        <a href="{{ route('attendance.report', $event->id) }}" title="Data Kehadiran"
                                            class="flex items-center justify-center bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white p-2 rounded-xl transition shadow-sm h-[32px] w-[32px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0 1 12 3m0 0c2.917 0 5.747.294 8.5.862m-21 1.402v11.494c0 1.242 1.008 2.25 2.25 2.25h1.371" />
                                            </svg>
                                        </a>

                                        @if (Auth::user() && Auth::user()->role === 'sekretaris')
                                            <form action="{{ route('event.broadcast', $event->id) }}" method="POST"
                                                class="inline form-broadcast">
                                                @csrf
                                                <button type="submit" title="Broadcast Email"
                                                    class="flex items-center justify-center bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-white p-2 rounded-xl transition shadow-sm h-[32px] w-[32px] md:h-[32px] md:w-[32px] lg:h-[36px] lg:w-[36px]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0l-7.5-4.615a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                    </svg>
                                                </button>
                                            </form>

                                            {{-- Hapus --}}
                                            <form action="{{ route('event.destroy', $event->id) }}" method="POST"
                                                class="inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Event"
                                                    class="flex items-center justify-center bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white p-2 rounded-xl transition shadow-sm h-[32px] w-[32px] md:h-[32px] md:w-[32px] lg:h-[36px] lg:w-[36px]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2 2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-medium text-lg">Belum ada event kegiatan yang
                                            dibuat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Total Data --}}
        <p class="mt-8 text-center text-slate-400 text-sm italic">
            Total {{ $events->total() }} Event Kegiatan Tersimpan
        </p>
        {{-- Pagination --}}
        <div class="mt-12">
            {{ $events->links() }}
        </div>
    </div>
    
    <script src="{{ asset('js/event.min.js') }}"></script>
    <script src="{{ asset('js/infomodal.min.js') }}"></script>

    @if (session('scan_alert'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "{{ session('scan_alert.type') }}",
                    title: "{{ session('scan_alert.title') }}",
                    text: "{{ session('scan_alert.message') }}",
                    confirmButtonColor: "{{ session('scan_alert.type') === 'info' ? '#0284c7' : '#64748b' }}",
                    confirmButtonText: 'Mengerti',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[28px]',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                    }
                });
            });
        </script>
    @endif
</body>

</html>
