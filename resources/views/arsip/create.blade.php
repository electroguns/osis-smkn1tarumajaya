<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Kenangan Baru | Arsip Foto OSIS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .input-focus-effect:focus {
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
            border-color: #571319;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div class="max-w-3xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- Top Navigation & Header Section --}}
        <div class="mb-10">
            <a href="{{ route('arsip.index') }}" aria-label="Kembali ke Arsip Foto"
                class="inline-flex items-center text-sm font-bold text-rose-600 hover:text-rose-700 transition gap-2 mb-3 group">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Arsip
            </a>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900">
                Tambah <span class="text-rose-600">Kenangan Baru</span>
            </h1>
            <p class="text-slate-500 mt-1.5 text-sm md:text-base">
                Abadikan dokumentasi kegiatan dan momen berharga kepengurusan OSIS.
            </p>
        </div>

        {{-- Error Validation Alert --}}
        @if ($errors->any())
            <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-5 shadow-sm">
                <div class="flex gap-3 text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm font-medium">
                        <p class="font-bold mb-1">Terjadi kesalahan input:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Card Form Tambah Kenangan Baru --}}
        <div class="relative bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.06)] border border-white">
            <div class="flex items-center gap-3.5 mb-8">
                <div class="p-2.5 bg-rose-500 rounded-2xl shadow-lg shadow-rose-200 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800">Tambah Kenangan Baru</h3>
                    <p class="text-xs text-slate-400">Pastikan foto dalam format gambar dan ukuran maks 10MB.</p>
                </div>
            </div>

            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- JUDUL KEGIATAN -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="judul" class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">
                            Judul Kegiatan
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                            placeholder="Malam Keakraban"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-[#571319] outline-none transition-all duration-200 input-focus-effect font-medium text-slate-800">
                    </div>

                    <!-- TAHUN -->
                    <div class="space-y-1.5">
                        <label for="tahun" class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">
                            Tahun
                        </label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', date('Y')) }}" required min="2000" max="2099"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-[#571319] outline-none transition-all duration-200 input-focus-effect font-medium text-slate-800">
                    </div>
                </div>

                <!-- CAPTION -->
                <div class="space-y-1.5">
                    <label for="caption" class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">
                        Caption
                    </label>
                    <textarea id="caption" name="caption" rows="3"
                        placeholder="Tulis cerita singkat tentang kegiatan ini..."
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-[#571319] outline-none transition-all duration-200 input-focus-effect font-medium text-slate-800 resize-none">{{ old('caption') }}</textarea>
                </div>

                <!-- UPLOAD FOTO -->
                <div class="space-y-1.5">
                    <label for="foto" class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">
                        Upload Foto
                    </label>
                    <input type="file" id="foto" name="foto" accept="image/*" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition-all cursor-pointer">
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="pt-3">
                    <button type="submit"
                        class="group relative w-full overflow-hidden rounded-2xl bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] px-8 py-4 font-bold text-white transition-all shadow-lg shadow-rose-200 hover:shadow-xl active:scale-[0.99]">
                        <div class="relative z-10 flex items-center justify-center gap-2">
                            <span>Simpan Kenangan</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="group-hover:-translate-y-0.5 transition-transform">
                                <path d="m5 12 7-7 7 7" />
                                <path d="M12 19V5" />
                            </svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>

