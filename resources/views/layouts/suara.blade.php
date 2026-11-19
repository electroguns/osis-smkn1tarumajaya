    <section id="feedback" class="py-20 bg-[#f8fafc]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 overflow-x-hidden">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-100 mb-4">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-rose-600">Aspirasimu
                        Penting</span>
                </div>
                <div class="flex items-center justify-center gap-3">
                    <h2 class="text-3xl sm:text-4xl font-black text-[#571319]">Kotak Aspirasimu</h2>
                    @if (Auth::check() && !request()->has('preview'))
                        <a href="{{ route('suara.index') }}" title="Kelola Data"
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
                <p class="text-slate-500 mt-3 text-sm">Bantu OSIS berkembang lebih baik melalui saran dan kritikmu.
                </p>
            </div>

            <form id="feedback-form"
                class="bg-white rounded-3xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 space-y-6">

                <!-- Kategori -->
                <div class="space-y-1.5">
                    <label for="fb-category" class="block text-sm font-bold text-slate-700 ml-1">Kategori</label>
                    <div class="relative">
                        <select id="fb-category" name="kategori" required
                            class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih kategori...</option>
                            <option value="saran">💡 Saran & Ide Baru</option>
                            <option value="keluhan">⚠️ Keluhan</option>
                            <option value="laporan">🚩 Laporan</option>
                            <option value="pertanyaan">❓ Pertanyaan</option>
                            <option value="apresiasi">🙌 Apresiasi</option>
                            <option value="aspirasi">📢 Aspirasi Umum</option>
                        </select>
                    </div>
                </div>

                <!-- Wrapper Rating (Hanya tampil di Apresiasi) -->
                <div id="star-rating-wrapper"
                    class="hidden bg-slate-50 p-6 rounded-2xl border border-dashed border-slate-200">
                    <div class="flex justify-between items-center mb-4">
                        <label for="fb-rating" class="text-sm font-bold text-slate-700">Tingkat Kepuasan</label>
                        <span id="rating-display"
                            class="bg-rose-500 text-white px-3 py-1 rounded-full text-xs font-black">5</span>
                    </div>

                    <div class="relative flex items-center">
                        <span class="text-xs font-bold text-slate-400 mr-3">1</span>
                        <input type="range" id="fb-rating" name="rating" min="1" max="10" value="5"
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-rose-500 transition-all">
                        <span class="text-xs font-bold text-slate-400 ml-3">10</span>
                    </div>

                    <p id="rating-text" class="text-center text-[10px] text-slate-400 mt-3 italic font-medium">
                        "Lumayan baik, terus tingkatkan!"
                    </p>
                </div>

                <!-- Isi Pesan -->
                <div id="message-wrapper" class="space-y-1.5">
                    <label for="fb-message" id="label-message" class="block text-sm font-bold text-slate-700 ml-1">Isi
                        Pesan</label>
                    <textarea id="fb-message" name="saran" rows="4" required
                        class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all resize-none"
                        placeholder="Sampaikan isi pikiranmu..."></textarea>
                </div>

                <button type="submit" id="fb-submit"
                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-rose-500 to-[#571319] hover:from-rose-600 hover:to-[#571319] text-white font-bold py-4 rounded-2xl shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-1 active:scale-95">
                    Sampaikan Aspirasi
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </button>


                {{-- Status Alert --}}
                <div id="fb-message-status" class="hidden">

                    {{-- SUCCESS --}}
                    <div id="status-success"
                        class="hidden items-center w-full bg-emerald-50 border border-emerald-100 rounded-2xl p-5 gap-4 text-emerald-800 shadow-md shadow-emerald-100/50">
                        <div
                            class="flex-shrink-0 bg-emerald-500 text-white rounded-xl p-2.5 shadow-sm shadow-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="space-y-1 text-left flex-1">
                            <h4 class="text-sm font-black tracking-tight text-emerald-900">Berhasil Kirim Aspirasimu
                            </h4>
                            <p id="status-success-msg" class="text-xs text-emerald-600/90 leading-relaxed"></p>
                        </div>
                    </div>

                    {{-- ERROR --}}
                    <div id="status-error"
                        class="hidden items-center w-full bg-rose-50 border border-rose-100 rounded-2xl p-5 gap-4 text-rose-800 shadow-md shadow-rose-100/50">
                        <div class="flex-shrink-0 bg-rose-500 text-white rounded-xl p-2.5 shadow-sm shadow-rose-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="space-y-1 text-left flex-1">
                            <h4 class="text-sm font-black tracking-tight text-rose-900">Sistem Mendeteksi Pelanggaran
                            </h4>
                            <p id="status-error-msg" class="text-xs text-rose-600/90 leading-relaxed"></p>
                            <div id="status-error-reason"
                                class="hidden mt-1.5 pt-1.5 border-t border-rose-200/60 text-[11px] italic font-semibold text-rose-700">
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const catSelect = document.getElementById('fb-category');
                const starWrap = document.getElementById('star-rating-wrapper');
                if (catSelect && starWrap) {
                    catSelect.addEventListener('change', function() {
                        if (this.value === 'apresiasi') {
                            starWrap.classList.remove('hidden');
                        } else {
                            starWrap.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
    </section>
