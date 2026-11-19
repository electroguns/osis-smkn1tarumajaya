<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logbook Aktivitas Pengurus | OSIS SMKN 1 Tarumajaya</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS & SweetAlert --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        }

        /* Glassmorphism Styles Ala Apple Liquid Glass */
        .liquid-glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(28px) saturate(190%);
            -webkit-backdrop-filter: blur(28px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 50px -15px rgba(95, 19, 25, 0.08), 0 0 0 1px rgba(255, 255, 255, 0.7) inset;
        }

        .liquid-glass-pill {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(244, 63, 94, 0.2);
            box-shadow: 0 4px 15px -3px rgba(225, 29, 72, 0.12);
        }

        .liquid-specular {
            position: absolute;
            top: 0;
            left: 5%;
            right: 5%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.9) 50%, transparent 100%);
            pointer-events: none;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-100/60 via-slate-50 to-slate-100 bg-fixed text-slate-800 antialiased overflow-x-hidden">

    {{-- Komponen Sidebar Navigasi Dini --}}
    @include('dini')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-20 md:pt-10">

        {{-- Breadcrumb & Back Button --}}
        <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
            <div class="flex items-center space-x-2">
                <a href="{{ url('/') }}" aria-label="Dashboard Utama"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/90 hover:bg-white text-rose-700 text-xs font-bold shadow-sm border border-rose-200/80 transition-all hover:scale-105 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Dashboard Utama</span>
                </a>
                <span class="text-slate-400 text-xs">•</span>
                <span class="text-xs font-bold text-rose-800 bg-rose-100/90 border border-rose-200/60 px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Logbook Audit Pengurus</span>
                </span>
            </div>

            @if(auth()->user()->role === 'ketua')
                <div class="flex items-center gap-2">
                    <button type="button" onclick="confirmClearLogs()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 shadow-sm transition-all active:scale-95">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span>Bersihkan Riwayat Logbook</span>
                    </button>
                    <form id="clear-logs-form" action="{{ route('logbook.clear') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="scope" id="clear-scope-input" value="all">
                    </form>
                </div>
            @endif
        </div>

        {{-- Header Section --}}
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-700 text-xs font-black uppercase tracking-wider mb-3">
                <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                <span>Otomatisasi Audit Trail & Monitoring OSIS</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                Logbook Aktivitas <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 via-rose-700 to-[#5f1319]">Pengurus</span>
            </h1>
            <p class="mt-3 text-sm sm:text-base text-slate-600 max-w-3xl leading-relaxed">
                Pantau seluruh rekam jejak aksi pengurus secara otomatis: siapa yang menambah, menyunting, menghapus, atau memberikan persetujuan, lengkap dengan nama pengurus, jabatan role, waktu, serta rincian perubahannya.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: {!! json_encode(session('success')) !!},
                        background: '#5f1319',
                        color: '#f8fafc',
                        confirmButtonColor: '#0ea5e9'
                    });
                });
            </script>
        @endif

        {{-- 4 Kartu Metrik Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
            {{-- Card 1: Total Aktivitas --}}
            <div class="liquid-glass-card relative overflow-hidden rounded-2xl p-5 border border-white/80 shadow-md">
                <div class="liquid-specular"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Aktivitas</p>
                        <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalActivities, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center font-bold">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-3 font-medium flex items-center gap-1">
                    <span class="text-emerald-600 font-bold">✓ Terdata</span> seluruh riwayat aksi
                </p>
            </div>

            {{-- Card 2: Hari Ini --}}
            <div class="liquid-glass-card relative overflow-hidden rounded-2xl p-5 border border-white/80 shadow-md">
                <div class="liquid-specular"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Aksi Hari Ini</p>
                        <h3 class="text-2xl font-black text-rose-700 mt-1">{{ number_format($todayActivities, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-3 font-medium flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Monitoring aksi hari ini</span>
                </p>
            </div>

            {{-- Card 3: 7 Hari Terakhir --}}
            <div class="liquid-glass-card relative overflow-hidden rounded-2xl p-5 border border-white/80 shadow-md">
                <div class="liquid-specular"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">7 Hari Terakhir</p>
                        <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($weekActivities, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 flex items-center justify-center font-bold">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-3 font-medium">
                    Total aktivitas mingguan pengurus
                </p>
            </div>

            {{-- Card 4: Pengurus Teraktif --}}
            <div class="liquid-glass-card relative overflow-hidden rounded-2xl p-5 border border-white/80 shadow-md">
                <div class="liquid-specular"></div>
                <div class="flex items-center justify-between">
                    <div class="truncate pr-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengurus Teraktif</p>
                        <h3 class="text-lg font-black text-slate-900 mt-1 truncate" title="{{ $topActor->user_name ?? '-' }}">
                            {{ $topActor->user_name ?? 'Belum ada data' }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold shrink-0">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-3 font-medium truncate">
                    @if($topActor)
                        <span class="font-bold text-rose-700">{{ $roleLabels[$topActor->user_role] ?? $topActor->user_role }}</span> ({{ $topActor->total }} aksi)
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <div class="bg-white/80 rounded-3xl p-6 mb-8 border border-white/80 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.03)] backdrop-blur-xl">
            <form action="{{ route('logbook.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    {{-- Search Input --}}
                    <div class="lg:col-span-2 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 pointer-events-none">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari nama pengurus, deskripsi, modul, atau IP..."
                            class="w-full pl-11 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition h-[46px]">
                    </div>

                    {{-- Filter Modul --}}
                    <div>
                        <select name="module" onchange="this.form.submit()"
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold h-[46px] transition">
                            <option value="all">Semua Modul</option>
                            @foreach($modulesList as $mod)
                                <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>
                                    {{ $mod }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Aksi --}}
                    <div>
                        <select name="action" onchange="this.form.submit()"
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold h-[46px] transition">
                            <option value="all">Semua Aksi</option>
                            <option value="TAMBAH" {{ request('action') === 'TAMBAH' ? 'selected' : '' }}>Tambah Data</option>
                            <option value="UBAH" {{ request('action') === 'UBAH' ? 'selected' : '' }}>Ubah Data</option>
                            <option value="HAPUS" {{ request('action') === 'HAPUS' ? 'selected' : '' }}>Hapus Data</option>
                            <option value="SETUJUI" {{ request('action') === 'SETUJUI' ? 'selected' : '' }}>Persetujuan</option>
                            <option value="TOLAK" {{ request('action') === 'TOLAK' ? 'selected' : '' }}>Penolakan</option>
                            <option value="STATUS" {{ request('action') === 'STATUS' ? 'selected' : '' }}>Ubah Status</option>
                            <option value="SIARAN" {{ request('action') === 'SIARAN' ? 'selected' : '' }}>Siaran Notifikasi</option>
                        </select>
                    </div>

                    {{-- Filter Role --}}
                    <div>
                        <select name="role" onchange="this.form.submit()"
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold h-[46px] transition">
                            <option value="all">Semua Role</option>
                            @foreach($roleLabels as $roleKey => $roleName)
                                <option value="{{ $roleKey }}" {{ request('role') === $roleKey ? 'selected' : '' }}>
                                    {{ $roleName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Filter Periode & Action Buttons --}}
                <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-200/60 flex-wrap">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Periode:</span>
                        <a href="{{ request()->fullUrlWithQuery(['period' => null]) }}"
                            class="px-3 py-1 rounded-lg text-xs font-bold {{ !request('period') ? 'bg-[#5f1319] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">
                            Semua
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => 'today']) }}"
                            class="px-3 py-1 rounded-lg text-xs font-bold {{ request('period') === 'today' ? 'bg-[#5f1319] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">
                            Hari Ini
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => '7_days']) }}"
                            class="px-3 py-1 rounded-lg text-xs font-bold {{ request('period') === '7_days' ? 'bg-[#5f1319] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">
                            7 Hari
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => '30_days']) }}"
                            class="px-3 py-1 rounded-lg text-xs font-bold {{ request('period') === '30_days' ? 'bg-[#5f1319] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">
                            30 Hari
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(request()->hasAny(['q', 'module', 'action', 'role', 'period']))
                            <a href="{{ route('logbook.index') }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Reset Filter</span>
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-[#5f1319] hover:bg-[#4d0f14] text-white text-xs font-bold shadow-md transition active:scale-95">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <span>Terapkan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Logbook --}}
        <div class="liquid-glass-card rounded-2xl overflow-hidden shadow-lg border border-white/80">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/70 text-slate-500 text-xs font-black uppercase tracking-wider">
                            <th class="px-5 py-4 w-36">Waktu</th>
                            <th class="px-5 py-4 w-60">Pengurus (Pelaku)</th>
                            <th class="px-5 py-4 w-40">Role / Jabatan</th>
                            <th class="px-5 py-4 w-36">Modul</th>
                            <th class="px-5 py-4 w-36 text-center">Aksi</th>
                            <th class="px-6 py-4">Deskripsi Perubahan</th>
                            <th class="px-4 py-4 w-20 text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($logs as $log)
                            @php
                                $badge = $log->action_badge_classes;
                                $roleKey = $log->user_role ?? ($log->user ? $log->user->role : 'guest');
                            @endphp
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                {{-- Waktu --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-xs">
                                            {{ $log->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 font-medium">
                                            {{ $log->created_at->format('H:i:s') }} WIB
                                        </span>
                                        <span class="text-[10px] text-rose-600 font-bold mt-0.5">
                                            {{ $log->time_ago }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Pengurus Pelaku --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-rose-500 to-[#5f1319] text-white flex items-center justify-center font-black text-xs shadow-sm shrink-0">
                                            {{ strtoupper(substr($log->user_name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div class="truncate max-w-[190px]">
                                            <p class="font-bold text-slate-900 text-sm truncate" title="{{ $log->user_name }}">
                                                {{ $log->user_name }}
                                            </p>
                                            @if($log->user && $log->user->email)
                                                <p class="text-[11px] text-slate-400 truncate">
                                                    {{ $log->user->email }}
                                                </p>
                                            @else
                                                <p class="text-[11px] text-slate-400">
                                                    {{ $log->ip_address ?? 'Sistem' }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Role Pengurus --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold
                                        {{ in_array($roleKey, ['ketua', 'wakil']) ? 'bg-rose-100 text-rose-800 border border-rose-200' : '' }}
                                        {{ $roleKey === 'bendahara' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : '' }}
                                        {{ $roleKey === 'sekretaris' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : '' }}
                                        {{ str_contains($roleKey, 'ketua_divisi_') ? 'bg-sky-100 text-sky-800 border border-sky-200' : '' }}
                                        {{ str_contains($roleKey, 'anggota_divisi_') ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}
                                        {{ !in_array($roleKey, ['ketua', 'wakil', 'bendahara', 'sekretaris']) && !str_contains($roleKey, '_divisi_') ? 'bg-slate-100 text-slate-600' : '' }}
                                    ">
                                        {{ $log->role_label }}
                                    </span>
                                </td>

                                {{-- Modul --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-xs text-slate-700 bg-slate-100/80 px-2.5 py-1 rounded-lg border border-slate-200/60">
                                        <span>{{ $log->module }}</span>
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border shadow-xs {{ $badge['bg'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                        <span>{{ $badge['label'] }}</span>
                                    </span>
                                </td>

                                {{-- Deskripsi Perubahan --}}
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800 text-xs sm:text-sm leading-relaxed">
                                        {{ $log->description }}
                                    </p>
                                    @if(!empty($log->properties['changes']))
                                        <div class="mt-1 flex items-center gap-1 text-[11px] text-slate-400">
                                            <span>Kolom diperbarui:</span>
                                            <span class="font-mono text-rose-700 font-bold">
                                                {{ implode(', ', array_keys($log->properties['changes'])) }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Tombol Detail --}}
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <button type="button" onclick="showLogDetail({{ $log->id }})"
                                        class="p-2 text-rose-600 hover:text-white hover:bg-[#5f1319] rounded-xl transition-all shadow-xs border border-rose-200/70"
                                        title="Lihat Detail Snapshot & IP">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center">
                                            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </div>
                                        <p class="font-bold text-slate-700 text-base">Belum Ada Rekaman Logbook</p>
                                        <p class="text-xs text-slate-400 max-w-sm">
                                            Tidak ditemukan aktivitas yang sesuai dengan kriteria filter pencarian Anda.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/60">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL DETAIL LOGBOOK (Apple Liquid Glass) --}}
    <div id="logDetailModal" class="fixed inset-0 z-[120] hidden bg-slate-900/60 backdrop-blur-md items-center justify-center p-4">
        <div class="liquid-glass-card w-full max-w-2xl rounded-3xl shadow-2xl border border-white/90 overflow-hidden transform scale-95 opacity-0 transition-all duration-300"
            id="logModalContent">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-rose-600 via-rose-700 to-[#5f1319] px-6 py-5 flex items-center justify-between text-white relative">
                <div class="liquid-specular"></div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold backdrop-blur-sm">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg leading-tight">Detail Log Aktivitas</h3>
                        <p class="text-xs text-rose-200 mt-0.5" id="modal-log-id">Log #--</p>
                    </div>
                </div>
                <button type="button" onclick="closeLogModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    &times;
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                {{-- Info Utama --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Pengurus (Pelaku)</span>
                        <p class="text-sm font-black text-slate-900" id="modal-user-name">-</p>
                        <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700" id="modal-user-role">-</span>
                    </div>

                    <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Waktu Kejadian</span>
                        <p class="text-sm font-bold text-slate-900" id="modal-created-at">-</p>
                        <span class="text-xs text-slate-500 font-medium" id="modal-time-ago">-</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Modul & Aksi</span>
                        <p class="text-sm font-bold text-slate-900" id="modal-module">-</p>
                        <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold" id="modal-action">-</span>
                    </div>

                    <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Alamat IP</span>
                        <p class="text-sm font-mono font-bold text-slate-900" id="modal-ip">-</p>
                    </div>
                </div>

                {{-- Deskripsi Aksi --}}
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">Deskripsi Lengkap</span>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/60 text-sm text-slate-800 leading-relaxed font-medium" id="modal-description">
                        -
                    </div>
                </div>

                {{-- User Agent --}}
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">Browser / Perangkat</span>
                    <p class="text-xs font-mono text-slate-600 p-3 bg-slate-50 rounded-xl border border-slate-200/60 break-all" id="modal-user-agent">
                        -
                    </p>
                </div>

                {{-- Snapshot Perubahan JSON --}}
                <div id="modal-properties-container" class="hidden">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">Rincian Perubahan (Data Snapshot)</span>
                    <pre class="text-xs font-mono text-emerald-800 p-4 bg-emerald-50/70 rounded-2xl border border-emerald-200/80 overflow-x-auto max-h-56" id="modal-properties"></pre>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-200/60 flex justify-end">
                <button type="button" onclick="closeLogModal()"
                    class="px-5 py-2 text-sm font-bold bg-[#5f1319] hover:bg-[#4d0f14] text-white rounded-xl shadow-md transition active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Script Interaksi Modal & Detail --}}
    <script>
        function showLogDetail(id) {
            const modal = document.getElementById('logDetailModal');
            const content = document.getElementById('logModalContent');

            // Reset
            document.getElementById('modal-log-id').textContent = 'Memuat...';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            fetch(`/admin/logbook/${id}/detail`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modal-log-id').textContent = `Log #${data.id}`;
                    document.getElementById('modal-user-name').textContent = data.user_name || 'Sistem / Tamu';
                    document.getElementById('modal-user-role').textContent = data.role_label || data.user_role;
                    document.getElementById('modal-created-at').textContent = data.created_at;
                    document.getElementById('modal-time-ago').textContent = data.time_ago;
                    document.getElementById('modal-module').textContent = data.module;
                    
                    const actionBadge = document.getElementById('modal-action');
                    actionBadge.textContent = data.action_badge.label;
                    actionBadge.className = `inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border ${data.action_badge.bg}`;

                    document.getElementById('modal-ip').textContent = data.ip_address || 'Tidak terekam';
                    document.getElementById('modal-description').textContent = data.description;
                    document.getElementById('modal-user-agent').textContent = data.user_agent || 'Tidak terekam';

                    const propContainer = document.getElementById('modal-properties-container');
                    const propPre = document.getElementById('modal-properties');

                    if (data.properties && Object.keys(data.properties).length > 0) {
                        propPre.textContent = JSON.stringify(data.properties, null, 2);
                        propContainer.classList.remove('hidden');
                    } else {
                        propContainer.classList.add('hidden');
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('modal-description').textContent = 'Gagal memuat rincian aktivitas.';
                });
        }

        function closeLogModal() {
            const modal = document.getElementById('logDetailModal');
            const content = document.getElementById('logModalContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        function confirmClearLogs() {
            Swal.fire({
                title: 'Bersihkan Riwayat Logbook',
                html: '<p class="text-xs text-slate-500 mb-4 leading-relaxed">Pilih cakupan data yang ingin dibersihkan dari catatan logbook aktivitas pengurus:</p>',
                input: 'radio',
                inputOptions: {
                    'all': '🔥 Hapus Semua Riwayat (Reset Total dari Nol)',
                    '7': '⏳ Hapus Catatan Lebih dari 7 Hari Lalu',
                    '30': '📅 Hapus Catatan Lebih dari 30 Hari Lalu'
                },
                inputValue: 'all',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Silakan pilih salah satu opsi pembersihan!';
                    }
                },
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#be123c',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Lanjutkan Bersihkan',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                color: '#1e293b'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    document.getElementById('clear-scope-input').value = result.value;
                    document.getElementById('clear-logs-form').submit();
                }
            });
        }

        // Close on backdrop click
        document.getElementById('logDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogModal();
            }
        });
    </script>
</body>
</html>

