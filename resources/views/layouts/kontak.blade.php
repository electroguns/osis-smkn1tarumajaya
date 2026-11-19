<section id="kontak" class="py-20 bg-[#571319] relative overflow-x-hidden">
        <div
            class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-rose-500/5 blur-[60px] rounded-full pointer-events-none">
        </div>     <div class="max-w-5xl mx-auto px-4 sm:px-6 relative z-10 overflow-x-hidden">
         <div class="text-center mb-16">
             <span class="text-xs font-bold tracking-[0.3em] uppercase text-rose-400">Hubungi Kami</span>
             <h2 class="text-3xl sm:text-4xl font-black text-white mt-3">Informasi Kontak</h2>
             <div class="w-12 h-1 bg-rose-500 mx-auto mt-4 rounded-full"></div>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="contact-cards">
             @foreach($contacts as $contact)
             <div
                 class="group p-8 rounded-3xl bg-rose-900/50 border border-white hover:border-rose-500/50 transition-all duration-300 text-center {{ $contact['order'] == 2 ? 'md:scale-110 md:z-20 shadow-xl shadow-black/20' : 'z-10' }} relative">

                 <div class="flex items-center justify-center mb-3 relative z-10">
                     <div
                        class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center mx-auto group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white text-rose-400 transition-all duration-300">
                         @if($contact['type'] == 'email')
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                             <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                             <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                         </svg>
                         @elseif($contact['type'] == 'telepon')
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                         @else
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                             <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                             <circle cx="12" cy="10" r="3"></circle>
                         </svg>
                         @endif
                     </div>
                 </div>
                 <p class="text-white font-bold mb-1" id="contact-label-{{ $contact['id'] }}">{{ $contact['label'] }}</p>
                 <p class="text-slate-400 text-sm break-all" id="contact-value-{{ $contact['id'] }}" contenteditable="{{ Auth::check() && !request()->has('preview') ? 'true' : 'false' }}" onblur="saveContactValue({{ $contact['id'] }}, this)" onkeydown="handleContactKeydown(event, {{ $contact['id'] }}, this)">{{ $contact['value'] }}</p>
             </div>
             @endforeach
         </div>

         <div class="mt-12 rounded-[2rem] overflow-hidden border border-white shadow-2xl group relative">
             <div
                 class="absolute inset-0 bg-rose-500/5 pointer-events-none group-hover:bg-transparent transition-colors duration-500">
             </div>
             <iframe
                 src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.7925475696684!2d106.9851540745303!3d-6.158532860359409!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a20c558a1c7df%3A0xc9b612f870551fca!2sSMKN%201%20Tarumajaya!5e0!3m2!1sid!2sid!4v1777212197492!5m2!1sid!2sid"
                 width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                 referrerpolicy="no-referrer-when-downgrade"
                 title="LOKASI SMKN 1 TARUMAJAYA"
                 class="grayscale active:grayscale-0 hover:grayscale-0 transition-all duration-700"></iframe>
         </div>
     </div>

    <script src="{{ asset('js/K.min.js') }}"></script>
</section>