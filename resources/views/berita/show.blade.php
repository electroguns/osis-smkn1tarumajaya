<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $berita->title }}</title>
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 md:pt-12 pb-16">
        <header class="mb-8">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-5">
                {{ $berita->title }}
            </h1>

            <div
                class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs sm:text-sm text-slate-600 border-y border-[#571319]/30 py-3">

                @if ($berita->author)
                    <div class="flex items-center gap-1.5 font-medium text-slate-700">
                        <span class="font-semibold text-[#571319]">
                            Oleh:
                        </span>
                        {{ $berita->author->name }}
                    </div>

                    <span class="text-[#571319]/50 hidden sm:inline">•</span>
                @endif

                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" class="text-[#571319]">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>

                    <span class="text-slate-700">
                        {{ $berita->published_at ? $berita->published_at->translatedFormat('l, j F Y | H:i') : 'Menunggu Publikasi (Draft)' }}
                        WIB
                    </span>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
            <div class="lg:col-span-2">
                {{-- Hero Image --}}
                <div class="mb-12">
                    @if ($berita->image)
                        <div
                            class="relative overflow-hidden rounded-3xl border border-[#571319]/30 bg-[#571319]/10 shadow-xl">
                            <img src="{{ asset('storage/berita/' . $berita->image) }}" alt="{{ $berita->title }}"
                                class="w-full aspect-video object-cover">
                        </div>

                        <p class="mt-4 text-sm text-slate-600 italic border-l-2 border-[#571319]/40 pl-4">
                            <span class="text-[#571319] font-medium">Foto:</span> {{ $berita->title }}
                        </p>
                    @else
                        <div
                            class="aspect-video rounded-3xl bg-gradient-to-br from-[#571319]/40 to-slate-200 flex flex-col items-center justify-center border border-[#571319]/30 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-[#571319] h-16 w-16 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                                <path d="M21 15l-3.086-3.086a2 2 0 00-2.828 0L6 21"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                            </svg>
                            <span class="text-sm text-[#571319]">Gambar tidak tersedia</span>
                        </div>
                    @endif
                </div>

                {{-- Article Container --}}
                <div class="bg-white border border-slate-200 rounded-3xl p-8 lg:p-12 shadow-md">
                    @if ($berita->excerpt)
                        <p
                            class="text-xl leading-relaxed text-slate-700 font-light italic border-l-2 border-slate-400 pl-6 mb-12">
                            {{ $berita->excerpt }}
                        </p>
                    @endif

                    <article
                        class="prose prose-lg max-w-none
                        prose-headings:text-slate-900
                        prose-p:text-slate-700 prose-p:mb-6
                        prose-strong:text-slate-900
                        prose-a:text-[#571319]
                        prose-blockquote:border-slate-400
                        prose-blockquote:text-slate-600">

                        <div class="content-body entry-content">
                            {!! $berita->content !!}
                        </div>
                    </article>
                </div>

                {{-- Rejection Notice --}}
                @if ($berita->status === 'draft' && !is_null($berita->rejection_reason))
                    <div class="mt-8 p-5 bg-amber-500/10 border border-amber-500/20 rounded-2xl">

                        <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">
                            ⚠️ Catatan Revisi
                        </span>

                        <p
                            class="mt-3 text-sm text-rose-100 bg-rose-950/40 p-4 rounded-xl border border-rose-900/40 italic leading-relaxed">
                            "{{ $berita->rejection_reason }}"
                        </p>
                    </div>
                @endif


                @auth
                    @php
                        $role = auth()->user()->role;
                    @endphp

                    @if (in_array($role, ['ketua', 'wakil', 'kadiv_humas', 'sekretaris']) ||
                            ($role === 'anggota_humas' && auth()->id() === $berita->author_id))
                        <div class="flex flex-wrap gap-3 mt-12 pt-6 border-t border-rose-900/40">

                            {{-- Hanya Ketua, Wakil, dan Anggota Humas pemilik yang boleh Edit --}}
                            @if (in_array($role, ['ketua', 'wakil']) || ($role === 'anggota_humas' && auth()->id() === $berita->author_id))
                                <a href="{{ route('berita.edit', $berita) }}"
                                    class="px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition text-xs font-semibold shadow-md shadow-amber-950/20">
                                    Edit Berita
                                </a>
                            @endif

                            {{-- Hanya Ketua & Wakil yang boleh Hapus --}}
                            @if (in_array($role, ['ketua', 'wakil']))
                                <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition text-xs font-semibold shadow-md shadow-red-950/20">
                                        Hapus Berita
                                    </button>
                                </form>
                            @endif

                            {{-- KADIV HUMAS & SEKRETARIS: MANAGEMEN PERSETUJUAN --}}
                            @if (in_array($role, ['kadiv_humas', 'sekretaris']))
                                <div
                                    class="w-full flex flex-col gap-4 bg-rose-950/20 p-4 border border-rose-900/40 rounded-2xl mt-4">
                                    <span class="text-xs font-bold text-rose-400/80 uppercase tracking-wider">Navigasi
                                        Persetujuan Konten:</span>
                                    <div class="flex flex-wrap gap-3">
                                        @if ($berita->status !== 'published')
                                            <form action="{{ route('berita.approve', $berita) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition text-xs font-semibold shadow-md">
                                                    ✅ Setujui & Publish
                                                </button>
                                            </form>

                                            <button type="button" onclick="toggleRejectForm()"
                                                class="px-4 py-2 bg-rose-950/60 text-amber-400 rounded-xl hover:bg-rose-900/60 transition text-xs font-semibold shadow-md border border-amber-500/20">
                                                ❌ Tolak Berita (Kembalikan ke Draft)
                                            </button>
                                        @else
                                            <div
                                                class="px-4 py-2 bg-emerald-950/50 text-emerald-400 rounded-xl border border-emerald-500/20 text-xs font-bold select-none">
                                                🎉 Berita Terpublikasi
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Container Form Alasan --}}
                                    <div id="reject-reason-container" class="hidden mt-3 pt-3 border-t border-rose-900/40">
                                        <form action="{{ route('berita.reject', $berita) }}" method="POST"
                                            class="space-y-3">
                                            @csrf
                                            <div>
                                                <label for="alasan"
                                                    class="block text-xs font-semibold text-amber-400 mb-1">Alasan Penolakan
                                                    / Catatan Perbaikan:</label>
                                                <textarea name="alasan" id="alasan" rows="3" required
                                                    placeholder="Contoh: Tolong ganti gambar headline dengan resolusi yang lebih jernih..."
                                                    class="w-full bg-rose-950/60 text-rose-100 text-xs p-3 rounded-xl border border-rose-900/40 focus:border-amber-500 focus:outline-none placeholder:text-rose-800 leading-relaxed"></textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition">
                                                    Kirim Penolakan via Email
                                                </button>
                                                <button type="button" onclick="toggleRejectForm()"
                                                    class="px-3 py-1.5 bg-rose-900 text-rose-300 text-xs font-bold rounded-lg hover:bg-rose-800 transition">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endif
                @endauth
            </div>

            <aside class="space-y-8">
                <div class="bg-white rounded-2xl p-6 border border-rose-900/30 shadow-sm sticky top-6">
                    <h2 class="text-lg font-bold text-rose-900 mb-5 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-[#571319] rounded-full"></span>
                        Berita Populer
                    </h2>

                    <div class="space-y-5">
                        @forelse($suggestions->take(4) as $index => $item)
                            <a href="{{ route('berita.show', $item) }}"
                                class="group flex gap-4 items-start pb-4 border-b border-slate-200 last:border-0 last:pb-0">
                                <span
                                    class="text-2xl font-black text-[#571319]/70 group-hover:text-[#571319] transition-colors pt-1">
                                    0{{ $index + 1 }}
                                </span>
                                <div class="space-y-1">
                                    <h3
                                        class="font-bold text-sm text-slate-800 group-hover:text-[#571319] transition-colors line-clamp-2 leading-snug">
                                        {{ $item->title }}
                                    </h3>
                                    <span class="text-[11px] text-[#571319] block">
                                        {{ $item->published_at ? $item->published_at->translatedFormat('j F Y') : 'Draft' }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-[#571319]">Belum ada berita terpopuler saat ini.</p>
                        @endforelse
                    </div>
                </div>
            </aside>

        </div>

        <section class="mt-16 pt-12 border-t border-[#571319]/40">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-8 flex items-center gap-2">
                <span class="w-2 h-6 bg-[#571319] rounded-full"></span>
                Rekomendasi Untuk Anda
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($suggestions as $suggestion)
                    <a href="{{ route('berita.show', $suggestion) }}"
                        class="group bg-white rounded-2xl overflow-hidden border border-red-200 hover:border-[#571319]/60 transition-all duration-300 flex flex-col h-full shadow-sm hover:shadow-lg">

                        <div class="aspect-video relative w-full bg-slate-100 overflow-hidden">
                            @if ($suggestion->image)
                                <img src="{{ asset('storage/berita/' . $suggestion->image) }}"
                                    alt="{{ $suggestion->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-100 text-[#571319]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect width="18" height="18" x="3" y="3" rx="2"
                                            ry="2"></rect>
                                        <path d="M21 15l-3.086-3.086a2 2 0 00-2.828 0L6 21"></path>
                                        <circle cx="9" cy="9" r="2"></circle>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-[#571319] mb-2 block">News</span>
                            <h3
                                class="font-bold text-md text-slate-800 group-hover:text-[#571319] transition-colors line-clamp-2 mb-2 leading-snug">
                                {{ $suggestion->title }}
                            </h3>
                            <p class="text-slate-600 text-xs line-clamp-2 mb-4 leading-relaxed">
                                {{ $suggestion->excerpt ?: Str::limit(strip_tags($suggestion->content), 80) }}
                            </p>
                            <span class="text-[11px] text-[#571319] mt-auto block pt-3 border-t border-slate-200">
                                {{ $suggestion->published_at ? $suggestion->published_at->translatedFormat('j F Y') : 'Draft' }}
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-sm text-[#571319] py-6">Tidak ada rekomendasi berita saat
                        ini.</p>
                @endforelse
            </div>
        </section>

        <div class="mt-16 flex justify-center">
            <a href="{{ route('berita.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white text-sm font-semibold rounded-full transition-all duration-300 shadow-lg shadow-rose-950/40 hover:shadow-rose-900/60 hover:scale-[1.02]">
                <svg xmlns="http://www.w3.org/2000/xl" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
                Kembali ke Hub Berita
            </a>
        </div>
    </main>

       <script src="{{asset('js/Sbt.min.js')}}"></script>
</body>

</html>
