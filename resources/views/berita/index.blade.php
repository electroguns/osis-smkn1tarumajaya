<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Berita OSIS SMKN 1 TARUMAJAYA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    @php
        $authUser = Auth::user();
        $authRole = $authUser ? $authUser->role : null;

        $isPimpinan = in_array($authRole, ['ketua', 'wakil']);
        $isBph = in_array($authRole, ['sekretaris', 'bendahara']);
        $isDivisi = \Illuminate\Support\Str::contains($authRole ?? '', 'divisi');
    @endphp

    <section id="berita"
        class="py-20 bg-gradient-to-br from-[#2d0a0f] via-[#571319] to-[#8b1e2b] relative overflow-hidden min-h-screen">
        <div class="absolute top-0 left-0 w-96 h-96 bg-red-500/20 blur-3xl rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-400/10 blur-3xl rounded-full"></div>

        <div class="fixed left-4 top-1/2 -translate-y-1/2 z-[100]">
            <div id="back-btn-container"
                class="mobile-back-btn is-closed transition-all duration-300 flex items-center justify-center
                      bg-gradient-to-br from-[#571319] to-rose-500
                    shadow-lg shadow-rose-200 w-8 h-12 rounded-r-full -translate-x-[20px] cursor-pointer">
                <a href="{{ url('/') }}" id="back-link" onclick="handleMobileBack(event)"
                    class="w-full h-full flex items-center justify-center">

                    <svg id="back-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" class="transition-all duration-300">

                        <path id="back-arrow" d="M9 18l6-6-6-6" class="transition-all duration-300" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 overflow-x-hidden">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-rose-200 text-xs font-bold uppercase tracking-wider backdrop-blur-sm border border-white/10">Agenda</span>
                        
                        {{-- Tombol Info Hak Akses Halaman --}}
                        <button type="button" onclick="openModal('infoModalBerita')"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-white border border-white/20 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                            title="Klik untuk melihat informasi hak akses halaman ini">
                            <span class="w-4 h-4 rounded-full bg-rose-500/40 text-white group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                            <span>Info Hak Akses</span>
                        </button>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white">Berita & Kegiatan</h2>
                </div>

                @auth
                    @if (str_contains(Auth::user()->role, 'anggota_divisi_'))
                        <a href="{{ route('berita.create') }}" aria-label="Buat Berita Baru"
                            class="group relative w-full md:w-auto inline-flex items-center justify-center gap-3 px-6 md:px-8 py-4 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white text-sm md:text-base font-bold rounded-2xl transition-all duration-300 hover:shadow-[0_20px_40px_-15px_rgba(225,29,72,0.4)] active:scale-[0.98] overflow-hidden">
                            <span class="relative z-10">Buat Berita Baru</span>
                            <div class="relative z-10 h-7 w-7 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </a>
                    @endif
                @endauth
            </div>

            {{-- ================= POPUP MODAL HAK AKSES BERITA & KEGIATAN ================= --}}
            <div id="infoModalBerita"
                class="fixed inset-0 top-0 left-0 w-full h-full bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
                <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-100/90 relative max-h-[90vh] overflow-y-auto custom-time-scroll animate-in fade-in zoom-in duration-200">
                    
                    {{-- Modal Header --}}
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                    Informasi Hak Akses Berita & Kegiatan
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Ketentuan wewenang penulisan artikel, peninjauan draf, dan penerbitan berita OSIS.
                                </p>
                            </div>
                        </div>

                        <button type="button" onclick="closeModal('infoModalBerita')"
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
                                        <span>Melihat seluruh artikel berita (status Draft maupun Published).</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Berhak mengedit & mempublikasikan berita dari semua divisi.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Menghapus berita yang tidak sesuai standar informasi sekolah.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Akses pratinjau lengkap isi berita sebelum tayang ke publik.</span>
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
                                        <span><b>Sekretaris</b>: Wewenang approval persetujuan berita draf menjadi Published.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Menyunting dan menyempurnakan artikel berita dari seluruh divisi.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Menerima notifikasi otomatis ketika ada draf berita baru diajukan.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Menghapus berita yang keliru, duplikat, atau sudah kedaluwarsa.</span>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
                                        <span><b>Anggota Divisi</b>: Wewenang membuat & menulis artikel berita kegiatan baru (Draft).</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span><b>Ketua Divisi</b>: Berhak mereview, menyunting, dan menyetujui (publish) berita divisi masing-masing.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        <span>Mengelola & mengedit draft artikel milik sendiri sebelum disetujui.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                        <span class="text-slate-400">Anggota dibatasi dari menyunting atau melihat draf berita milik divisi lain.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="pt-2">
                        <button type="button" onclick="closeModal('infoModalBerita')"
                            class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition text-sm">
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>

            {{-- Search Form --}}
            <x-search-form :action="route('berita.index')" placeholder="Cari berita..." />

            {{-- Alert Messages --}}
            @if ($message = Session::get('success'))
                <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400 text-sm">
                    {{ $message }}
                </div>
            @endif

            {{-- Berita Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($beritas as $berita)
                    <div
                        class="group rounded-3xl overflow-hidden bg-white hover:border-rose-500/30 transition-all duration-300 flex flex-col justify-between h-full shadow-xl border border-slate-200">

                        <a href="{{ route('berita.show', $berita) }}" class="block flex-1">
                            <div
                                class="h-48 relative flex items-center justify-center bg-slate-100 overflow-hidden">
                                @if ($berita->image)
                                    <img src="{{ asset('storage/berita/' . $berita->image) }}"
                                        alt="{{ $berita->title }}"
                                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-black/20"></div>
                                @else
                                    <div class="absolute inset-0 opacity-20 grid-pattern"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="text-slate-400 relative z-10 group-hover:scale-105 transition-transform duration-500">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" x2="16" y1="2" y2="6"></line>
                                        <line x1="8" x2="8" y1="2" y2="6"></line>
                                        <line x1="3" x2="21" y1="10" y2="10"></line>
                                    </svg>
                                @endif
                            </div>

                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    @php
                                        $status = $berita->statusLabelFor(auth()->user());
                                    @endphp

                                    @if ($status === 'published')
                                        <span
                                            class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                                            Published
                                        </span>
                                    @elseif($status === 'draft')
                                        @if ($berita->status === 'draft' && is_null($berita->rejection_reason))
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-amber-600 bg-amber-100 px-2.5 py-0.5 rounded-full animate-pulse">
                                                Sudah Direvisi / Baru
                                            </span>
                                        @else
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-red-600 bg-red-100 px-2.5 py-0.5 rounded-full">
                                                Draft (Ditolak)
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <h3
                                    class="font-bold text-lg text-slate-900 group-hover:text-rose-600 transition-colors line-clamp-2 leading-snug">
                                    {{ $berita->title }}
                                </h3>

                                <p class="text-slate-600 text-sm mt-2 leading-relaxed line-clamp-2">
                                    {{ $berita->excerpt ?: Str::limit(strip_tags($berita->content), 100) }}
                                </p>

                                {{-- KOMPONEN PERMANEN RIWAYAT ALASAN PENOLAKAN --}}
                                @if ($berita->status === 'draft' && !is_null($berita->rejection_reason))
                                    <div
                                        class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl flex flex-col gap-0.5">
                                        <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider">⚠️
                                            Catatan Penolakan:</span>
                                        <p class="text-xs text-slate-600 italic">"{{ $berita->rejection_reason }}"</p>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 mt-4 text-xs text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>{{ $berita->published_at ? $berita->published_at->translatedFormat('j F Y') : '-' }}</span>
                                </div>
                            </div>
                        </a>

                        @auth
                            @php
                                $role = auth()->user()->role;
                                $userDivision = str_contains($role, 'ketua_divisi_') ? str_replace('ketua_divisi_', '', $role) : null;
                            @endphp

                            <div class="px-6 pb-6 pt-4 border-t border-slate-200 flex flex-col gap-3 bg-white rounded-b-3xl">
                                <div class="flex flex-wrap gap-2">

                                    {{-- JIKA BERITA SUDAH PUBLISHED (Berlaku untuk Semua Role) --}}
                                    @if ($berita->status === 'published')
                                        <div
                                            class="inline-flex items-center gap-1.5 px-3 justify-center w-full text-center py-2 text-xs font-bold bg-emerald-500 text-white rounded-xl select-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M4 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5z" />
                                                <path d="M8 9h8" />
                                                <path d="M8 13h8" />
                                                <path d="M8 17h5" />
                                            </svg>Tayang
                                        </div>
                                    @else
                                        {{-- JIKA BERITA MASIH DRAFT, TAMPILKAN TOMBOL BERDASARKAN ROLE --}}

                                        {{-- KADIV DIVISI & SEKRETARIS: TOMBOL APPROVE & REJECT --}}
                                        @if ($role === 'sekretaris' || ($userDivision && $berita->divisi === $userDivision))
                                            <form action="{{ route('berita.approve', $berita) }}" method="POST"
                                                class="flex-1 m-0">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full text-center py-2 text-xs font-bold text-emerald-600 bg-emerald-100 hover:bg-emerald-200 rounded-xl transition border border-emerald-200">
                                                    ✅ Setujui
                                                </button>
                                            </form>

                                            <button type="button"
                                                onclick="toggleIndexRejectForm(event, '{{ $berita->id }}')"
                                                class="flex-1 text-center py-2 text-xs font-bold text-red-600 bg-red-100 hover:bg-red-200 rounded-xl transition border border-red-200">
                                                ❌ Tolak
                                            </button>
                                        @endif
                                    @endif

                                    {{-- TOMBOL SUNTING --}}
                                    @if (in_array($role, ['ketua', 'wakil', 'sekretaris']) || ($userDivision && $berita->divisi === $userDivision) || (str_contains($role, 'anggota_divisi_') && auth()->id() === $berita->author_id))
                                        <a href="{{ route('berita.edit', $berita) }}"
                                            class="flex-1 text-center py-2 text-xs font-bold text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-xl transition border border-blue-200">
                                            Sunting
                                        </a>
                                    @endif

                                    {{-- HAPUS (Bisa kapan saja untuk Ketua, Wakil, Sekretaris, Kadiv Divisi, dan Anggota Divisi sendiri) --}}
                                    @if (in_array($role, ['ketua', 'wakil', 'sekretaris']) || ($userDivision && $berita->divisi === $userDivision) || (str_contains($role, 'anggota_divisi_') && auth()->id() === $berita->author_id))
                                        <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                                            class="flex-1 delete-form m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full text-center py-2 text-xs font-bold text-red-600 bg-red-100 hover:bg-red-200 rounded-xl transition border border-red-200">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- FORM BOX INPUT ALASAN POPUP DINAMIS (Hanya untuk Kadiv/Sekretaris jika belum published) --}}
                                @if (($role === 'sekretaris' || ($userDivision && $berita->divisi === $userDivision)) && $berita->status !== 'published')
                                    <div id="reject-container-{{ $berita->id }}"
                                        class="hidden mt-1 pt-3 border-t border-slate-200">
                                        <form action="{{ route('berita.reject', $berita) }}" method="POST"
                                            class="space-y-2.5 m-0">
                                            @csrf
                                            <div>
                                                <label for="alasan-{{ $berita->id }}"
                                                    class="block text-[11px] font-semibold text-slate-700 mb-1">
                                                    Alasan Penolakan / Catatan Perbaikan <span
                                                        class="text-red-500">*</span>
                                                </label>
                                                <textarea name="alasan" id="alasan-{{ $berita->id }}" rows="2" required
                                                    placeholder="Contoh: Tolong revisi judul dan lampirkan foto dokumentasi kegiatan..."
                                                    class="w-full bg-slate-50 text-slate-900 text-xs p-2 rounded-xl border border-slate-300 focus:border-red-500 focus:outline-none placeholder:text-slate-400"></textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-600 text-white text-[11px] font-bold rounded-lg hover:bg-red-700 transition">
                                                    Kirim via Email
                                                </button>
                                                <button type="button"
                                                    onclick="toggleIndexRejectForm(event, '{{ $berita->id }}')"
                                                    class="px-3 py-1.5 bg-slate-200 text-slate-600 text-[11px] font-bold rounded-lg hover:bg-slate-300 transition">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endauth
                    </div>
                @empty
                    <div
                        class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-white border border-slate-200 rounded-3xl">
                        <p class="text-slate-500 text-sm">Belum ada berita atau kegiatan yang tersedia.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $beritas->links() }}
            </div>
        </div>
    </section>

    <script src="{{ asset('js/Bbutton.min.js') }}"></script>
    <script src="{{ asset('js/infomodal.min.js') }}"></script>
    <script src="{{asset('js/Ibt.min.js')}}"></script>
</body>
</html>