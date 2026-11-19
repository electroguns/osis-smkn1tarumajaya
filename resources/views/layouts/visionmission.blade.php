<div id="visi-misi" class="min-h-screen bg-[#571319] px-4 sm:px-6 md:px-10 py-10 text-slate-200 font-sans">
    <section
        class="w-full mx-auto bg-[#571319]/70 rounded-[2.5rem] border border-rose-800/80 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.7)] p-6 sm:p-8 md:p-12 relative overflow-hidden backdrop-blur-md">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-rose-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-rose-700/10 rounded-full blur-[100px] pointer-events-none"></div>

        {{-- Header Panel --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 relative z-10 gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white ">
                        Misi <span class="text-rose-400">&</span> Visi
                    </h1>
                    @if (Auth::user() && !request()->has('preview') && in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris']))
                        <a href="{{ route('visionmission.index') }}" title="Kelola Data"
                            class="flex items-center justify-center w-11 h-11 rounded-full bg-rose-500 text-white shadow-md shadow-rose-200 transition-all duration-300 hover:bg-rose-600 hover:scale-110 hover:-rotate-12 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z">
                                </path>
                                <line x1="6" y1="10" x2="14" y2="10"></line>
                                <line x1="6" y1="14" x2="10" y2="14"></line>
                            </svg>
                        </a>
                    @endif
                </div>
                <p class="text-slate-400 text-sm md:text-base max-w-md ">
                    Struktur dinamis fondasi pergerakan roda organisasi kepengurusan.
                </p>
            </div>

            <div
                class="self-start md:self-center px-5 py-2.5 border border-rose-500/30 rounded-full bg-[#571319]/80 backdrop-blur-md shadow-[0_0_30px_-5px_rgba(238,34,34,0.6)]">
                <span
                    class="text-xs md:text-sm font-black uppercase tracking-[0.2em] text-white block whitespace-nowrap">
                    OSIS SMKN 1 Tarumajaya
                </span>
            </div>
        </div>

        {{-- Grid Utama Visi & Misi --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-4 relative z-10">

            {{-- ==================== MISI CARD (KIRI) ==================== --}}
            <div class="flex flex-col gap-4">
                {{-- Komponen Bentuk L-Terbalik Misi --}}
                <div class="grid grid-cols-2 grid-rows-[1fr_45px] min-h-[360px]">
                    <div
                        class="col-span-2 bg-rose-950/45 border-t border-x border-white/60 p-6 sm:p-8 rounded-t-[2rem] rounded-br-[2rem] flex flex-col justify-center">
                        <div class="space-y-3">
                            @forelse ($landingMisi as $misi)
                                <div
                                    class="flex gap-3 items-center bg-rose-950/45 p-3 rounded-xl border border-rose-800/40">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-[#571319]/80 text-white font-bold text-xs shrink-0 border border-white/20">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="text-white text-base md:text-lg leading-relaxed">{{ $misi->description }}</div>
                                </div>
                            @empty
                                <p class="text-black italic text-sm py-2">Misi pengurus belum diisi.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-rose-950/45 border-b border-l border-white/60 rounded-b-[2rem]"></div>
                    <div class="bg-transparent"></div>
                </div>

                {{-- Tab Tombol MISI --}}
                <div class="flex items-center gap-6 sm:gap-8 md:gap-10 w-full">
                    <div
                        class="w-[160px] sm:w-[180px] md:w-[200px] bg-[#571319]/90 border border-rose-700/60 py-3 px-4 rounded-full shadow-lg flex items-center justify-center gap-2 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white shrink-0">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3M12 19v3M2 12h3M19 12h3" />
                        </svg>
                        <span
                            class="text-base sm:text-lg md:text-xl font-black tracking-widest text-white uppercase">Misi</span>
                    </div>
                </div>
            </div>

            {{-- ==================== VISI CARD (KANAN) ==================== --}}
            <div class="flex flex-col gap-4 justify-start relative z-20">
                <div class="flex flex-row-reverse items-center gap-6 sm:gap-8 md:gap-10 w-full">
                    {{-- Tab Tombol VISI --}}
                    <div
                        class="w-[160px] sm:w-[180px] md:w-[200px] bg-[#571319]/90 border border-rose-700/60 py-3 px-4 rounded-full shadow-lg flex items-center justify-center gap-2 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.24 7.76l-2.12 5.3-5.3 2.12 2.12-5.3 5.3-2.12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2M12 19v2M3 12h2M19 12h2" />
                        </svg>
                        <span
                            class="text-base sm:text-lg md:text-xl font-black tracking-widest text-white uppercase">Visi</span>
                    </div>
                </div>

                {{-- Komponen Bentuk L-Tidur Visi --}}
                <div class="grid grid-cols-2 grid-rows-[45px_1fr] min-h-[360px]">
                    <div class="bg-transparent"></div>
                    <div class="bg-rose-950/45 border-t border-r border-white/60 rounded-t-[2rem]"></div>

                    <div
                        class="col-span-2 bg-rose-950/45 border-b border-x border-white/60 p-6 sm:p-8 rounded-b-[2rem] rounded-tl-[2rem] flex flex-col justify-center">
                        <div class="space-y-4">
                            @forelse ($landingVisi as $visi)
                                <div class="bg-rose-950/45 p-5 rounded-xl border-l-4 border-rose-500 shadow-inner">
                                    <p class="text-slate-200 leading-relaxed text-lg md:text-xl font-medium italic">
                                        "{{ $visi->description }}"
                                    </p>
                                </div>
                            @empty
                                <p class="text-slate-500 italic text-lg py-2">Visi pengurus belum diisi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
