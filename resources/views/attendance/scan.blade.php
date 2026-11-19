<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kamera Scanner Presensi - {{ $event->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
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
    class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased flex flex-col justify-between">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <header class="sticky top-0 z-40 px-6 py-4 bg-white/80 backdrop-blur-xl border-b border-rose-200 shadow-sm pl-28">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('event.index') }}"
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-rose-100 hover:bg-rose-200 text-rose-600 transition-colors duration-200 border border-rose-200 focus:outline-none shadow-sm"
                    title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>

                <div>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">
                        Kategori: {{ ucfirst($event->category) }}
                    </span>
                    <h1 class="text-lg font-bold text-slate-800 mt-1">
                        Presensi: {{ $event->title }}
                    </h1>
                </div>
            </div>

            <div class="text-right text-xs">
                @php
                    $eventStart = \Carbon\Carbon::parse($event->event_date . ' ' . $event->start_time);
                    $toleransiMenit = (int) ($event->batas_waktu_absen ?? 15);
                    $cutoffTepatWaktu = $eventStart->copy()->addMinutes($toleransiMenit);
                @endphp
                <p class="text-slate-500 font-medium">
                    Mulai: <span class="font-bold text-slate-700">{{ $eventStart->format('H:i') }} WIB</span>
                </p>
                <p class="text-slate-500">
                    Toleransi Hadir:
                    <span class="font-semibold text-emerald-600">
                        s/d {{ $cutoffTepatWaktu->format('H:i') }} WIB ({{ $toleransiMenit }} mnt)
                    </span>
                </p>
            </div>
        </div>
    </header>

    <main class="max-w-6xl w-full mx-auto p-6 flex-1 flex flex-col lg:flex-row gap-6">
        <div class="flex-1">
            <div class="bg-[#571319] border border-rose-800 rounded-3xl p-6 shadow-2xl relative overflow-hidden">

                @if ($event->is_geofencing)
                    <div id="location-status"
                        class="mb-4 text-center text-xs py-2 px-4 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        📍 Kunci Lokasi Aktif: Memeriksa posisi GPS Anda di area sekolah...
                    </div>
                @endif

                <div id="reader"
                    class="relative w-full aspect-square bg-white rounded-3xl overflow-hidden border border-rose-700/50">
                    <div
                        class="absolute inset-0 pointer-events-none border-[40px] md:border-[50px] border-[#571319]/80 flex items-center justify-center rounded-3xl">
                        <div class="w-full h-full border-2 border-dashed border-rose-400/40 rounded-2xl">
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-sm text-white">Arahkan QR Code Kartu Pengurus OSIS tepat ke dalam kotak kamera
                        scanner.</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-80">
            <div class="bg-[#571319]/50 border border-rose-800/60 rounded-2xl p-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white mb-2">Scan Terakhir:</h4>
                <div id="scan-logs" class="text-sm text-white py-1">
                    @if ($lastAttendance)
                        <div
                            class="flex justify-between items-center bg-[#0f172a] p-2 rounded-xl border border-rose-800">
                            <span class="truncate max-w-[150px]">{{ $lastAttendance->user->name }}</span>
                            <span
                                class="{{ $lastAttendance->status === 'hadir' ? 'text-emerald-400' : 'text-amber-400' }} font-bold">
                                {{ strtoupper($lastAttendance->status) }}
                            </span>
                        </div>
                    @else
                        <div class="text-white italic text-center py-1">Belum ada aktivitas scan.</div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <audio id="beep-sound" src="{{ asset('mixkit-censorship-beep-1082.wav') }}" preload="auto"></audio>

    <script>
        window.appConfig = {
            isGeofencingActive: @json($event->is_geofencing),
            eventId: @json($event->id),
            attendanceRoute: @json(route('attendance.store', $event->id))
        };
    </script>
    <script src="{{asset('js/scan.min.js')}}"></script>
</body>

</html>