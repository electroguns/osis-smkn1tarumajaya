<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sunting Berita | Panel Manajemen</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('berita.css') }}">
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div
        class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-rose-500/10 rounded-full blur-[120px] pointer-events-none z-0">
    </div>
    <div
        class="absolute bottom-10 right-1/4 w-[400px] h-[400px] bg-purple-500/5 rounded-full blur-[120px] pointer-events-none z-0">
    </div>

    <div class="relative z-10 py-12 pt-20 md:pt-12">
        <div class="max-w-2xl mx-auto px-4">

            <div class="mb-8">
                <a href="{{ route('berita.index') }}"
                    class="text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-800 transition-colors flex items-center gap-1 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    Sunting <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-rose-900">Berita</span>
                </h1>
                <p class="text-slate-600 text-sm mt-1">Perbarui informasi artikel atau dokumentasi kegiatan organisasi.
                </p>
            </div>

            @if ($errors->any())
                <div
                    class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-2xl backdrop-blur-md flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-red-400 shrink-0 mt-0.5" width="20"
                        height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" x2="12" y1="8" y2="12" />
                        <line x1="12" x2="12" y1="16" y2="16" />
                    </svg>
                    <ul class="space-y-1 text-red-400 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="bg-white/80 border border-slate-200/80 rounded-[2rem] p-6 sm:p-10 shadow-[0_20px_50px_-20px_rgba(0,0,0,0.05)] backdrop-blur-xl">

                <form action="{{ route('berita.update', $berita) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Title Input --}}
                    <div>
                        <label for="title"
                            class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Judul
                            Berita</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $berita->title) }}"
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-slate-900 placeholder-slate-400 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 {{ $errors->has('title') ? 'border-red-400 bg-red-50/30' : 'border-slate-200' }}"
                            placeholder="Contoh: Pelantikan Raya Pengurus OSIS Periode Terbaru" required>
                    </div>

                    {{-- Image Upload Component --}}
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Foto
                            Berita</label>

                        <div
                            class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2 bg-rose-500/5 border border-rose-500/10 rounded-xl p-3 text-xs text-rose-700">
                            <div class="flex items-center gap-1.5 font-bold shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4" />
                                    <path d="M12 8h.01" />
                                </svg>
                                REKOMENDASI UKURAN:
                            </div>
                            <span class="text-rose-700">
                                Rasio <strong class="text-rose-900">16:9</strong> (Resolusi <strong
                                    class="text-rose-900">1200×675 px)</strong>.
                            </span>
                        </div>

                        @if ($berita->image)
                            <div
                                class="mb-4 relative group rounded-2xl overflow-hidden border border-slate-200 aspect-video max-h-56 bg-slate-100 animate-fade-in">
                                <img src="{{ asset('storage/berita/' . $berita->image) }}" alt="{{ $berita->title }}"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-rose-900/60 via-transparent to-transparent flex items-end p-4">
                                    <span
                                        class="text-xs font-semibold text-white bg-emerald-900/90 backdrop-blur px-2.5 py-1 rounded-md border border-emerald-700/60">
                                        Foto Saat Ini
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="relative group">
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-rose-600 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-rose-700 hover:file:bg-rose-300 file:transition-colors focus:outline-none focus:border-rose-500 cursor-pointer"
                                style="border-color: {{ $errors->has('image') ? '#ef4444' : '#334155' }};">
                            <p class="text-slate-400 text-[11px] mt-1.5">Format berkas: JPG, JPEG, PNG, GIF (Maks 2MB)
                            </p>
                        </div>
                    </div>

                    {{-- Excerpt Input --}}
                    <div>
                        <label for="excerpt"
                            class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Ringkasan
                            Singkat</label>
                        <textarea id="excerpt" name="excerpt" rows="2"
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-slate-900 placeholder-slate-400 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 {{ $errors->has('excerpt') ? 'border-red-400 bg-red-50/30' : 'border-slate-200' }}"
                            placeholder="Tulis ringkasan berita 1-2 kalimat untuk kartu halaman depan...">{{ old('excerpt', $berita->excerpt) }}</textarea>
                    </div>

                    {{-- Content Input --}}
                    <div>
                        <label for="content"
                            class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Isi
                            Berita</label>

                        <input id="content" type="hidden" name="content"
                            value="{{ old('content', $berita->content) }}">

                        <trix-editor input="content"
                            placeholder="Blok teks untuk menebalkan (B), memiringkan (I), atau garis bawah (U)..."
                            class="trix-content {{ $errors->has('content') ? 'border-red-500/60' : '' }}">
                        </trix-editor>

                        <div class="flex items-center gap-1.5 text-slate-500 text-[11px] mt-2">
                            <svg xmlns="http://www.w3.org/2000/xl" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                            <span>Tip: Blok teks yang telah diketik untuk memunculkan atau klik panel menu atas untuk
                                kustomisasi font.</span>
                        </div>
                        @error('content')
                            <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Divisi --}}
                    <div>
                        <label for="divisi"
                            class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Divisi</label>
                        @php
                            $userDivision = str_contains(auth()->user()->role, 'anggota_divisi_') ? str_replace('anggota_divisi_', '', auth()->user()->role) : null;
                            $divisiNames = [
                                'inti' => 'Manajemen Inti',
                                'humas' => 'Divisi Humas',
                                'artmedia' => 'Divisi Artmedia',
                                'olahraga' => 'Divisi Olahraga',
                                'agama' => 'Divisi Agama'
                            ];
                        @endphp
                        @if (str_contains(auth()->user()->role, 'anggota_divisi_'))
                            <input type="text" readonly
                                class="w-full px-5 py-3.5 bg-slate-100 border border-slate-200 rounded-2xl text-slate-600 cursor-not-allowed font-semibold"
                                value="{{ $divisiNames[$berita->divisi] ?? $berita->divisi }}">
                            <input type="hidden" name="divisi" value="{{ $berita->divisi }}">
                        @elseif (in_array(auth()->user()->role, ['ketua', 'wakil', 'sekretaris']))
                            <div class="relative">
                                <select id="divisi" name="divisi" required
                                    class="w-full px-5 py-3.5 pr-12 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 font-semibold transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 appearance-none cursor-pointer">
                                    <option value="inti" {{ $berita->divisi === 'inti' ? 'selected' : '' }}>Manajemen Inti</option>
                                    <option value="humas" {{ $berita->divisi === 'humas' ? 'selected' : '' }}>Divisi Humas</option>
                                    <option value="artmedia" {{ $berita->divisi === 'artmedia' ? 'selected' : '' }}>Divisi Artmedia</option>
                                    <option value="olahraga" {{ $berita->divisi === 'olahraga' ? 'selected' : '' }}>Divisi Olahraga</option>
                                    <option value="agama" {{ $berita->divisi === 'agama' ? 'selected' : '' }}>Divisi Agama</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        @else
                            <input type="text" readonly
                                class="w-full px-5 py-3.5 bg-slate-100 border border-slate-200 rounded-2xl text-slate-600 cursor-not-allowed font-semibold"
                                value="{{ $divisiNames[$berita->divisi] ?? $berita->divisi }}">
                            <input type="hidden" name="divisi" value="{{ $berita->divisi }}">
                        @endif
                    </div>

                    {{-- Tanggal & Status --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="published_at"
                                class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Tanggal
                                Publikasi</label>
                            <input type="datetime-local" id="published_at" name="published_at"
                                value="{{ old('published_at', $berita->published_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 font-semibold transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500">
                        </div>

                        {{-- Status Selection pada Halaman Edit --}}
                        <div>
                            <label for="status"
                                class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Status
                                Publikasi</label>

                            @php
                                $isDivisi = Str::contains(auth()->user()->role, 'divisi');
                                $currentStatus = old('status', $berita->status);
                            @endphp

                            <div class="relative">
                                <select id="status" name="status" {{ $isDivisi ? 'disabled' : '' }}
                                    class="w-full px-5 py-3.5 pr-12 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 font-semibold transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 appearance-none cursor-pointer {{ $isDivisi ? 'opacity-60 bg-slate-100 cursor-not-allowed' : '' }}">

                                    <option value="draft"
                                        {{ $currentStatus === 'draft' || $isDivisi ? 'selected' : '' }}>📁 Simpan Sebagai
                                        Draft</option>

                                    <option value="published"
                                        {{ $currentStatus === 'published' && !$isDivisi ? 'selected' : '' }}>🚀
                                        Publikasikan Langsung
                                    </option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            @if ($isDivisi)
                                <input type="hidden" name="status" value="draft">
                                <p class="text-amber-600 text-[11px] mt-1.5 flex items-center gap-1">Status dikunci ke
                                    Draft untuk peninjauan Admin.</p>
                            @endif

                            @error('status')
                                <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-100">
                        <button type="submit"
                            class="w-full sm:flex-1 order-1 sm:order-2 px-6 py-4 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white rounded-2xl shadow-lg shadow-rose-200 transition-all font-bold text-sm tracking-wide transform active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('berita.index') }}"
                            class="w-full sm:flex-1 order-2 sm:order-1 text-center px-6 py-4 bg-slate-100 border border-slate-200 text-slate-600 rounded-2xl hover:bg-slate-200 hover:text-slate-800 transition-all font-bold text-sm tracking-wide">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>