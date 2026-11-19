<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Portal resmi OSIS SMKN 1 Tarumajaya. Temukan berita terbaru, program kerja, dan informasi penting lainnya seputar organisasi siswa kami. Bergabunglah untuk menjadi bagian dari perubahan positif di sekolah!" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Auth::check() && !request()->has('preview') ? 'Dashboard Administrator | OSIS SMKN 1 Tarumajaya' : 'OSIS SMKN 1 Tarumajaya Portal' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('index.css') }}">
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">

</head>

<body class="h-full overflow-x-hidden">
    <div id="app" class="w-full h-full overflow-auto scroll-smooth {{ Auth::check() && !request()->has('preview') ? 'bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed' : 'bg-[#f8fafc]' }}">

        @if (Auth::check() && !request()->has('preview'))
            {{-- ========================================================== --}}
            {{-- TAMPILAN KHUSUS BACKEND ADMINISTRATOR (SETELAH LOGIN)      --}}
            {{-- ========================================================== --}}

            {{-- Sidebar Dini --}}
            @include('dini')

            <div class="pt-16 pb-20 min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">
                @include('layouts.roleaccess')
            </div>

        @else
            {{-- ========================================================== --}}
            {{-- TAMPILAN FRONT END PUBLIK (GUEST ATAU LIVE PREVIEW IFRAME) --}}
            {{-- ========================================================== --}}

            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Hero -->
            @include('layouts.hero')

            {{-- Visi & Misi --}}
            @include('layouts.visionmission')

            <!-- Struktur Organisasi -->
            @include('layouts.struktur')

            <!-- Berita Upcoming -->
            @include('layouts.berita')

            <!-- Statistik -->
            @include('layouts.statistik')

            <!-- Pendaftaran -->
            @include('layouts.pendaftaran')

            <!-- Feedback -->
            @include('layouts.suara')

            <!-- Kontak -->
            @include('layouts.kontak')

            <!-- Arsip Kegiatan -->
            @include('layouts.arsip')

            <!-- Footer -->
            @include('layouts.footer')

        @endif
    </div>

    <!-- Toast container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2"></div>

    @if (!Auth::check() || request()->has('preview'))
        <!-- Login Modal (Dimuat untuk tamu front-end atau mode live preview) -->
        @include('layouts.loginmodal')
    @endif

    <script>
        window.currentUser = @json((Auth::user() && !request()->has('preview'))
                ? [
                    'display' => Auth::user()->name,
                    'role' => Auth::user()->role,
                ]
                : null);

        window.routes = {
            login: "{{ route('login') }}",
            logout: "{{ route('logout') }}",
        };

        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/osis.min.js') }}"></script>
    <script src="{{ asset('js/site-config.min.js') }}"></script>
    <script src="{{ asset('js/custom-ui.js') }}"></script>
    @if (!Auth::check() && !request()->has('preview') && request()->has('login'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.ModalSystem) {
                    ModalSystem.open('login-modal');
                }
            });
        </script>
    @endif
    @auth
        @if (!request()->has('preview'))
            <script>
                // Bersihkan parameter query ?login di URL jika user sudah dalam status login di dashboard admin
                if (window.location.search.includes('login')) {
                    const cleanUrl = new URL(window.location);
                    cleanUrl.searchParams.delete('login');
                    window.history.replaceState({}, document.title, cleanUrl.pathname + (cleanUrl.search ? cleanUrl.search : '') + cleanUrl.hash);
                }
            </script>
        @endif
    @endauth
</body>

</html>
