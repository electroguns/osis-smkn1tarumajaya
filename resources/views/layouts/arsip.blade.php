 <section id="arsip" class="py-24 relative overflow-hidden" style="background: #f8fafc;">
     <div class="absolute -top-16 -right-16 w-48 h-48 bg-sky-100 rounded-full blur-2xl opacity-50"></div>
     <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-indigo-100 rounded-full blur-2xl opacity-50"></div>

     <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10 overflow-x-hidden">
         <div
             class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm mb-6">
             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                 class="text-rose-500">
                 <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                 <circle cx="9" cy="9" r="2" />
                 <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
             </svg>
             <span class="text-[10px] font-bold tracking-widest uppercase text-slate-600">Dokumentasi
                 Kegiatan</span>
         </div>

         <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">Arsip Foto & Momen</h2>
         <p class="text-slate-500 mt-4 mb-10 text-lg max-w-2xl mx-auto leading-relaxed">
             Lihat kumpulan dokumentasi kegiatan, rapat, dan momen seru perjalanan OSIS SMKN 1 Tarumajaya dari
             masa ke masa.
         </p>

         <div class="relative inline-block group">
      <div
    class="absolute -inset-1 bg-rose-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition">
</div>
    
             <a href="{{ route('arsip.index') }}"
                 class="relative flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white font-bold rounded-2xl transition-all duration-300 transform group-hover:scale-[1.02] active:scale-95 shadow-2xl">
                 <span class="tracking-wide">Jelajahi Arsip Lengkap</span>
                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                     stroke-linejoin="round" class="transition-transform group-hover:translate-x-1">
                     <path d="M5 12h14" />
                     <path d="m12 5 7 7-7 7" />
                 </svg>
             </a>
         </div>

         <div class="mt-12 flex justify-center items-center gap-8 text-slate-400">
                <div class="flex flex-col">
                    <span class="text-slate-900 font-bold text-xl">{{ $arsipCount ?? 0 }}</span>
                    <span class="text-[10px] uppercase tracking-wider font-semibold">Foto Kegiatan</span>
                </div>
             <div class="w-px h-8 bg-slate-200"></div>
                <div class="flex flex-col">
                    <span class="text-slate-900 font-bold text-xl">{{ $albumCount ?? 0 }}</span>
                 <span class="text-[10px] uppercase tracking-wider font-semibold">Album Besar</span>
             </div>
         </div>
     </div>
 </section>