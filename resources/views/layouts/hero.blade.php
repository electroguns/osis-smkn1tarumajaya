<section id="beranda"
    class="hero-bg grid-pattern relative flex items-center justify-center pt-24 pb-16 md:pt-28 md:pb-20 overflow-hidden min-h-screen bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset($siteConfig['hero']['background_image'] ?? 'image/hero.png') }}');">

    <div class="absolute inset-0 bg-black/60"></div>

    @if (Auth::check() && !request()->has('preview') && in_array(auth()->user()->role, ['ketua', 'wakil', 'sekretaris']))
        <button onclick="document.getElementById('hero-bg-input').click()" class="absolute bottom-6 right-6 z-50 pointer-events-auto text-white p-3.5 bg-rose-600 hover:bg-rose-700 rounded-full transition shadow-xl" title="Edit Background">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                <path d="m15 5 4 4"></path>
            </svg>
        </button>
        <input type="file" id="hero-bg-input" accept="image/*" class="hidden" onchange="uploadHeroBackground(this)">
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6 overflow-x-clip relative z-10 text-center mt-12">
        <div class="fade-up stagger-1">
            <span
                class="inline-block px-4 py-1.5 rounded-full text-xs font-bold text-white border border-rose-500/30 mb-6 bg-rose-500/50 tracking-wider uppercase"
                style="text-shadow: 0 0 8px white;">
                {{ $siteConfig['hero']['badge'] ?? 'Periode 2026' }}
            </span>
        </div>

        <h1 id="hero-title"
            class="section-title text-4xl sm:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight fade-up stagger-2"
            style="text-shadow: 0 0 8px #ffff;">
            {!! $siteConfig['hero']['title'] ?? 'OSIS SMKN 1 <span class="bg-clip-text text-rose-600">Tarumajaya</span>' !!}
        </h1>

        <p id="hero-subtitle"
            class="text-base sm:text-xl text-white max-w-2xl mx-auto mb-10 leading-relaxed fade-up stagger-3">
            {{ $siteConfig['hero']['subtitle'] ?? 'Membangun generasi pemimpin masa depan yang inovatif, berintegritas, dan siap berkontribusi untuk kemajuan sekolah.' }}
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 fade-up stagger-4">
            <a href="{{ $siteConfig['hero']['button_primary_link'] ?? '#pendaftaran' }}"
                class="px-8 py-4 rounded-xl font-bold text-white text-sm bg-gradient-to-r from-rose-500 to-rose-600 shadow-lg shadow-rose-500/25 hover:shadow-rose-500/40 transition-all hover:-translate-y-1">
                {{ $siteConfig['hero']['button_primary_text'] ?? 'Daftar Sekarang' }}
            </a>
            <a href="{{ $siteConfig['hero']['button_secondary_link'] ?? '#struktur' }}"
                class="px-8 py-4 rounded-xl font-bold text-white text-sm border border-white hover:border-rose-500 hover:text-rose-400 transition-all backdrop-blur-sm bg-white/5">
                {{ $siteConfig['hero']['button_secondary_text'] ?? 'Lihat Struktur' }}
            </a>
        </div>

        <div
            class="grid grid-cols-3 gap-8 max-w-lg mx-auto mt-16 p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm fade-up stagger-5">
            <div class="text-center">
                <div class="text-2xl md:text-3xl font-black text-white"
                    style="text-shadow: 0 2px #5f1319,
                        0 4px #5f1319,
                        0 6px #5f1319,
                        0 8px #5f1319,
                        0 10px 15px rgba(0,0,0,.4);">
                    4
                </div>
                <div class="text-[10px] md:text-xs uppercase tracking-widest text-white font-bold mt-1">
                    Divisi</div>
            </div>
            
            <div class="text-center border-x border-white/10">
                <div class="text-2xl md:text-3xl font-black text-white"
                    style="text-shadow: 0 0 2px #5f1319,
                        0 4px #5f1319,
                        0 6px #5f1319,
                        0 8px #5f1319,
                        0 10px 15px rgba(0,0,0,.4);">
                    {{ $anggotaCount }}
                </div>
                <div class="text-[10px] md:text-xs uppercase tracking-widest text-white font-bold mt-1">
                    Anggota</div>
            </div>
            <div class="text-center">
                <div class="text-2xl md:text-3xl font-black text-white"
                    style="text-shadow: 0 0 2px #5f1319,
                        0 4px #5f1319,
                        0 6px #5f1319,
                        0 8px #5f1319,
                        0 10px 15px rgba(0,0,0,.4);">
                    {{ $programCount }}
                </div>
                <div class="text-[10px] md:text-xs uppercase tracking-widest text-white font-bold mt-1">
                    Program</div>
            </div>
        </div>
    </div>
</section>