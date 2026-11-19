<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Papan Kenangan & Arsip Foto OSIS SMKN 1 Tarumajaya</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Caveat & Patrick Hand for authentic handwritten chalk, Plus Jakarta Sans & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Patrick+Hand&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('arsip.css') }}">
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="overflow-x-hidden selection:bg-rose-500 selection:text-white">

    {{-- Top Wooden Frame Border (Bingkai Kayu Papan Tulis Kelas) --}}
    <div class="chalkboard-top-frame"></div>

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#be123c',
                    background: '#17261f',
                    color: '#f8fafc',
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @php
        $user = Auth::user();

        // Ketua, Wakil, Sekretaris - CRUD
        // Kadiv humas & artmedia - CRUD
        // Anggota divisi humas & artmedia - CR (Create, Read)
        $canCreateArsip = $user && (
            in_array($user->role, ['ketua', 'wakil', 'sekretaris']) ||
            in_array($user->role, ['ketua_divisi_humas', 'ketua_divisi_artmedia']) ||
            in_array($user->role, ['anggota_divisi_humas', 'anggota_divisi_artmedia'])
        );

        // Ketua, Wakil, Sekretaris - CRUD
        // Kadiv humas & artmedia - CRUD
        $canDeleteArsip = $user && (
            in_array($user->role, ['ketua', 'wakil', 'sekretaris']) ||
            in_array($user->role, ['ketua_divisi_humas', 'ketua_divisi_artmedia'])
        );
    @endphp

    {{-- CHALKBOARD MAIN CONTAINER --}}
    <div class="chalkboard-container min-h-screen relative flex flex-col justify-between pt-16 md:pt-12">
        {{-- Chalk Dust Overlay --}}
        <div class="chalk-dust-overlay"></div>

        {{-- BACK BUTTON --}}
        <div class="fixed left-4 top-1/2 -translate-y-1/2 z-[100]">
            <div id="back-btn-container"
                class="mobile-back-btn is-closed
                transition-all duration-300
                flex items-center justify-center
                bg-gradient-to-br from-[#7d441e] to-[#a15a28]
                border border-amber-300/40
                shadow-xl shadow-black/60
                w-9 h-14 rounded-r-2xl
                -translate-x-[20px]
                cursor-pointer"
                title="Kembali ke Dashboard">
                <a href="{{ url('/') }}" id="back-link" onclick="handleMobileBack(event)"
                    class="w-full h-full flex items-center justify-center">
                    <svg id="back-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                        viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.6" stroke-linecap="round"
                        stroke-linejoin="round" class="transition-all duration-300">
                        <path id="back-arrow" d="M15 18l-6-6 6-6" class="transition-all duration-300" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- CONTENT WRAPPER --}}
        <div class="w-full px-4 sm:px-6 relative z-10">

            {{-- HEADER SECTION PAPAN TULIS --}}
            <div class="max-w-4xl mx-auto text-center mb-8 relative">

                {{-- Chalk Doodle: Left Camera --}}
                <div class="hidden md:flex flex-col items-center absolute -left-16 top-2 text-white/30 transform -rotate-12 pointer-events-none select-none">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <span class="chalk-font-hand text-sm text-white/40 mt-1">✦ Jepret & Kenang ✦</span>
                </div>

                {{-- Chalk Doodle: Right Graduation Cap --}}
                <div class="hidden md:flex flex-col items-center absolute -right-16 top-2 text-white/30 transform rotate-12 pointer-events-none select-none">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    <span class="chalk-font-hand text-sm text-white/40 mt-1">✨ OSIS Berjaya ✨</span>
                </div>

                {{-- Chalk Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full chalk-badge text-xs sm:text-sm font-semibold mb-4 tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                    <span>DOKUMENTASI & MEMORI ARSIP KEGIATAN OSIS</span>
                </div>

                {{-- Chalk Title --}}
                <h1 class="chalk-font-hand text-4xl sm:text-6xl md:text-7xl font-bold chalk-text tracking-tight leading-tight">
                    Papan Kenangan <span class="text-rose-400 font-extrabold">Putih Abu-Abu</span>
                    <button type="button" onclick="openModal('infoModalArsip')" class="text-rose-300 hover:text-white transition align-middle ml-1" title="Info Halaman">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block opacity-80 hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </button>
                </h1>

                {{-- Chalk Quote / Description --}}
                <p class="chalk-font-sub text-base sm:text-lg md:text-xl text-slate-300 mt-3 max-w-2xl mx-auto leading-relaxed">
                    Setiap lembar foto merawat tawa, peluh, dan jejak perjuangan kita di SMKN 1 Tarumajaya dari masa ke masa.
                </p>

                {{-- Chalk Arrow Indicator --}}
                <div class="mt-3 inline-flex items-center gap-2 text-xs sm:text-sm text-rose-300/80 chalk-font-sub tracking-wider">
                    <span>⟵ Tarik tali / geser foto ke samping untuk menjelajah kenangan ⟶</span>
                </div>

                {{-- Info Modal --}}
                <div id="infoModalArsip" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-[200] p-4">
                    <div class="bg-[#1b2b23] border border-white/20 rounded-3xl shadow-2xl p-6 sm:p-8 max-w-md w-full text-left relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-xl">
                                📷
                            </div>
                            <h2 class="chalk-font-hand text-3xl font-bold text-white chalk-text">Papan Memori OSIS</h2>
                        </div>
                        <p class="chalk-font-sub text-base text-slate-300 leading-relaxed mb-6">
                            Halaman ini mengabadikan seluruh dokumentasi kegiatan OSIS SMKN 1 Tarumajaya seperti galeri foto polaroid yang digantung pada tali tambang di atas papan tulis sekolah. Semua anggota dapat menikmati kenangan ini, dan pengurus berwenang dapat menggantungkan momen baru kapan saja.
                        </p>
                        <button onclick="closeModal('infoModalArsip')"
                            class="w-full py-2.5 px-4 bg-gradient-to-r from-rose-600 to-[#571319] hover:from-rose-500 hover:to-rose-700 text-white font-bold rounded-xl transition shadow-lg">
                            Tutup
                        </button>
                    </div>
                </div>

            </div>

            {{-- FILTER TAHUN PAPAN TULIS --}}
            <div class="flex flex-wrap justify-center items-center gap-2.5 mb-6">
                <a href="{{ url('arsip') }}"
                    class="chalk-btn px-4 py-1.5 rounded-full {{ !request('tahun') ? 'is-active' : '' }}">
                    ✦ Semua Kenangan
                </a>

                @foreach ($listTahun as $thn)
                    <a href="{{ url('/arsip?tahun=' . $thn) }}"
                        class="chalk-btn px-4 py-1.5 rounded-full {{ request('tahun') == $thn ? 'is-active' : '' }}">
                        Tahun {{ $thn }}
                    </a>
                @endforeach
            </div>

            {{-- ACTION BUTTONS SECTION --}}
            <div class="flex flex-wrap justify-center items-center gap-3 mb-6">
                @if ($canCreateArsip)
                    <a href="{{ route('arsip.create') }}"
                        class="group px-6 py-2.5 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-700 to-[#571319] hover:from-rose-500 hover:to-rose-600 text-white font-bold shadow-xl shadow-rose-950/40 hover:scale-105 active:scale-95 transition-all duration-300 inline-flex items-center gap-2 border border-rose-400/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        <span>Gantung Kenangan Baru</span>
                    </a>
                @endif

                @if ($canDeleteArsip)
                    <!-- DELETE MODE -->
                    <button id="toggle-delete-mode"
                        class="group px-5 py-2.5 rounded-2xl bg-gradient-to-r from-rose-800 to-red-700 hover:from-rose-700 hover:to-red-600 text-white font-bold shadow-lg shadow-black/40 hover:scale-105 active:scale-95 transition-all duration-300 border border-red-400/30">
                        <span class="flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20 8 21 9 21H15C16 21 16.9 20 17 19L18 7M9 7V5C9 4.4 9.4 4 10 4H14C14.6 4 15 4.4 15 5V7" />
                            </svg>
                            Kelola / Hapus Foto
                        </span>
                    </button>

                    <!-- SELECT ALL -->
                    <button id="select-all-btn"
                        class="hidden px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold border border-white/20 transition-all duration-200">
                        Pilih Semua
                    </button>

                    <!-- DELETE SELECTED -->
                    <button id="delete-selected-btn"
                        class="hidden px-4 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold shadow-md transition-all duration-200">
                        Hapus Terpilih
                    </button>

                    <!-- CANCEL -->
                    <button id="cancel-delete-mode"
                        class="hidden px-4 py-2.5 rounded-2xl bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-semibold transition-all duration-200">
                        Batal
                    </button>
                @endif
            </div>

            {{-- THE ROPE STAGE — SVG catenary rope rendered by arsip.min.js --}}
            <div class="rope-stage">
                {{-- SVG rope + nails injected here by drawRopeSVG() in arsip.min.js --}}

                {{-- Track Foto Polaroid yang Digantung --}}
                <div id="polaroid-track" class="arsip-track">
                    {{-- Dynamically populated by public/js/arsip.min.js --}}
                </div>
            </div>

        </div>

        {{-- BOTTOM CHALK TRAY & FELT ERASER (Rak Kapur & Penghapus Papan Tulis Kelas) --}}
        <div class="chalkboard-bottom-tray">
            {{-- Chalk Sticks --}}
            <div class="chalk-stick chalk-white" title="Kapur Putih"></div>
            <div class="chalk-stick chalk-pink" title="Kapur Merah Muda"></div>
            <div class="chalk-stick chalk-yellow" title="Kapur Kuning"></div>
            <div class="chalk-stick chalk-blue hidden sm:block" title="Kapur Biru"></div>

            {{-- Felt Blackboard Eraser --}}
            <div class="chalk-eraser" title="Penghapus Papan Tulis SMKN 1"></div>
        </div>

    </div>

    {{-- LIGHTBOX PREVIEW MODAL --}}
    <div id="chalk-lightbox" class="hidden">
        <div class="lightbox-polaroid relative">
            {{-- Close Button --}}
            <button type="button" id="lightbox-close-btn" class="absolute -top-4 -right-4 w-10 h-10 rounded-full bg-rose-700 text-white font-bold flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 transition z-50">
                ✕
            </button>

            {{-- Large Image Frame --}}
            <div class="w-full h-80 sm:h-96 rounded-sm overflow-hidden bg-slate-900 shadow-inner relative">
                <img id="lightbox-img" src="" alt="Preview Kenangan" class="w-full h-full object-contain">
            </div>

            {{-- Polaroid Caption --}}
            <div class="mt-4 text-center">
                <h3 id="lightbox-title" class="chalk-font-hand text-3xl font-bold text-slate-900 leading-tight"></h3>
                <p id="lightbox-caption" class="chalk-font-sub text-base text-slate-600 mt-1 max-w-lg mx-auto leading-relaxed"></p>
                <div class="mt-4 flex items-center justify-center gap-3">
                    <span id="lightbox-year" class="px-3 py-1 rounded border border-rose-900/30 text-xs font-bold text-rose-900 bg-rose-50 uppercase"></span>
                    <a id="lightbox-download" href="" download class="px-4 py-1.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-1.5">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 12L12 16.5m0 0L16.5 12M12 16.5V3" />
                        </svg>
                        <span>Unduh Foto</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed bottom-12 right-5 z-[100] flex flex-col gap-2"></div>

    <script>
        window.currentUser = @json(auth()->user());
        window.arsipData = @json($arsips ?? []);
        window.csrfToken = "{{ csrf_token() }}";
        window.arsipStoreRoute = "{{ route('arsip.store') }}";
    </script>

    <script src="{{ asset('js/arsip.min.js') }}"></script>
    <script src="{{ asset('js/Bbutton.min.js') }}"></script>
    <script src="{{ asset('js/infomodal.min.js') }}"></script>
</body>

</html>
