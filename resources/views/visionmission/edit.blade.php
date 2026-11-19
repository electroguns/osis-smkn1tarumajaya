<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal resmi OSIS SMKN 1 Tarumajaya. Temukan berita terbaru, program kerja, dan informasi penting lainnya seputar organisasi siswa kami. Bergabunglah untuk menjadi bagian dari perubahan positif di sekolah!" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Visi & Misi | Panel Manajemen</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div class="max-w-4xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- Top Navigation & Header --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <a href="{{ route('visionmission.index') }}" aria-label="Dashboard Visi Misi"
                    class="inline-flex items-center text-sm font-bold text-rose-600 hover:text-rose-700 transition gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">Edit <span
                        class="text-rose-600">Visi & Misi</span></h1>
                <p class="text-slate-500">Perbarui informasi tujuan dan target OSIS agar tetap selaras dengan program kerja.</p>
            </div>
        </div>

        {{-- Error Validation Alert --}}
        @if ($errors->any())
            <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-5">
                <div class="flex gap-3 text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <ul class="text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Two-Column Grid Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Left Column: Live Status Card --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Information Card Section --}}
                <div class="rounded-[24px] bg-rose-900 p-6 text-white shadow-xl shadow-rose-900/20">
                    <p class="text-rose-200 text-xs font-bold uppercase tracking-widest mb-2">Petunjuk</p>
                    <p class="text-sm leading-relaxed text-rose-50">Tuliskan deskripsi secara ringkas, padat, dan jelas. Hindari penggunaan singkatan yang membingungkan agar pembaca di landing page mengerti arah pergerakan OSIS.</p>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="lg:col-span-8">
                <div class="rounded-[32px] bg-white p-8 md:p-10 shadow-xl shadow-slate-200/60 border border-white">
                    <form action="{{ route('visionmission.update', $visionmission) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            {{-- Select Category Input --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kategori Konten</label>
                                <select name="category" id="category-select" required
                                    class="w-full rounded-2xl px-5 py-4 text-slate-900 outline-none transition font-medium cursor-pointer"
                                    style="background: rgba(255,255,255,0.75); border: 1.5px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
                                    <option value="Visi" {{ old('category', $visionmission->category) === 'Visi' ? 'selected' : '' }}>Visi</option>
                                    <option value="Misi" {{ old('category', $visionmission->category) === 'Misi' ? 'selected' : '' }}>Misi</option>
                                </select>
                            </div>

                            {{-- Textarea Description Input --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Deskripsi Keterangan</label>
                                <textarea name="description" rows="5" required placeholder="Tulis rincian narasi visi atau poin misi di sini..."
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white leading-relaxed font-medium">{{ old('description', $visionmission->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Form Action Button --}}
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full md:w-auto inline-flex items-center justify-center rounded-2xl bg-rose-600 px-10 py-4 text-white font-bold shadow-lg shadow-rose-200 hover:bg-rose-700 hover:shadow-rose-300/40 transition-all duration-300">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/Evm.min.js') }}"></script>
    <script src="{{ asset('js/custom-ui.js') }}"></script>
</body>

</html>
