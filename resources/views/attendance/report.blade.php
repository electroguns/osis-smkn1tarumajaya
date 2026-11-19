<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran - {{ $event->title }}</title>
        @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div class="max-w-6xl mx-auto px-4 md:px-6 py-8 md:py-12 pt-20 md:pt-12">

        {{-- Kembali Link --}}
        <a href="{{ route('event.index') }}"
            class="text-sm font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-2 mb-6 group w-fit transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        {{-- Header Section --}}
        <div class="mb-8 md:mb-12 space-y-3">
            <div
                class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider">
                <span>Kategori: {{ $event->category }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Rekap Absensi: <span class="text-rose-600">{{ $event->title }}</span>
            </h1>

            <p class="text-slate-500 text-sm md:text-base font-medium flex flex-wrap items-center gap-x-2 gap-y-1">
                <span>📅 Pelaksanaan: <b>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</b></span>
                <span class="text-slate-300 hidden md:inline">•</span>
                <span class="font-mono bg-slate-200/50 text-slate-700 px-2 py-0.5 rounded-md text-xs font-bold">
                    🕒 {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} WIB
                </span>
            </p>
        </div>

        {{-- Grid Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 md:mb-12">
            <div
                class="rounded-2xl border border-white/60 bg-white/70 p-5 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.02)] backdrop-blur-xl">
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Pengurus</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ $totalPengurus }}</p>
            </div>

            <div
                class="rounded-2xl border border-white/60 bg-white/70 p-5 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.02)] backdrop-blur-xl border-l-4 border-l-emerald-500">
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-600">Hadir</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ $totalHadir }}</p>
            </div>

            <div
                class="rounded-2xl border border-white/60 bg-white/70 p-5 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.02)] backdrop-blur-xl border-l-4 border-l-amber-500">
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-amber-600">Terlambat</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ $totalTerlambat }}</p>
            </div>

            <div
                class="rounded-2xl border border-white/60 bg-white/70 p-5 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.02)] backdrop-blur-xl border-l-4 border-l-rose-500">
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-rose-600">Alpa</p>
                <p class="text-3xl font-black text-slate-800 mt-2">{{ $totalAlpa }}</p>
            </div>
        </div>

        {{-- Search Form --}}
        <x-search-form :action="route('attendance.report', $event->id)" placeholder="Cari absensi..." />

        {{-- --- MODE MOBILE (CARD VIEW) --- --}}
        <div class="block md:hidden space-y-4">
            @foreach ($users as $user)
                @php
                    $absen = $attendances->get($user->id);
                @endphp
                <div
                    class="rounded-3xl border border-white/60 bg-white/70 p-5 shadow-[0_16px_32px_-8px_rgba(0,0,0,0.02)] backdrop-blur-xl">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <h2 class="font-bold text-lg text-slate-800 tracking-tight leading-snug">
                                {{ $user->name }}
                            </h2>
                        </div>

                        <div>
                            @if ($absen)
                                @if (strtolower($absen->status) === 'hadir')
                                    <span
                                        class="inline-block bg-emerald-500/10 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">
                                        🟢 HADIR
                                    </span>
                                @else
                                    <span
                                        title="{{ $absen->notes ?? '' }}"
                                        class="inline-block bg-amber-500/10 text-amber-700 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">
                                        🟡 TERLAMBAT
                                    </span>
                                    @if (!empty($absen->notes) && $absen->notes !== 'Hadir tepat waktu')
                                        <div class="text-[10px] text-amber-600 font-medium mt-1">{{ $absen->notes }}</div>
                                    @endif
                                @endif
                            @else
                                <span
                                    class="inline-block bg-rose-500/10 text-rose-700 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">
                                    🔴 ALPA
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="my-3 border-t border-slate-100"></div>

                    <div
                        class="flex justify-between items-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <span>Waktu Scan</span>
                        <span class="font-mono font-bold text-slate-600 normal-case">
                            @if ($absen)
                                🕒 {{ \Carbon\Carbon::parse($absen->created_at)->format('H:i:s') }} WIB
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- --- MODE DESKTOP (TABLE VIEW) --- --}}
        <div
            class="hidden md:block relative overflow-hidden rounded-[32px] border border-white/60 bg-white/70 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Nama Pengurus</th>
                            <th class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400">
                                Waktu Scan</th>
                            <th
                                class="px-6 py-6 text-[11px] font-extrabold uppercase tracking-[0.1em] text-slate-400 text-center w-44">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($users as $user)
                            @php
                                $absen = $attendances->get($user->id);
                            @endphp
                            <tr class="group hover:bg-rose-50/50 transition-colors duration-200">
                                <td class="px-6 py-5 text-sm font-bold text-slate-800">{{ $user->name }}</td>
                                <td class="px-6 py-5 text-xs font-mono text-slate-600 font-semibold">
                                    @if ($absen)
                                        {{ \Carbon\Carbon::parse($absen->created_at)->format('H:i:s') }} WIB
                                    @else
                                        <span class="text-slate-400 font-sans font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if ($absen)
                                        @if (strtolower($absen->status) === 'hadir')
                                            <span
                                                class="inline-block bg-emerald-500/10 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">
                                                🟢 HADIR
                                            </span>
                                        @else
                                            <span
                                                title="{{ $absen->notes ?? '' }}"
                                                class="inline-block bg-amber-500/10 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">
                                                🟡 TERLAMBAT
                                            </span>
                                            @if(!empty($absen->notes))
                                                <div class="text-[10px] text-amber-600 mt-1 font-medium">{{ $absen->notes }}</div>
                                            @endif
                                        @endif
                                    @else
                                        <span
                                            class="inline-block bg-rose-500/10 text-rose-700 px-3 py-1 rounded-full text-xs font-bold">
                                            🔴 ALPA
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Total Data --}}
        <p class="mt-8 text-center text-slate-400 text-sm italic">
            Data rekap absensi diekstrak secara realtime
        </p>
        {{-- Pagination --}}
        <div class="mt-12">
            {{ $users->links() }}
        </div>
    </div>
</body>

</html>
