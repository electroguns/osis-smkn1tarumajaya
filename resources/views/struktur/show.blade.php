<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ID Card - {{ $user->name }}</title>
        @vite('resources/css/app.css')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=block"
        rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('/fonts/Poppins-Regular.ttf') format('truetype');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('/fonts/Poppins-Bold.ttf') format('truetype');
            font-weight: 700;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .id-card-size {
            width: 350px;
            height: 540px;
        }

        .glass-premium-svg-fix {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        #id-card-download img {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }
    </style>

    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-[#2d0a0f] via-[#571319] to-[#8b1e2b] bg-fixed font-[Poppins] antialiased flex flex-col items-center py-6 pt-20 md:pt-10">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    {{-- Tombol Navigasi & Download --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
        <a href="{{ route('struktur.index') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-sm font-semibold transition backdrop-blur-sm border border-white/10 w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Panel
        </a>

        <button id="btn-download"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-600 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white rounded-xl text-sm font-semibold transition shadow-lg shadow-rose-600/30 w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download Gambar (PNG)
        </button>
    </div>

    {{-- WRAPPER TRANSPARAN --}}
    <div id="id-card-wrapper" class="p-4 bg-transparent inline-block">

        {{-- ID CARD CONTAINER (PARENT) --}}
        <div id="id-card-download"
            class="id-card-size relative overflow-hidden bg-gradient-to-b from-rose-400 via-rose-500 to-rose-600 p-[2px] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.5)]"
            style="border-radius: 32px; -webkit-border-radius: 32px;">

            {{-- Elemen Desain Abstrak Latar Belakang --}}
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/20 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-rose-400/30 rounded-full blur-2xl"></div>

            <div class="w-full h-full bg-gradient-to-b from-[#571319]/80 to-rose-950/95 px-6 py-7 flex flex-col items-center relative z-10 text-white text-center"
                style="border-radius: 30px; -webkit-border-radius: 30px;">

                {{-- Header OSIS --}}
                <div class="w-full shrink-0 mb-2">
                    <h2 class="text-xs font-extrabold uppercase tracking-[0.2em] text-rose-300">Pengurus OSIS</h2>
                    <h3 class="text-sm font-bold tracking-wide text-white/90">SMKN 1 TARUMAJAYA</h3>
                    <div class="h-[2px] w-12 bg-rose-400 mx-auto mt-2 rounded-full"></div>
                </div>

                {{-- Komponen Foto Profil --}}
                <div class="relative my-3 shrink-0">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-rose-400 to-rose-500 rounded-3xl blur-md opacity-70 scale-105">
                    </div>

                    <div class="relative w-32 h-44 rounded-3xl overflow-hidden border-4 border-white/80 bg-rose-800">
                        @if ($user->foto)
                            <img src="{{ asset('storage/struktural/' . $user->foto) }}" crossOrigin="anonymous"
                                class="w-full h-full object-cover object-center">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center text-5xl font-black text-white/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Identitas Diri --}}
                <div class="w-full px-2 flex-grow flex flex-col items-center justify-center my-2">
                    <h1
                        class="text-xl font-extrabold tracking-tight text-white uppercase leading-normal w-full pb-1 block">
                        {{ $user->name }}
                    </h1>

                    <div
                        class="inline-flex items-center justify-center px-6 py-1 rounded-full bg-gradient-to-r from-rose-500 to-rose-500 text-[11px] font-extrabold uppercase tracking-wider shadow-md my-1">
                        <span class="text-white whitespace-nowrap leading-tight">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </div>

                    <p class="text-xs text-rose-300 font-medium pt-1">Kelas: <span
                            class="text-white font-bold">{{ $user->kelas }}</span></p>
                </div>

                {{-- Footer Card + QR Code --}}
                <div
                    class="w-full glass-premium-svg-fix rounded-2xl p-3 flex items-center justify-between gap-4 shrink-0">
                    <div class="text-left space-y-0.5 pl-1">
                        <p class="text-[9px] uppercase tracking-widest text-white font-bold">ID Anggota</p>
                        <p class="text-xs font-mono font-bold text-rose-500">
                            OSIS-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[9px] text-white italic pt-1 font-semibold">Berlaku s/d Periode Selesai</p>
                    </div>

                    <div class="bg-white p-1.5 rounded-xl shadow-md shrink-0 flex items-center justify-center">
                        @if ($user->qr_token)
                            {!! QrCode::size(58)->margin(0)->generate($user->qr_token) !!}
                        @else
                            <div
                                class="w-[58px] h-[58px] bg-rose-100 flex items-center justify-center text-[8px] text-rose-400 font-bold">
                                NO QR</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script type="module">

        import { toPng } from 'https://esm.sh/html-to-image';
        document.getElementById('btn-download').addEventListener('click', function() {
            const cardWrapper = document.getElementById('id-card-wrapper');

            document.fonts.ready.then(function() {
                setTimeout(function() {
                    console.log(
                        getComputedStyle(document.body).fontFamily
                    );
                    toPng(cardWrapper, {
                        scale: 3,
                        quality: 1,
                        backgroundColor: null,
                        features: {
                            webgl: false
                        }
                    }).then(dataUrl => {
                        const link = document.createElement('a');
                        link.download = "ID-Card-{{ Str::slug($user->name) }}.png";
                        link.href = dataUrl;
                        link.click();
                    }).catch(err => {
                        alert("Gagal men-download ID Card.");
                        console.error(err);
                    });
                }, 150);
            });
        });
    </script>
</body>

</html>
