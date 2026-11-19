<section id="pendaftaran" class="py-20 bg-slate-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 overflow-x-hidden">
        <div class="text-center mb-12">
            <span class="text-xs font-bold tracking-[0.3em] uppercase text-rose-500">Formulir Digital</span>

            <div class="mt-3 px-4">
                <h2 class="text-3xl sm:text-4xl font-black text-[#571319] leading-tight">
                    Gabung Pengurus OSIS

                    @if (Auth::check() && !request()->has('preview'))
                        <a href="{{ route('admin.osis.approved') }}" title="Kelola Data"
                            class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 ml-2 rounded-full bg-rose-500 text-white shadow-md shadow-rose-200 transition-all duration-300 hover:bg-rose-600 hover:scale-110 hover:-rotate-12 active:scale-95 align-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z">
                                </path>
                                <line x1="6" y1="10" x2="14" y2="10"></line>
                                <line x1="6" y1="14" x2="10" y2="14"></line>
                            </svg>
                        </a>
                    @endif
                </h2>
            </div>

            <div class="w-16 h-1 bg-rose-500 mx-auto mt-4 rounded-full"></div>
            <p class="text-slate-500 mt-4 text-sm max-w-md mx-auto px-4">
                Langkah awal untuk menjadi bagian dari perubahan. Isi data dirimu dengan lengkap dan benar.
            </p>
        </div>

        {{-- Formulir Pendaftaran --}}
        <form id="register-form"
            class="bg-white rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/60 border border-slate-100 space-y-6">

            <div class="space-y-1.5">
                <label for="nama" class="block text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                <input id="nama" type="text" required
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200 placeholder:text-slate-400"
                    placeholder="Masukkan nama lengkap sesuai absen">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="kelas" class="block text-sm font-bold text-slate-700 ml-1">Kelas</label>
                    <input id="kelas" type="text" required
                        class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200"
                        placeholder="Contoh: X RPL 1">
                </div>
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-bold text-slate-700 ml-1">Email
                        Aktif</label>
                    <input id="email" type="email" required
                        class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200"
                        placeholder="nama@sekolah.sch.id" autocomplete="on">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="no_telp" class="block text-sm font-bold text-slate-700 ml-1">Nomor
                    WhatsApp</label>
                <div class="relative">
                    <span
                        class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm border-r border-slate-200 pr-3">+62</span>
                    <input type="tel" required id="no_telp"
                        class="w-full pl-16 pr-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200"
                        placeholder="8123456789">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="divisi" class="block text-sm font-bold text-slate-700 ml-1">Pilihan
                    Divisi</label>
                <select id="divisi" required
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200 appearance-none cursor-pointer">
                    <option value="" disabled selected>Pilih divisi yang sesuai minatmu...</option>
                    <optgroup label="Inti">
                        <option>Ketua</option>
                        <option>Wakil Ketua</option>
                        <option>Sekretaris</option>
                        <option>Bendahara</option>
                    </optgroup>
                    <optgroup label="Sekbid">
                        <option>Humas</option>
                        <option>ArtMedia</option>
                        <option>Keagamaan</option>
                        <option>Olahraga</option>
                    </optgroup>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="motivasi" class="block text-sm font-bold text-slate-700 ml-1">Apa Motivasi Kamu?</label>
                <textarea id="motivasi" rows="4" required
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200 resize-none"
                    placeholder="Ceritakan alasan dan apa yang ingin kamu capai di OSIS..."></textarea>
            </div>

            @if ($isRegistrationOpen)
                <button type="button" onclick="submitForm()" id="btn-submit"
                    class="w-full bg-gradient-to-r from-rose-500 to-[#571319] hover:bg-rose-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-rose-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                    <span>Kirim Formulir Pendaftaran</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            @else
                <div
                    class="w-full bg-rose-50 border border-rose-100 rounded-2xl p-5 flex items-start gap-4 text-rose-800 shadow-md shadow-rose-100/50">
                    <div class="flex-shrink-0 bg-rose-500 text-white rounded-xl p-2.5 shadow-sm shadow-rose-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-black tracking-tight text-rose-900">Pendaftaran Sudah Ditutup</h4>
                        <p class="text-xs text-rose-600/90 leading-relaxed">
                            Mohon maaf, pengisian formulir pendaftaran pengurus OSIS SMKN 1 Tarumajaya saat ini telah
                            <b>ditutup</b> atau sudah melewati batas waktu seleksi berkas. Terima kasih atas
                            antusiasme kamu!
                        </p>
                    </div>
                </div>
            @endif

            <div id="reg-message"
                class="hidden text-center text-sm font-bold p-3 rounded-2xl shadow-lg shadow-rose-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 items-center justify-center gap-2 animate-pulse">
            </div>

        </form>

        {{-- Formulir Update Link WhatsApp --}}
        @if (Auth::check() && !request()->has('preview') && in_array(Auth::user()->role, ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas']))
            <div
                class="max-w-md mx-auto bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50 p-6 mt-3">
                <form action="{{ route('admin.osis.whatsapp.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="whatsapp_link"
                            class="block text-center text-xs font-bold tracking-[0.3em] uppercase text-rose-600 mb-2">
                            Link Group WhatsApp
                        </label>
                        @php
                            $whatsappLink = app('App\\Http\\Controllers\\OsisController')->getWhatsAppLink();
                        @endphp
                        <input id="whatsapp_link" name="whatsapp_link" type="url" required
                            class="w-full px-5 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all duration-200"
                            placeholder="{{ $whatsappLink }}">
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-rose-500 to-[#571319] hover:bg-rose-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-rose-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                        <span>Simpan Link WhatsApp</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3v4h10V3M12 12v6m-3-3h6" />
                        </svg>
                    </button>

                </form>
            </div>
        @endif
    </div>

    {{-- Modal Success Pendaftaran --}}
    <div id="success-modal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm items-center justify-center z-50 p-4 transition-all duration-300 opacity-0">

        <div id="modal-card"
            class="bg-white rounded-3xl p-8 max-w-md w-full border border-slate-100 shadow-2xl shadow-slate-900/20 text-center transform scale-95 transition-all duration-300">

            <div
                class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-50 border border-emerald-100 mb-6 animate-bounce">
                <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Pendaftaran Berhasil!</h3>
            <p class="mt-3 text-slate-500 text-sm leading-relaxed">
                Selamat, data kamu sudah dianalisis oleh sistem AI! Langkah selanjutnya, silakan bergabung ke grup
                koordinasi WhatsApp resmi calon pengurus OSIS di bawah ini.
            </p>

            <div class="mt-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-400">
                Pastikan nomor WhatsApp kamu aktif untuk info wawancara berikutnya.
            </div>

            <div class="mt-6 space-y-3">
                <a href="{{ route('admin.osis.whatsapp.get') }}" target="_blank" rel="noopener noreferrer"
                    class="w-full bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-green-200/80 transition-all duration-200 transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.69-4.936c-.202-.101-1.192-.588-1.378-.656-.185-.067-.32-.101-.454.101-.134.2-.516.656-.634.793-.118.135-.236.151-.438.051-.2-.101-.845-.312-1.611-.994-.596-.532-1-1.192-1.118-1.393-.118-.2-.013-.308.088-.408.09-.089.202-.236.302-.354.101-.118.135-.2.203-.332.067-.134.034-.252-.017-.354-.05-.101-.454-1.093-.622-1.499-.163-.393-.327-.34-.454-.347-.117-.006-.252-.007-.387-.007-.135 0-.355.05-.541.254-.186.203-.708.692-.708 1.688 0 1.004.73 1.973.832 2.107.102.135 1.436 2.193 3.479 3.076.486.21 1.104.337 1.554.479.404.129.773.11 1.064.067.325-.049 1.192-.487 1.358-.957.165-.47.165-.873.116-.957-.01-.01-.101-.051-.301-.152z" />
                    </svg>
                    <span>Gabung Grup Koordinasi</span>
                </a>

                <button id="close-modal"
                    class="w-full text-slate-400 hover:text-slate-600 font-medium py-2 text-xs transition-colors duration-150 tracking-wide">
                    Nanti Saja, Tutup Jendela
                </button>
            </div>

        </div>
    </div>
</section>
