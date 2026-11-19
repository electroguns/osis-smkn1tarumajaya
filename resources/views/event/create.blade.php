<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event Baru - OSIS SMKN 1 Tarumajaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .input-focus-effect:focus {
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1);
            border-color: #571319;
        }

        .custom-time-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .custom-time-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 4px;
        }
        .custom-time-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-time-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#e11d48'
            });
        </script>
    @endif

    <div class="max-w-3xl mx-auto px-6 py-12 pt-20 md:pt-12">
        <div class="mb-10">
            <a href="{{ route('event.index') }}" aria-label="Manajemen Pengurus OSIS"
                class="inline-flex items-center text-sm font-bold text-rose-600 hover:text-rose-700 transition gap-2 mb-2 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            <h1 class="text-4xl font-black tracking-tight text-slate-900">
                Buat <span class="text-rose-600">Event</span> Baru
            </h1>
            <p class="text-slate-500 mt-2">
                Isi formulir untuk membuka sistem absensi QR Code otomatis.
            </p>
        </div>

        <div class="bg-white p-8 md:p-10 rounded-[32px] shadow-2xl shadow-slate-200/60 border border-white">

            <form action="{{ route('event.store') }}" method="POST" class="space-y-8">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nama Kegiatan</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        placeholder="Contoh: Rapat Pleno Class Meeting"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:bg-white">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    {{-- Kategori --}}
                    <div class="relative" id="container-category">
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kategori</label>
                        <div class="relative">
                            <input type="text" id="display_category" readonly
                                value="{{ old('category', 'Rapat') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 pr-12 text-slate-900 font-semibold outline-none transition input-focus-effect focus:bg-white cursor-pointer select-none">
                            <input type="hidden" name="category" id="category" value="{{ old('category', 'Rapat') }}">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 transition-transform duration-200" id="category-chevron">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Custom Dropdown Kategori --}}
                        <div id="dropdown-category" class="hidden absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 z-50">
                            <button type="button" data-val="Rapat" class="category-item w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition text-left text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                                <span class="flex items-center gap-2.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                                    Rapat
                                </span>
                                <span class="check-icon text-rose-600 text-sm {{ old('category', 'Rapat') === 'Rapat' ? '' : 'hidden' }}">✓</span>
                            </button>
                            <button type="button" data-val="Program" class="category-item w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition text-left text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                                <span class="flex items-center gap-2.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    Program
                                </span>
                                <span class="check-icon text-rose-600 text-sm {{ old('category') === 'Program' ? '' : 'hidden' }}">✓</span>
                            </button>
                            <button type="button" data-val="Lainnya" class="category-item w-full flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition text-left text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                                <span class="flex items-center gap-2.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                    Lainnya
                                </span>
                                <span class="check-icon text-rose-600 text-sm {{ old('category') === 'Lainnya' ? '' : 'hidden' }}">✓</span>
                            </button>
                        </div>

                        {{-- Opsi Kategori Manual jika memilih 'Lainnya' --}}
                        <div id="container-custom-category" class="{{ old('category') == 'Lainnya' ? '' : 'hidden' }} mt-3 space-y-1.5 transition-all duration-200">
                            <div class="flex items-center justify-between text-xs px-1">
                                <span class="font-bold text-slate-700">Nama Kategori Khusus:</span>
                                <span class="text-slate-400 font-medium italic">(Bisa diisi atau diskip)</span>
                            </div>
                            <div class="relative">
                                <input type="text" name="custom_category" id="custom_category"
                                    value="{{ old('custom_category') }}"
                                    placeholder="Contoh: LDKS, Class Meeting, Bakti Sosial (atau skip)..."
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-3.5 pr-10 text-slate-900 text-sm font-semibold outline-none transition input-focus-effect placeholder:text-slate-400 placeholder:font-normal">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 pl-1">
                                💡 <i>Kategori manual boleh diskip/dikosongkan. Jika diskip, kategori otomatis disimpan sebagai <b>"Lainnya"</b>.</i>
                            </p>
                        </div>
                    </div>

                    {{-- Tanggal Pelaksanaan --}}
                    <div class="relative" id="container-event-date">
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Tanggal Pelaksanaan</label>
                        <div class="relative">
                            <input type="text" id="display_event_date" readonly
                                value="{{ old('event_date') ? \Carbon\Carbon::parse(old('event_date'))->translatedFormat('d F Y') : \Carbon\Carbon::today()->translatedFormat('d F Y') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 pr-12 text-slate-900 font-semibold outline-none transition input-focus-effect focus:bg-white cursor-pointer select-none">
                            <input type="hidden" name="event_date" id="event_date" value="{{ old('event_date', date('Y-m-d')) }}">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Custom Calendar Dropdown Popup --}}
                        <div id="picker-event-date" class="hidden absolute left-0 top-full mt-2 w-full max-w-xs md:max-w-sm bg-white rounded-3xl shadow-2xl border border-slate-100 p-4 z-50">
                            {{-- Header Bulan / Tahun & Navigasi --}}
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <button type="button" id="cal-prev" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Sebelumnya">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <div class="flex items-center gap-1.5" id="cal-header-title">
                                    <button type="button" id="cal-btn-month" class="px-2.5 py-1.5 rounded-xl font-extrabold text-slate-800 hover:bg-slate-100 transition flex items-center gap-1 text-xs tracking-wide">
                                        <span id="cal-label-month">November</span>
                                        <svg id="cal-icon-month" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" id="cal-btn-year" class="px-2.5 py-1.5 rounded-xl font-extrabold text-slate-800 hover:bg-slate-100 transition flex items-center gap-1 text-xs tracking-wide">
                                        <span id="cal-label-year">2026</span>
                                        <svg id="cal-icon-year" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <button type="button" id="cal-next" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Berikutnya">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                            {{-- View 1: Tampilan Hari (Grid Tanggal 1..31) --}}
                            <div id="cal-view-days">
                                {{-- Nama Hari (Min, Sen, Sel, Rab, Kam, Jum, Sab) --}}
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    <span>Min</span>
                                    <span>Sen</span>
                                    <span>Sel</span>
                                    <span>Rab</span>
                                    <span>Kam</span>
                                    <span>Jum</span>
                                    <span>Sab</span>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-xs min-h-[175px]" id="cal-days-grid">
                                    {{-- Diisi oleh JavaScript --}}
                                </div>
                            </div>

                            {{-- View 2: Tampilan Pilihan Bulan (12 Bulan) --}}
                            <div id="cal-view-months" class="hidden">
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-2">Pilih Bulan</div>
                                <div class="grid grid-cols-3 gap-2 py-1 min-h-[175px]" id="cal-months-grid">
                                    {{-- Diisi oleh JavaScript --}}
                                </div>
                            </div>

                            {{-- View 3: Tampilan Pilihan Tahun --}}
                            <div id="cal-view-years" class="hidden">
                                <div class="flex items-center justify-between mb-2 px-1">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pilih / Ketik Tahun</span>
                                    <span class="text-[10px] text-rose-500 font-semibold bg-rose-50 px-2 py-0.5 rounded-full">Bebas</span>
                                </div>
                                <div class="mb-2">
                                    <input type="number" id="cal-input-year" placeholder="Ketik tahun (misal: 2026)..."
                                        class="w-full text-center py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-100">
                                </div>
                                <div class="grid grid-cols-3 gap-2 py-1 max-h-[145px] overflow-y-auto custom-time-scroll pr-1" id="cal-years-grid">
                                    {{-- Diisi oleh JavaScript --}}
                                </div>
                            </div>

                            {{-- Footer Kalender --}}
                            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <button type="button" id="cal-btn-today" class="text-rose-600 font-bold hover:underline">
                                    Hari Ini
                                </button>
                                <button type="button" onclick="closeAllCustomPickers()" class="px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition text-xs shadow-sm">
                                    Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Jam Mulai Acara --}}
                    <div class="relative" id="container-start-time">
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Jam Mulai Acara</label>
                        <div class="relative">
                            <input type="text" id="display_start_time" readonly
                                value="{{ old('start_time') ? old('start_time') . ' WIB' : '07:30 WIB' }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 pr-12 text-slate-900 font-semibold outline-none transition input-focus-effect focus:bg-white cursor-pointer select-none">
                            <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time', '07:30') }}">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Dropdown Picker 24 Jam (Jam, Menit, WIB/WITA/WIT) --}}
                        <div id="picker-start-time" class="hidden absolute left-0 top-full mt-2 w-full max-w-xs md:max-w-sm bg-white rounded-3xl shadow-2xl border border-slate-100 p-4 z-50">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 text-xs">
                                <span class="font-extrabold text-slate-800 tracking-wider uppercase text-[11px]">Format 24 Jam</span>
                                <span class="font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full text-xs" id="preview-start-time">07:30 WIB</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                                <div>
                                    <span class="block font-bold text-slate-400 mb-1.5 uppercase tracking-wider text-[10px]">Jam (24h)</span>
                                    <div class="h-44 overflow-y-auto space-y-1 pr-1 custom-time-scroll" id="hours-start-time"></div>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-400 mb-1.5 uppercase tracking-wider text-[10px]">Menit</span>
                                    <div class="h-44 overflow-y-auto space-y-1 pr-1 custom-time-scroll" id="minutes-start-time"></div>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-400 mb-1.5 uppercase tracking-wider text-[10px]">Zona Waktu</span>
                                    <div class="h-44 space-y-2 pt-1" id="zone-start-time">
                                        <button type="button" data-val="WIB" class="zone-btn w-full py-2.5 rounded-xl font-bold transition text-xs bg-rose-600 text-white shadow-sm">WIB</button>
                                        <button type="button" data-val="WITA" class="zone-btn w-full py-2.5 rounded-xl font-bold transition text-xs bg-slate-50 text-slate-700 hover:bg-slate-100">WITA</button>
                                        <button type="button" data-val="WIT" class="zone-btn w-full py-2.5 rounded-xl font-bold transition text-xs bg-slate-50 text-slate-700 hover:bg-slate-100">WIT</button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 italic">Klik jam di atas</span>
                                <button type="button" onclick="closeAllTimePickers()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                    Selesai
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Jam Selesai Acara --}}
                    <div class="relative" id="container-end-time">
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Jam Selesai Acara</label>
                        <div class="relative">
                            <input type="text" id="display_end_time" readonly
                                value="{{ old('end_time') ? old('end_time') . ' WIB' : '15:00 WIB' }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 pr-12 text-slate-900 font-semibold outline-none transition input-focus-effect focus:bg-white cursor-pointer select-none">
                            <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time', '15:00') }}">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Dropdown Picker 24 Jam (Jam, Menit, WIB/WITA/WIT) --}}
                        <div id="picker-end-time" class="hidden absolute left-0 top-full mt-2 w-full max-w-xs md:max-w-sm bg-white rounded-3xl shadow-2xl border border-slate-100 p-4 z-50">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 text-xs">
                                <span class="font-extrabold text-slate-800 tracking-wider uppercase text-[11px]">Format 24 Jam</span>
                                <span class="font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full text-xs" id="preview-end-time">15:00 WIB</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                                <div>
                                    <span class="block font-bold text-slate-400 mb-1.5 uppercase tracking-wider text-[10px]">Jam (24h)</span>
                                    <div class="h-44 overflow-y-auto space-y-1 pr-1 custom-time-scroll" id="hours-end-time"></div>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-400 mb-1.5 uppercase tracking-wider text-[10px]">Menit</span>
                                    <div class="h-44 overflow-y-auto space-y-1 pr-1 custom-time-scroll" id="minutes-end-time"></div>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-400 mb-1.5 uppercase tracking-wider text-[10px]">Zona Waktu</span>
                                    <div class="h-44 space-y-2 pt-1" id="zone-end-time">
                                        <button type="button" data-val="WIB" class="zone-btn w-full py-2.5 rounded-xl font-bold transition text-xs bg-rose-600 text-white shadow-sm">WIB</button>
                                        <button type="button" data-val="WITA" class="zone-btn w-full py-2.5 rounded-xl font-bold transition text-xs bg-slate-50 text-slate-700 hover:bg-slate-100">WITA</button>
                                        <button type="button" data-val="WIT" class="zone-btn w-full py-2.5 rounded-xl font-bold transition text-xs bg-slate-50 text-slate-700 hover:bg-slate-100">WIT</button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 italic">Klik jam di atas</span>
                                <button type="button" onclick="closeAllTimePickers()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                    Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Batas Waktu Absen (Menit)</label>
                    <input type="number" name="batas_waktu_absen" value="{{ old('batas_waktu_absen', 15) }}" min="1" max="120"
                        placeholder="15"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:bg-white">
                    <p class="text-xs text-slate-500 mt-1 ml-1">Toleransi keterlambatan sebelum dianggap terlambat (default: 15 menit)</p>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_geofencing" id="toggle-geofencing"
                        {{ old('is_geofencing') ? 'checked' : '' }}
                        class="rounded border-slate-300 text-rose-600 focus:ring-rose-200">
                    <label for="toggle-geofencing" class="text-sm font-semibold text-slate-700 cursor-pointer">
                        Kunci Lokasi Presensi (Wajib Berada di Lingkungan Sekolah - Radius 100 Meter)
                    </label>
                    <!-- Tombol Info -->
                    <button type="button" onclick="openModal('infoModalCevent')"
                        class="ml-2 text-rose-600 hover:text-rose-800" title="Penjelasan Kunci Lokasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </button>
                </div>

                <div id="geofencing-fields"
                    class="{{ old('is_geofencing') ? 'grid' : 'hidden' }} md:grid-cols-1 gap-6 p-6 rounded-2xl bg-slate-50 border border-slate-200">

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Latitude</label>
                        <input type="text" name="latitude" id="lat-input" value="{{ old('latitude') }}"
                            placeholder="-6.20000"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-slate-900 outline-none input-focus-effect">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Longitude</label>
                        <input type="text" name="longitude" id="lng-input" value="{{ old('longitude') }}"
                            placeholder="106.8166"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-slate-900 outline-none input-focus-effect">
                    </div>

                    <div class="md:col-span-2 text-right">
                        <button type="button" onclick="getLokasiSekarang()"
                            class="text-sm text-rose-600 font-semibold ">
                            📍 Gunakan Lokasi Saya
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-rose-200 transition">
                    Simpan Event
                </button>

            </form>

            <div id="infoModalCevent"
                class="fixed inset-0 top-0 left-0 w-full h-full bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
                    <h2 class="text-xl font-bold mb-3 text-slate-800">Informasi Kunci Lokasi Sekolah</h2>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        <strong>Kunci Lokasi Sekolah</strong> memastikan presensi kehadiran hanya bisa dilakukan jika petugas dan peserta berada di area lingkungan sekolah (maksimal radius 100 meter dari titik koordinat).<br><br>
                        Jika kegiatan diadakan di luar sekolah atau absensi diperbolehkan dari mana saja, <strong>jangan centang pilihan ini</strong> agar presensi bisa dilakukan tanpa pembatasan lokasi.
                    </p>
                    <button type="button" onclick="closeModal('infoModalCevent')"
                        class="w-full py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/Cevent.min.js') }}?v={{ filemtime(public_path('js/Cevent.min.js')) }}"></script>
    <script src="{{ asset('js/infomodal.min.js') }}"></script>

</body>
</html>