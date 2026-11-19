<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Laporan Kegiatan</title>
        @vite('resources/css/app.css')
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

    @if (session('success'))
    <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563eb'
            });
        </script>
    @endif

    @if (session('error'))
    <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc2626'
            });
        </script>
    @endif

    @php
        $authUser = Auth::user();
        $authRole = $authUser ? $authUser->role : null;

        $isPimpinan = in_array($authRole, ['ketua', 'wakil']);
        $isBph = in_array($authRole, ['sekretaris', 'bendahara']);
        $isDivisi = \Illuminate\Support\Str::contains($authRole ?? '', 'divisi');
    @endphp

    {{-- Konten Utama --}}
    <main class="max-w-6xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- Navbar --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
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
                    <button type="button" onclick="openModal('infoModalReport')"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                        title="Klik untuk melihat informasi hak akses halaman ini">
                        <span class="w-4 h-4 rounded-full bg-rose-200 text-rose-700 group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                        <span>Info Hak Akses</span>
                    </button>
                </div>
                <h1 class="text-5xl font-extrabold tracking-tight text-slate-900">
                    Tracking <span class="text-rose-600">Laporan</span>
                </h1>
                <p class="text-slate-500 text-lg max-w-md leading-relaxed">
                    Monitor status pengajuan berkas, purpose, laporan kegiatan, dan progres approval.
                </p>
            </div>

            @if (auth()->user()->role !== 'ketua' && auth()->user()->role !== 'wakil')
                <a onclick="toggleModal('modal-create', true)" aria-label="Tambah Laporan Baru"
                    class="group relative w-full md:w-auto inline-flex items-center justify-center gap-3 px-5 md:px-8 py-4 md:py-4 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white text-sm md:text-base font-bold rounded-2xl md:rounded-[20px] transition-all duration-300 hover:bg-rose-700 active:scale-[0.98] hover:shadow-[0_20px_40px_-15px_rgba(2,132,199,0.4)] overflow-hidden">

                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white/10 via-white/0 to-white/10 transition-opacity duration-500">
                    </div>

                    <span class="relative z-10">
                        Tambah Laporan Baru
                    </span>

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

        {{-- ================= POPUP MODAL HAK AKSES TRACKING LAPORAN ================= --}}
        <div id="infoModalReport"
            class="fixed inset-0 top-0 left-0 w-full h-full bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full border border-slate-100/90 relative max-h-[90vh] overflow-y-auto custom-time-scroll animate-in fade-in zoom-in duration-200">
                
                {{-- Modal Header --}}
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                Informasi Hak Akses Tracking Laporan
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ketentuan wewenang dan alur approval berkas khusus pada halaman Tracking Laporan ini.
                            </p>
                        </div>
                    </div>

                    <button type="button" onclick="closeModal('infoModalReport')"
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
                                    <span>Memantau seluruh dokumen & tracking laporan semua divisi.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Memonitor progres status persetujuan (Draft, Purpose, Approved, Rejected).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Akses langsung membuka tautan dokumen kegiatan.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Dikhususkan untuk monitoring pengawasan (tidak membuat laporan teknis).</span>
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
                                    <span>Menyetujui (Approve) laporan yang telah di-purpose Ketua Divisi.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Menolak (Reject) laporan disertai catatan alasan revisi.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Menambah, mengedit, & menghapus laporan berkas kegiatan.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Akses penuh meninjau seluruh laporan dari semua divisi.</span>
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
                                    <span>Menambah dan mengajukan tracking laporan baru sesuai divisinya.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Ketua Divisi: Mem-purpose laporan draft agar dapat ditinjau Sekretaris.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-emerald-500 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span>Mengedit & memperbarui laporan yang diajukan divisinya.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-slate-400 font-bold shrink-0 flex items-center pt-0.5"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                    <span class="text-slate-400">Terbatas dari menyetujui (approve) atau menolak (reject) laporan final.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="pt-2">
                    <button type="button" onclick="closeModal('infoModalReport')"
                        class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition text-sm">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        {{-- Search Form --}}
        <x-search-form :action="route('reports.index')" placeholder="Cari laporan..." />

        {{-- Indikator Read-Only untuk Ketua/Wakil --}}
        @if (auth()->user()->role === 'ketua' || auth()->user()->role === 'wakil')
            <div class="mb-8 rounded-2xl border border-amber-200 bg-amber-50/80 px-5 py-4 backdrop-blur-sm">
                <p class="text-sm font-medium text-amber-800">
                    <strong>Mode Pantauan:</strong>
                    Anda hanya dapat memantau keaktifan berkas divisi. Keputusan akhir dilakukan secara offline.
                </p>
            </div>
        @endif

        {{-- Tabel Data (READ) --}}
        <div
            class="relative overflow-hidden rounded-[32px] border border-white/60 bg-white/70 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
            {{-- Dekstop View --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="text-[11px] uppercase tracking-[0.18em] text-slate-500">
                            <tr class="text-gray-500 text-left text-xs uppercase font-semibold tracking-wider">
                                <th class="px-6 py-3.5">Nama Kegiatan</th>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Divisi / PIC</th>
                                <th class="px-6 py-3.5">Sifat Dokumen</th>
                                <th class="px-6 py-3.5">Link Dokumen</th>
                                <th class="px-6 py-3.5">Status Approval</th>
                                @if (auth()->user()->role !== 'ketua' && auth()->user()->role !== 'wakil')
                                    <th class="px-6 py-3.5 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-gray-600 text-sm">
                            @forelse ($reports as $report)
                                <tr class="transition-all duration-300 hover:bg-rose-50/50">
                                    <td class="px-6 py-4 font-semibold text-gray-950">{{ $report->activity_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::parse($report->activity_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="block font-medium text-gray-900">{{ $report->pic }}</span>
                                        <span
                                            class="text-xs text-gray-400 font-medium">{{ strtoupper($report->division) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                {{ $report->type === 'purpose' ? 'bg-indigo-100 text-indigo-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($report->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ $report->document_link }}" target="_blank"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white transition hover:bg-blue-700font-medium hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                c4.477 0 8.268 2.943 9.542 7
                                                -1.274 4.057-5.065 7-9.542 7
                                                -4.477 0-8.268-2.943-9.542-7z" />
                                            </svg></a>
                                    </td>

                                    {{-- Status Approval (Khusus Sekretaris) --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Jika status draft dan role ketua divisi --}}
                                        @if (str_contains(auth()->user()->role, 'ketua_divisi_') && $report->status === 'draft')
                                            <form id="purpose-form-{{ $report->id }}"
                                                action="{{ route('reports.purpose', $report->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button type="button" onclick="confirmPurpose({{ $report->id }})"
                                                    class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] px-3 py-2 text-xs font-semibold text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 10.5l18-7.5-7.5 18-2.5-8L3 10.5z" />
                                                    </svg>
                                                    Purpose
                                                </button>
                                            </form>

                                            {{-- Jika status purpose dan role sekretaris --}}
                                        @elseif (auth()->user()->role === 'sekretaris' && $report->status === 'purpose')
                                            <div class="flex gap-2">
                                                <form id="approve-form-{{ $report->id }}"
                                                    action="{{ route('reports.approve', $report->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="button"
                                                        onclick="confirmApprove({{ $report->id }})"
                                                        class="flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Approve
                                                    </button>
                                                </form>

                                                <button onclick="openRejectModal({{ $report->id }})"
                                                    class="flex items-center gap-2 rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            </div>

                                            {{-- Status badge untuk semua kondisi --}}
                                        @else
                                            <div class="space-y-1">
                                                <span
                                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $report->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $report->status === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                                            {{ $report->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ $report->status === 'purpose' ? 'bg-amber-100 text-amber-700' : '' }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>

                                                @if ($report->status === 'rejected' && $report->rejection_reason)
                                                    <p class="text-xs text-rose-600 italic max-w-[180px] truncate"
                                                        title="{{ $report->rejection_reason }}">
                                                        Ket: {{ $report->rejection_reason }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Fitur UPDATE & DELETE --}}
                                    @if (auth()->user()->role !== 'ketua' && auth()->user()->role !== 'wakil')
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            @if ($report->status === 'purpose')
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold text-white bg-rose-500 rounded-full">
                                                    Pending
                                                </span>
                                            @elseif ($report->canManage(auth()->user()))
                                                <div class="flex items-center gap-2">

                                                    {{-- EDIT --}}
                                                    <button onclick="openEditModal({{ json_encode($report) }})"
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500 text-white transition hover:bg-amber-600">

                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                        </svg>

                                                    </button>

                                                    {{-- DELETE --}}
                                                    <form id="delete-form-{{ $report->id }}"
                                                        action="{{ route('reports.destroy', $report->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                            onclick="confirmDelete({{ $report->id }})"
                                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 text-white transition hover:bg-rose-600">

                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                    a2 2 0 01-1.995-1.858L5 7" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M10 11v6M14 11v6M9 7V4
                                                                    a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                                                            </svg>

                                                        </button>
                                                    </form>

                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No Access</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16">
                                        <div class="text-center">
                                            <div
                                                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                                                📄
                                            </div>

                                            <h3 class="text-lg font-semibold text-slate-800">
                                                Belum ada laporan
                                            </h3>

                                            <p class="mt-2 text-sm text-slate-500">
                                                Laporan kegiatan yang ditambahkan akan muncul di sini.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile View --}}
            <div class="md:hidden p-4 space-y-4">
                @forelse ($reports as $report)
                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">

                        {{-- Header --}}
                        <div>
                            <h2 class="font-bold text-lg text-slate-800 leading-tight">
                                {{ $report->activity_name }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ \Carbon\Carbon::parse($report->activity_date)->format('d M Y') }}
                            </p>
                        </div>

                        {{-- Detail --}}
                        <div class="mt-4 space-y-3">

                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    PIC
                                </p>
                                <p class="text-sm font-medium text-slate-700">
                                    {{ $report->pic }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    Divisi
                                </p>
                                <span
                                    class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ strtoupper($report->division) }}
                                </span>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400">
                                    Jenis Dokumen
                                </p>

                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            {{ $report->type === 'purpose' ? 'bg-indigo-100 text-indigo-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ ucfirst($report->type) }}
                                </span>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-400 mb-2">
                                    Status
                                </p>

                                @if (str_contains(auth()->user()->role, 'ketua_divisi_') && $report->status === 'draft')
                                    <form id="purpose-form-mobile-{{ $report->id }}"
                                        action="{{ route('reports.purpose', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="button" onclick="confirmPurpose({{ $report->id }})"
                                            class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] px-4 py-2 text-xs font-semibold text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10.5l18-7.5-7.5 18-2.5-8L3 10.5z" />
                                            </svg>
                                            Purpose
                                        </button>
                                    </form>
                                @elseif (auth()->user()->role === 'sekretaris' && $report->status === 'purpose')
                                    <div class="flex gap-2">

                                        <form id="approve-form-mobile-{{ $report->id }}"
                                            action="{{ route('reports.approve', $report->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="button" onclick="confirmApprove({{ $report->id }})"
                                                class="flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>

                                        <button onclick="openRejectModal({{ $report->id }})"
                                            class="flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Reject
                                        </button>

                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $report->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $report->status === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                                            {{ $report->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ $report->status === 'purpose' ? 'bg-amber-100 text-amber-700' : '' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>

                                    @if ($report->status === 'rejected' && $report->rejection_reason)
                                        <p class="mt-2 text-xs text-rose-600 italic">
                                            {{ $report->rejection_reason }}
                                        </p>
                                    @endif
                                @endif
                            </div>

                            @if ($report->status === 'rejected' && $report->rejection_reason)
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-400">
                                        Catatan
                                    </p>

                                    <p class="text-sm text-rose-600">
                                        {{ $report->rejection_reason }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Action --}}
                        <div class="mt-5 flex items-center gap-2">

                            {{-- View --}}
                            <a href="{{ $report->document_link }}" target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white transition hover:bg-blue-700">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.477 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.065 7-9.542 7
                                    -4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            @if ($report->canManage(auth()->user()))
                                {{-- Edit --}}
                                <button onclick="openEditModal({{ json_encode($report) }})"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-white transition hover:bg-amber-600">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <button onclick="confirmDelete({{ $report->id }})"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500 text-white transition hover:bg-rose-600">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                            a2 2 0 01-1.995-1.858L5 7" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6M9 7V4
                                            a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif

                        </div>

                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        Belum ada laporan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Footer Total Data --}}
        <p class="mt-8 text-center text-slate-400 text-sm italic">
            Total {{ $reports->total() }} Laporan Tersimpan
        </p>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $reports->links() }}
        </div>
    </main>

    <div id="modal-create"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div
            class="w-full max-w-lg overflow-visible rounded-3xl relative
                bg-white/95 backdrop-blur-xl
                border border-white/60
                shadow-[0_25px_70px_rgba(15,23,42,0.18)]
                animate-in fade-in zoom-in duration-200">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-200/70
            bg-gradient-to-r from-rose-50 to-rose-50 rounded-t-3xl">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">
                            Tracking Laporan
                        </p>

                        <h3 class="text-lg font-bold text-slate-800">
                            Tambah Tracking Baru
                        </h3>
                    </div>

                    <button onclick="toggleModal('modal-create', false)"
                        class="h-9 w-9 rounded-xl text-slate-500 hover:bg-white/70 hover:text-slate-700 transition cursor-pointer">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('reports.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="relative z-10">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                        Nama Kegiatan
                    </label>

                    <input type="text" name="activity_name" required placeholder="Contoh: Pimpin Rapat Koordinasi"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                        text-slate-900 font-semibold placeholder:text-slate-400 placeholder:font-normal
                        focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10
                        focus:outline-none transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-30">
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                            Tanggal Kegiatan
                        </label>
                        <input type="date" name="activity_date" required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                            text-slate-900 font-semibold focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10
                            focus:outline-none transition">
                    </div>

                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                            Nama Divisi
                        </label>
                        <select id="divisionSelect" name="division" required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                                text-slate-900 font-semibold
                                focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10
                                focus:outline-none transition cursor-pointer">

                            <option value="" disabled selected>
                                Pilih Divisi / Jabatan...
                            </option>

                            @foreach ($roleGrouped as $role => $users)
                                <option value="{{ $role }}">
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="relative z-20">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">PIC Kegiatan</label>
                    <select id="picSelect" name="pic" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                            text-slate-900 font-semibold
                            focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10
                            focus:outline-none transition cursor-pointer">
                        <option value="" disabled selected>
                            Pilih PIC...
                        </option>
                    </select>
                </div>

                <div class="relative z-10">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                        Link Dokumen
                    </label>
                    <input type="url" name="document_link" required placeholder="https://drive.google.com/..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                            text-slate-900 font-semibold placeholder:text-slate-400 placeholder:font-normal
                            focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10
                            focus:outline-none transition">
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 pt-5 mt-6 border-t border-slate-200/70">
                    <button type="button" onclick="toggleModal('modal-create', false)"
                        class="px-5 py-3 rounded-2xl border border-slate-200
                        bg-white text-slate-600 font-bold text-sm
                        hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-6 py-3 rounded-2xl
                            bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319]
                            text-white font-bold text-sm
                            shadow-lg shadow-rose-500/25
                            hover:scale-[1.02] active:scale-95 transition cursor-pointer">
                        Ajukan Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div
            class="w-full max-w-lg overflow-visible rounded-3xl relative bg-white/95 backdrop-blur-xl border border-white/60 
                shadow-[0_25px_70px_rgba(15,23,42,0.18)] animate-in fade-in zoom-in duration-200">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-200/70 bg-gradient-to-r from-amber-50 to-orange-50 rounded-t-3xl">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">
                            Edit Dokumen
                        </p>
                        <h3 class="text-lg font-bold text-slate-800">
                            Perbarui Tracking
                        </h3>
                    </div>
                    <button onclick="toggleModal('modal-edit', false)"
                        class="h-9 w-9 rounded-xl text-slate-500 hover:bg-white/70 hover:text-slate-700 transition cursor-pointer flex items-center justify-center">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form id="form-edit" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div class="relative z-10">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                        Nama Kegiatan
                    </label>

                    <input type="text" id="edit_activity_name" name="activity_name" required placeholder="Nama kegiatan..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-slate-900 font-semibold placeholder:text-slate-400 placeholder:font-normal
                        focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:outline-none transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-30">
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                            Tanggal Kegiatan
                        </label>

                        <input type="date" id="edit_activity_date" name="activity_date" required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-slate-900 font-semibold focus:border-amber-500 
                            focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:outline-none transition">
                    </div>

                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                            Nama Divisi
                        </label>
                        <select id="edit_division" name="division" required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                            text-slate-900 font-semibold focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10
                            focus:outline-none transition cursor-pointer">
                            <option value="" disabled>Pilih Divisi / Jabatan...</option>

                            @foreach ($roleGrouped as $role => $users)
                                <option value="{{ $role }}">
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="relative z-20">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                        PIC Kegiatan
                    </label>

                    <select id="edit_pic" name="pic" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                        text-slate-900 font-semibold focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10
                        focus:outline-none transition cursor-pointer">
                        <option value="" disabled>Pilih PIC...</option>
                    </select>
                </div>

                <div class="relative z-10">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                        Link Dokumen
                    </label>
                    <input type="url" id="edit_document_link" name="document_link" required
                        placeholder="https://drive.google.com/..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-slate-900 font-semibold placeholder:text-slate-400 placeholder:font-normal 
                        focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:outline-none transition">
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 pt-5 mt-6 border-t border-slate-200/70">

                    <button type="button" onclick="toggleModal('modal-edit', false)"
                        class="px-5 py-3 rounded-2xl border border-slate-200
                        bg-white text-slate-600 font-bold text-sm
                        hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 text-white 
                        font-bold text-sm shadow-lg shadow-amber-500/25 hover:scale-[1.02] active:scale-95 transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-reject"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div
            class="w-full max-w-md overflow-hidden rounded-3xl
        bg-white/95 backdrop-blur-xl
        border border-white/60
        shadow-[0_25px_70px_rgba(15,23,42,0.18)]
        animate-in fade-in zoom-in duration-200">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-200/70
            bg-gradient-to-r from-rose-50 to-red-50 rounded-t-3xl">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">
                            Penolakan Dokumen
                        </p>

                        <h3 class="text-lg font-bold text-slate-800">
                            Alasan Penolakan
                        </h3>
                    </div>

                    <button onclick="toggleModal('modal-reject', false)"
                        class="h-9 w-9 rounded-xl text-slate-500 hover:bg-white/70 hover:text-slate-700 transition cursor-pointer flex items-center justify-center">
                        ✕
                    </button>

                </div>
            </div>

            {{-- Form --}}
            <form id="form-reject" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div>

                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 ml-1">
                        Tulis Alasan / Catatan Perbaikan
                    </label>

                    <textarea id="rejection_reason" name="rejection_reason" required rows="5"
                        placeholder="Contoh: Lampiran anggaran belum lengkap, format tanda tangan tidak sesuai, atau terdapat data yang perlu diperbaiki."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5
                    text-slate-900 font-semibold placeholder:text-slate-400 placeholder:font-normal resize-none
                    focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10
                    focus:outline-none transition"></textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        Catatan ini akan dikirim kepada pengaju sebagai bahan perbaikan dokumen.
                    </p>

                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 pt-5 mt-6 border-t border-slate-200/70">

                    <button type="button" onclick="toggleModal('modal-reject', false)"
                        class="px-5 py-3 rounded-2xl border border-slate-200
                        bg-white text-slate-600 font-bold text-sm
                        hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>

                    <button type="button" onclick="confirmReject()"
                        class="px-6 py-3 rounded-2xl
                        bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319]
                        text-white font-bold text-sm
                        shadow-lg shadow-rose-500/25
                        hover:scale-[1.02] active:scale-95
                        transition cursor-pointer">
                        Tolak & Kirim
                    </button>

                </div>
            </form>

        </div>
    </div>

    <script src="{{ asset('js/infomodal.min.js') }}"></script>
    <script src="{{ asset('js/custom-ui.js') }}"></script>
    <script>
        const divisionSelect = document.getElementById('divisionSelect');
        const picSelect = document.getElementById('picSelect');

        const roleData = @json($roleGrouped);

        divisionSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            const users = roleData[selectedRole] || [];

            // Kosongkan PIC dropdown
            picSelect.innerHTML = '<option value="" disabled selected>Pilih PIC...</option>';

            users.forEach(user => {
                const opt = document.createElement('option');
                opt.value = user.name;
                opt.textContent = user.name;
                picSelect.appendChild(opt);
            });

            if (window.syncCustomUI) {
                window.syncCustomUI();
            }
        });

        function toggleModal(modalId, show) {
            const modal = document.getElementById(modalId);
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function openEditModal(report) {

            document.getElementById('edit_activity_name').value = report.activity_name;
            document.getElementById('edit_activity_date').value = report.activity_date;
            document.getElementById('edit_document_link').value = report.document_link;

            document.getElementById('edit_division').value = report.division;

            const picSelect = document.getElementById('edit_pic');
            picSelect.innerHTML = '';

            if (roleData[report.division]) {

                roleData[report.division].forEach(user => {

                    const option = document.createElement('option');
                    option.value = user.name;
                    option.textContent = user.name;

                    if (user.name === report.pic) {
                        option.selected = true;
                    }

                    picSelect.appendChild(option);
                });
            }

            document.getElementById('form-edit').action =
                `/tracking-laporan/${report.id}`;

            if (window.syncCustomUI) {
                window.syncCustomUI();
            }

            toggleModal('modal-edit', true);
        }

        function openRejectModal(reportId) {
            document.getElementById('form-reject').action = `/tracking-laporan/${reportId}/reject`;
            toggleModal('modal-reject', true);
        }

        function confirmPurpose(id) {
            Swal.fire({
                title: 'Purpose laporan?',
                text: 'Laporan akan dikirim ke Sekretaris untuk approval.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                customClass: {
                    popup: 'rounded-[24px]',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-medium px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`purpose-form-${id}`).submit();
                }
            });
        }

        function confirmApprove(id) {
            Swal.fire({
                title: 'Approve laporan?',
                text: 'Pastikan laporan sudah sesuai.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#16a34a',
                customClass: {
                    popup: 'rounded-[24px]',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-medium px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`approve-form-${id}`).submit();
                }
            });
        }

        function confirmReject() {

            const reason = document.getElementById('rejection_reason').value;

            if (!reason.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Alasan wajib diisi'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Penolakan',
                html: `
            <div class="text-left">
                <p class="mb-2">Alasan yang akan dikirim:</p>
                <div class="bg-gray-100 p-3 rounded text-sm">
                    ${reason}
                </div>
            </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak & Kirim',
                cancelButtonText: 'Kembali Edit',
                confirmButtonColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-reject').submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus laporan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[24px]',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-medium px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
</body>
</html>