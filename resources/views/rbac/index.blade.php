<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Matriks Hak Akses Semua Role (RBAC) - OSIS SMKN 1 Tarumajaya</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .liquid-glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 50px -12px rgba(190, 18, 60, 0.08);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-100 via-slate-50 to-pink-50 bg-fixed font-[Plus_Jakarta_Sans] antialiased text-slate-800">

    {{-- Pemanggilan Komponen Sidebar Navigasi Dini --}}
    @include('dini')

    <div class="relative overflow-hidden py-8 sm:py-12">
        {{-- Ambient Lighting Background --}}
        <div class="absolute top-10 left-1/4 w-[500px] h-[500px] bg-rose-500/10 rounded-full blur-[140px] pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 right-1/4 w-[450px] h-[450px] bg-[#571319]/10 rounded-full blur-[140px] pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Breadcrumb & Tombol Kembali --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-rose-600 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <span>/</span>
                    <span class="text-rose-700 font-bold">Matriks Hak Akses (RBAC)</span>
                </nav>

                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-rose-700 hover:border-rose-300 text-xs font-bold shadow-sm transition hover:shadow">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard Utama</span>
                </a>
            </div>

            {{-- Header Halaman RBAC --}}
            <div class="liquid-glass-card rounded-[2.5rem] p-6 sm:p-10 mb-8 border border-white shadow-xl relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-gradient-to-br from-rose-500/20 via-pink-400/10 to-transparent rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rose-100 border border-rose-200 text-rose-800 text-[11px] font-black tracking-widest uppercase mb-3 shadow-sm">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                            <span>👑 Panel Eksklusif Ketua OSIS</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                            Matriks Hak Akses & Otoritas Seluruh Role
                        </h1>
                        <p class="text-slate-600 text-sm sm:text-base mt-2.5 leading-relaxed">
                            Peta wewenang operasional komprehensif seluruh <strong>12 Jabatan Resmi Kepengurusan OSIS SMKN 1 Tarumajaya</strong>. Halaman ini hanya dapat diakses secara khusus oleh akun Ketua OSIS untuk pengawasan tata kelola sistem.
                        </p>
                    </div>

                    {{-- Card Salin Password Universal --}}
                    <div class="shrink-0 p-4 rounded-2xl bg-gradient-to-r from-rose-50 via-white to-pink-50 border border-rose-200/90 shadow-sm flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-600 to-[#571319] text-white flex items-center justify-center shrink-0 shadow-md">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase tracking-wider font-black text-slate-400">Password Universal Seluruh Role</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <code class="px-2.5 py-1 rounded bg-white font-mono text-sm font-black text-rose-700 border border-rose-200 shadow-inner">osistmj1</code>
                                <button type="button" onclick="navigator.clipboard.writeText('osistmj1'); Swal.fire({icon: 'success', title: 'Tersalin', text: 'Password osistmj1 berhasil disalin!', timer: 1500, showConfirmButton: false});" class="px-2 py-1 rounded bg-rose-600 hover:bg-rose-700 text-white text-[11px] font-bold shadow-sm transition">
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= HIGHLIGHT KHUSUS: SIAPA YANG BISA MENAMBAHKAN LINK WHATSAPP ================= --}}
            <div class="liquid-glass-card rounded-[2.5rem] p-6 sm:p-8 mb-8 border border-emerald-200/80 bg-gradient-to-r from-emerald-500/[0.04] via-teal-500/[0.02] to-white/90 shadow-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-emerald-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white flex items-center justify-center shrink-0 shadow-md">
                            <span class="text-2xl">📱</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Otoritas Integrasi WhatsApp
                            </span>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                                Siapa Saja yang Bisa Menambahkan / Mengubah Link WhatsApp?
                            </h2>
                        </div>
                    </div>

                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-white border border-emerald-200 text-emerald-800 shadow-sm self-start md:self-auto">
                        4 Role Diberikan Izin
                    </span>
                </div>

                <p class="text-xs sm:text-sm text-slate-600 mb-6 leading-relaxed">
                    Fitur ini digunakan untuk memperbarui tautan undangan grup WhatsApp resmi pada formulir pendaftaran calon pengurus OSIS (<code>/pendaftaran-osis</code>). Berdasarkan verifikasi kode kontroler (<code>OsisController::updateWhatsAppLink</code>) dan tampilan (<code>pendaftaran.blade.php</code>), hanya <strong>4 jabatan berikut</strong> yang memiliki hak akses:
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- 1. Ketua --}}
                    <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-sm flex items-start gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0 text-sm">✓</span>
                        <div>
                            <div class="text-[10px] font-black uppercase text-emerald-700">Top Executive</div>
                            <div class="font-black text-slate-900 text-sm">Ketua OSIS</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Dapat update & kelola link grup kapan saja.</div>
                        </div>
                    </div>

                    {{-- 2. Wakil Ketua --}}
                    <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-sm flex items-start gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0 text-sm">✓</span>
                        <div>
                            <div class="text-[10px] font-black uppercase text-emerald-700">Co-Executive</div>
                            <div class="font-black text-slate-900 text-sm">Wakil Ketua OSIS</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Dapat update & kelola link grup kapan saja.</div>
                        </div>
                    </div>

                    {{-- 3. Sekretaris --}}
                    <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-sm flex items-start gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0 text-sm">✓</span>
                        <div>
                            <div class="text-[10px] font-black uppercase text-emerald-700">Administrasi Utama</div>
                            <div class="font-black text-slate-900 text-sm">Sekretaris</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Penanggung jawab teknis tautan pendaftaran.</div>
                        </div>
                    </div>

                    {{-- 4. Kadiv Humas --}}
                    <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-sm flex items-start gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0 text-sm">✓</span>
                        <div>
                            <div class="text-[10px] font-black uppercase text-emerald-700">Hubungan Masyarakat</div>
                            <div class="font-black text-slate-900 text-sm">Ketua Divisi Humas</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Pengelola komunikasi dan alur calon pendaftar.</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-xl bg-slate-100/80 border border-slate-200 flex items-center justify-between text-[11px] text-slate-600">
                    <span class="font-medium">
                        🚫 <strong>Role Lainnya (Bendahara, Kadiv Agama, Kadiv Olahraga, Kadiv Artmedia, dan seluruh Anggota Divisi)</strong>: Akses otomatis terkunci (HTTP 403 Forbidden).
                    </span>
                    <span class="font-mono text-emerald-700 font-bold hidden sm:inline">POST /admin/osis/whatsapp-link</span>
                </div>
            </div>

            {{-- ================= TABEL MATRIKS RBAC 12 JABATAN RESMI ================= --}}
            <div class="liquid-glass-card rounded-[2.5rem] p-6 sm:p-10 mb-8 border border-white shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-200/70">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-black text-slate-800 uppercase tracking-wider mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                            <span>Tabel Matriks Otoritas Modul (RBAC System)</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Pemetaan Hak Akses 12 Jabatan Resmi Kepengurusan
                        </h2>
                    </div>

                    <div class="text-[11px] font-bold text-slate-500 flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">12 Jabatan Resmi</span>
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">11 Kategori Modul</span>
                    </div>
                </div>

                {{-- Tabel Responsif Scroll Horizontal --}}
                <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm bg-white">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-rose-950 via-[#571319] to-rose-950 text-white font-black uppercase tracking-wider text-[11px]">
                                <th class="py-4 px-4 sticky left-0 z-30 bg-[#571319] min-w-[230px] shadow-[3px_0_8px_rgba(0,0,0,0.18)]">
                                    Modul / Fitur Sistem
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Ketua OSIS
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Wakil Ketua
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Sekretaris
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Bendahara
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60 bg-rose-900/40">
                                    Kadiv Humas
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Anggota Humas
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60 bg-rose-900/40">
                                    Kadiv Agama
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Anggota Agama
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60 bg-rose-900/40">
                                    Kadiv Olahraga
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Anggota Olahraga
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60 bg-rose-900/40">
                                    Kadiv Artmedia
                                </th>
                                <th class="py-4 px-3 min-w-[130px] text-center border-l border-rose-800/60">
                                    Anggota Artmedia
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">

                            {{-- 1. REVIEW KEUANGAN --}}
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0">$</span>
                                        <div>
                                            <div class="text-xs font-black">Review Keuangan</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Input kas, approval & saldo</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Approve</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Review</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Review</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-sm">★ Full (Input Kas)</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Review</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Review</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Review</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Review</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                            {{-- 2. CALON PENGURUS & REKRUTMEN --}}
                            <tr class="hover:bg-slate-50/80 transition bg-slate-50/20">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-rose-100 text-rose-800 font-bold flex items-center justify-center text-xs shrink-0">👥</span>
                                        <div>
                                            <div class="text-xs font-black">Data Calon OSIS</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Pendaftaran & seleksi berkas</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-sm">★ Keputusan</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Seleksi</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Berkas</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-100 text-cyan-800 border border-cyan-200">✓ Seleksi</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">✓ Input Calon</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                            {{-- 3. LINK WHATSAPP (FEATURE REQUEST KHUSUS USER) --}}
                            <tr class="hover:bg-emerald-50/40 transition bg-emerald-50/20 font-semibold">
                                <td class="py-3.5 px-4 font-bold text-emerald-950 sticky left-0 bg-emerald-50/90 z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-emerald-500 text-white font-bold flex items-center justify-center text-xs shrink-0">📱</span>
                                        <div>
                                            <div class="text-xs font-black text-emerald-900">Link WhatsApp Calon</div>
                                            <div class="text-[10px] text-emerald-700 font-normal">Tambah / ubah tautan grup WA</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100 bg-emerald-50/50">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white shadow-sm">✓ Update WA</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100 bg-emerald-50/50">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white shadow-sm">✓ Update WA</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100 bg-emerald-50/50">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white shadow-sm">✓ Update WA</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100 bg-emerald-100/50">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white shadow-sm">✓ Update WA</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-emerald-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                            {{-- 4. PELACAKAN DOKUMEN & LPJ --}}
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs shrink-0">📑</span>
                                        <div>
                                            <div class="text-xs font-black">Pelacakan Dokumen / LPJ</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Buat, ajukan & validasi LPJ</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Monitor</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Monitor</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white shadow-sm">★ Validasi & Approve</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">⊙ Akses</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-100 text-cyan-800 border border-cyan-200">✓ Buat & Purpose</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">⊙ Draft Humas</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">✓ Buat & Purpose</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200">⊙ Draft Agama</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-orange-100 text-orange-800 border border-orange-200">✓ Buat & Purpose</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-800 border border-orange-200">⊙ Draft Olahraga</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-pink-100 text-pink-800 border border-pink-200">✓ Buat & Purpose</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-800 border border-pink-200">⊙ Draft Artmedia</span>
                                </td>
                            </tr>

                            {{-- 5. STRUKTUR PENGURUS & JABATAN --}}
                            <tr class="hover:bg-slate-50/80 transition bg-slate-50/20">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-xs shrink-0">🏛️</span>
                                        <div>
                                            <div class="text-xs font-black">Struktur Pengurus</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Kelola jabatan & anggota divisi</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-sm">★ Full (Semua)</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Full (Semua)</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Full (Semua)</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">+ Anggota Humas</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200">+ Anggota Agama</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-800 border border-orange-200">+ Anggota Olahraga</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Lihat</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-800 border border-pink-200">+ Anggota Artmedia</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Lihat</span>
                                </td>
                            </tr>

                            {{-- 6. VISI & MISI ORGANISASI --}}
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-pink-100 text-pink-800 font-bold flex items-center justify-center text-xs shrink-0">🎯</span>
                                        <div>
                                            <div class="text-xs font-black">Visi & Misi</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Edit visi, misi, tagline & periode</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-sm">★ Full Control</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Full Control</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Full Control</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                            {{-- 7. KOTAK SUARA SISWA --}}
                            <tr class="hover:bg-slate-50/80 transition bg-slate-50/20">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-cyan-100 text-cyan-800 font-bold flex items-center justify-center text-xs shrink-0">💬</span>
                                        <div>
                                            <div class="text-xs font-black">Kotak Suara Siswa</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Tanggapan aspirasi & export PDF</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Monitor</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Monitor</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white shadow-sm">★ Export PDF</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-500 text-white shadow-sm">★ Tanggapi</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">✓ Moderasi</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                            {{-- 8. BERITA & ARTIKEL PUBLIKASI --}}
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-800 font-bold flex items-center justify-center text-xs shrink-0">📰</span>
                                        <div>
                                            <div class="text-xs font-black">Berita & Kegiatan</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Buat artikel, edit & publish</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Publish</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Publish</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Publish</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-500 text-white shadow-sm">★ Tulis & Publish</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Baca</span>
                                </td>
                            </tr>

                            {{-- 9. ARSIP FOTO & DOKUMENTASI --}}
                            <tr class="hover:bg-slate-50/80 transition bg-slate-50/20">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-violet-100 text-violet-800 font-bold flex items-center justify-center text-xs shrink-0">📸</span>
                                        <div>
                                            <div class="text-xs font-black">Arsip Kenangan Foto</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Upload dokumentasi acara</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Upload/Hapus</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Upload/Hapus</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Upload/Hapus</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Galeri</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-100 text-cyan-800 border border-cyan-200">✓ Upload</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">✓ Upload</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Galeri</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Galeri</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Galeri</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">⊙ Galeri</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-pink-100 text-pink-800 border border-pink-200">✓ Upload</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-pink-50 text-pink-700 border border-pink-200">✓ Upload</span>
                                </td>
                            </tr>

                            {{-- 10. PRESENSI SCANNER QR EVENT --}}
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 font-bold flex items-center justify-center text-xs shrink-0">📲</span>
                                        <div>
                                            <div class="text-xs font-black">Scanner QR Presensi</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Petugas scan QR tiket masuk</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Scanner</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Scanner</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Scanner</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-100 text-cyan-800 border border-cyan-200">✓ Scanner</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">✓ Scanner</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                            {{-- 11. LOGBOOK AKTIVITAS & AUDIT TRAIL --}}
                            <tr class="hover:bg-slate-50/80 transition bg-slate-50/20">
                                <td class="py-3.5 px-4 font-bold text-slate-900 sticky left-0 bg-white z-20 shadow-[3px_0_8px_rgba(0,0,0,0.04)]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-red-100 text-red-800 font-bold flex items-center justify-center text-xs shrink-0">🛡️</span>
                                        <div>
                                            <div class="text-xs font-black">Logbook & Audit Trail</div>
                                            <div class="text-[10px] text-slate-400 font-normal">Audit riwayat aktivitas sistem</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-sm">★ Full Audit</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">✓ Full Audit</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100 bg-slate-50/40">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                                <td class="py-3.5 px-3 text-center border-l border-slate-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">✕ Terkunci</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- Legend & Keterangan Simbol --}}
                <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-600">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shrink-0"></span>
                            <span class="font-bold text-slate-800">Full Access / Approval</span>
                            <span class="text-slate-400 font-normal">(Hak eksekusi penuh)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-amber-400 shadow-sm shrink-0"></span>
                            <span class="font-bold text-slate-800">Akses Terbatas / Parsial</span>
                            <span class="text-slate-400 font-normal">(Khusus divisi / read-only)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-slate-300 shadow-sm shrink-0"></span>
                            <span class="font-bold text-slate-800">Terkunci</span>
                            <span class="text-slate-400 font-normal">(Otoritas ditolak server 403)</span>
                        </div>
                    </div>
                    <div class="text-[11px] text-slate-400 font-semibold italic">
                        * Seluruh aturan hak akses telah diverifikasi dan diproteksi di level Controller & Middleware Laravel.
                    </div>
                </div>
            </div>

            {{-- ================= GRID KREDENSIAL AKUN RESMI ORGANISASI ================= --}}
            <div class="liquid-glass-card rounded-[2.5rem] p-6 sm:p-10 border border-white shadow-xl">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200/70">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-black text-slate-800 uppercase tracking-wider mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                            <span>Referensi Akun & Kredensial Resmi</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Daftar Akun Pengurus Resmi yang Dikonfigurasi
                        </h2>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                        7 Akun Terdaftar
                    </span>
                </div>

                @php
                    $roleAccounts = [
                        [
                            'role_name' => 'Ketua OSIS',
                            'email' => '15240006@nusamandiri.ac.id',
                            'role_code' => 'ketua',
                            'badge' => 'Executive Leader',
                            'badge_color' => 'bg-rose-100 text-rose-800 border-rose-300',
                            'level' => 'Tingkat 1 - Otoritas Tertinggi',
                            'desc' => 'Persetujuan akhir kas, proposal, struktur organisasi, update link WA, dan audit log sistem.'
                        ],
                        [
                            'role_name' => 'Wakil Ketua OSIS',
                            'email' => '15240007@nusamandiri.ac.id',
                            'role_code' => 'wakil',
                            'badge' => 'Co-Executive',
                            'badge_color' => 'bg-red-100 text-red-800 border-red-300',
                            'level' => 'Tingkat 1 - Wakil Pimpinan',
                            'desc' => 'Monitoring seluruh divisi, evaluasi program kerja, co-approver, dan update link WA.'
                        ],
                        [
                            'role_name' => 'Sekretaris',
                            'email' => 'guntur@surawijaya.com',
                            'role_code' => 'sekretaris',
                            'badge' => 'Administrasi & Arsip',
                            'badge_color' => 'bg-amber-100 text-amber-800 border-amber-300',
                            'level' => 'Tingkat 2 - Biro Administrasi',
                            'desc' => 'Validasi dan approve LPJ kegiatan, update link WA pendaftaran, dan export PDF aspirasi.'
                        ],
                        [
                            'role_name' => 'Bendahara',
                            'email' => '15240002@nusamandiri.ac.id',
                            'role_code' => 'bendahara',
                            'badge' => 'Keuangan & Kas',
                            'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            'level' => 'Tingkat 2 - Biro Keuangan',
                            'desc' => 'Input, edit, dan pembukuan arus kas, transaksi masuk, dan pencairan dana.'
                        ],
                        [
                            'role_name' => 'Kadiv Humas',
                            'email' => '15240008@nusamandiri.ac.id',
                            'role_code' => 'ketua_divisi_humas',
                            'badge' => 'Hubungan Masyarakat',
                            'badge_color' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
                            'level' => 'Tingkat 3 - Pimpinan Divisi',
                            'desc' => 'Update link WA calon, purpose proposal humas, tanggapi aspirasi, dan publikasi berita.'
                        ],
                        [
                            'role_name' => 'Anggota Humas',
                            'email' => 'anggota@osisbekasi.com',
                            'role_code' => 'anggota_divisi_humas',
                            'badge' => 'Staff Operasional',
                            'badge_color' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'level' => 'Tingkat 4 - Pelaksana Teknis',
                            'desc' => 'Petugas scanner QR presensi, draft laporan kegiatan, dan operasional lapangan.'
                        ],
                        [
                            'role_name' => 'Kadiv Agama',
                            'email' => 'surya@abdi.com',
                            'role_code' => 'ketua_divisi_agama',
                            'badge' => 'Kerohanian & Agama',
                            'badge_color' => 'bg-purple-100 text-purple-800 border-purple-300',
                            'level' => 'Tingkat 3 - Pimpinan Divisi',
                            'desc' => 'Purpose program kerja kerohanian, kelola anggota divisi agama, dan data calon OSIS.'
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($roleAccounts as $acc)
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/90 hover:border-rose-400 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full border {{ $acc['badge_color'] }}">
                                        {{ $acc['badge'] }}
                                    </span>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 group-hover:text-rose-600 transition">
                                        {{ $acc['role_code'] }}
                                    </span>
                                </div>
                                <div class="font-black text-slate-900 text-sm">
                                    {{ $acc['role_name'] }}
                                </div>
                                <div class="mt-1 flex items-center gap-1.5 text-xs text-rose-600 font-bold truncate" title="{{ $acc['email'] }}">
                                    <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="truncate font-mono">{{ $acc['email'] }}</span>
                                </div>
                                <p class="mt-2 text-[11px] text-slate-500 leading-snug">
                                    {{ $acc['desc'] }}
                                </p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px]">
                                <span class="text-slate-400 font-semibold">{{ $acc['level'] }}</span>
                                <span class="font-mono text-rose-700 font-bold bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200/60">PW: osistmj1</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</body>
</html>
