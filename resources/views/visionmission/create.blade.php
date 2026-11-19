<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal resmi OSIS SMKN 1 Tarumajaya. Temukan berita terbaru, program kerja, dan informasi penting lainnya seputar organisasi siswa kami. Bergabunglah untuk menjadi bagian dari perubahan positif di sekolah!" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Visi & Misi | Panel Manajemen</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div class="max-w-4xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- Top Navigation & Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <a href="{{ route('visionmission.index') }}" aria-label="Dashboard Visi Misi"
                    class="inline-flex items-center text-sm font-bold text-rose-600 hover:text-rose-700 transition gap-2 mb-2 group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">Tambah <span class="text-rose-600">Visi &
                        Misi</span></h1>
                <p class="text-slate-500">Buat target capaian dan program aksi strategis baru untuk kepengurusan OSIS.
                </p>
            </div>
        </div>

        {{-- Error Validation Alert Section --}}
        @if ($errors->any())
            <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-5 shadow-sm">
                <div class="flex gap-3 text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm font-medium">
                        <p class="font-bold mb-1">Terjadi kesalahan:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Two-Column Grid Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Left Column --}}
            <div class="lg:col-span-4 space-y-6 text-slate-600">

                {{-- Panduan Cepat Card --}}
                <div
                    class="rounded-[32px] bg-white/60 backdrop-blur-md p-8 border border-white shadow-xl shadow-slate-200/50">
                    <div
                        class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-rose-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Format Konten</h3>
                    <ul class="space-y-4 text-sm leading-relaxed">
                        <li class="flex gap-3">
                            <span class="font-bold text-rose-600">Visi</span>
                            <p>Merupakan cita-cita jangka panjang besar yang ingin diraih bersam-sama.</p>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-rose-600">Misi</span>
                            <p>Merupakan rincian poin-poin taktis instruksional tindakan nyata.</p>
                        </li>
                    </ul>
                </div>

                {{-- Perilaku Publikasi --}}
                <div
                    class="rounded-[32px] bg-gradient-to-br from-slate-800 to-slate-900 p-8 text-white shadow-2xl shadow-slate-900/20">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Otomatisasi Publik
                    </h4>
                    <p class="text-xs text-slate-400 leading-loose">
                        Setiap data yang Anda simpan akan langsung disinkronkan ke dalam komponen animasi utama halaman
                        depan (<i>landing page</i>) dengan urutan penomoran otomatis.
                    </p>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="lg:col-span-8">
                <div class="rounded-[32px] bg-white p-8 md:p-10 shadow-2xl shadow-slate-200/60 border border-white">
                    <form action="{{ route('visionmission.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="space-y-6">
                            {{-- Dropdown Select Category Input --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kategori Dokumen</label>
                                <select name="category" id="category-dropdown" required
                                    class="w-full rounded-2xl px-5 py-4 text-slate-900 outline-none transition font-medium cursor-pointer {{ $errors->has('category') ? 'ring-2 ring-red-400' : '' }}"
                                    style="background: rgba(255,255,255,0.75); border: 1.5px solid {{ $errors->has('category') ? '#f87171' : '#e2e8f0' }}; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
                                    <option value="Visi" {{ old('category') == 'Visi' ? 'selected' : '' }}>Visi</option>
                                    <option value="Misi" {{ old('category') == 'Misi' ? 'selected' : '' }}>Misi</option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-xs mt-2 ml-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Textarea Narration Input --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Narasi Deskripsi</label>
                                <textarea name="description" rows="5" required placeholder="Tulis rincian narasi visi atau poin misi di sini..."
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:border-rose-500 focus:bg-white leading-relaxed font-medium">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-2 ml-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Form Submit Action Button --}}
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full group flex items-center justify-center gap-3 rounded-2xl bg-rose-600 px-8 py-5 text-white font-extrabold shadow-xl shadow-rose-200 hover:bg-rose-700 hover:shadow-rose-300/60 transition-all duration-300">
                                <span>Simpan & Publikasikan</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

     <script src="{{ asset('js/Cvm.min.js') }}"></script>
    <script src="{{ asset('js/custom-ui.js') }}"></script>
</body>

</html>
