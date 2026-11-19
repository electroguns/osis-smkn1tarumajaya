<section id="berita"
    class="py-20 bg-gradient-to-br from-[#2d0a0f] via-[#571319] to-[#8b1e2b] relative overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-red-500/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-400/10 blur-3xl rounded-full"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 overflow-x-hidden">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold tracking-[0.2em] uppercase text-white">Agenda</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mt-2">Berita & Kegiatan</h2>
            </div>
            <a href="{{ route('berita.index') }}"
                class="text-white text-sm font-semibold hover:text-rose-300 transition-colors flex items-center gap-2">
                Lihat Lainnya
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($latestBeritas as $item)
                <a href="{{ route('berita.show', $item) }}"
                    class="group rounded-3xl overflow-hidden bg-white hover:border-rose-500/30 transition-all duration-300 flex flex-col justify-between h-full shadow-xl border border-slate-200">
                    <div
                        class="h-48 relative flex items-center justify-center bg-slate-100 overflow-hidden">
                        @if ($item->image)
                            <img src="{{ asset('storage/berita/' . $item->image) }}" alt="{{ $item->title }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/20"></div>
                        @else
                            <div class="absolute inset-0 opacity-20 grid-pattern"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="text-slate-400 relative z-10 group-hover:scale-105 transition-transform duration-500">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2">
                                </rect>
                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                <line x1="3" x2="21" y1="10" y2="10"></line>
                            </svg>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                                Published
                            </span>
                        </div>
                        <h3
                            class="font-bold text-lg text-slate-900 group-hover:text-rose-600 transition-colors line-clamp-2 leading-snug h-[52px] flex flex-col justify-start">
                            {{ $item->title }}
                        </h3>
                        <p class="text-slate-600 text-sm mt-2 leading-relaxed line-clamp-2">
                            {{ $item->excerpt ?: Str::limit(strip_tags($item->content), 100) }}</p>
                        <div class="flex items-center gap-2 mt-4 text-xs text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>{{ $item->published_at->translatedFormat('j F Y') }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-white border border-slate-200 rounded-3xl">
                    <p class="text-slate-500 text-sm">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>