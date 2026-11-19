<style>
    @keyframes bellPulse {
        0% {
            transform: scale(1);
            opacity: 0.6;
        }

        50% {
            transform: scale(1.12);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 0.6;
        }
    }

    .bell-pulse-active {
        animation: bellPulse 2s infinite ease-in-out;
    }
</style>

<nav id="navbar"
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-[#5f1319] backdrop-blur-xl border-b border-white/5 overflow-visible">
    <div class="max-w-full px-4 overflow-x-clip">
        <div class="flex items-center justify-between h-16 min-w-0 gap-4">

            <div class="flex items-center gap-3 flex-shrink-0 min-w-0">
                <img class="w-10 h-10 object-contain rounded-lg flex-shrink-0" loading="lazy"
                    src="{{ asset('image/logosmkn1trmj.png') }}" alt="Logo SMKN 1 Tarumajaya">

                <div class="flex flex-col leading-tight min-w-0 mr-2">
                    <span class="font-black text-white text-xs sm:text-sm tracking-tight uppercase whitespace-nowrap">
                        OSIS SMKN 1
                    </span>
                    <span
                        class="text-[9px] sm:text-[10px] text-white font-bold tracking-widest uppercase whitespace-nowrap">
                        Tarumajaya
                    </span>
                </div>

                <div id="user-auth-bar" class="flex items-center gap-2 flex-shrink-0">
                    <span id="user-role-label" class="text-slate-200 text-xs hidden sm:block">Guest</span>
                    <button id="auth-button" aria-label="Autentikasi Pengguna" onclick="handleAuthButton()"
                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-rose-500 text-white hover:bg-rose-400 shadow-lg shadow-rose-500/20 transition-all active:scale-95">
                        Login
                    </button>
                </div>

                {{-- Desktop Bell --}}
                <button id="notification-bell-desktop"
                    class="hidden lg:flex p-1.5 rounded-full hover:bg-white/10 transition-all duration-200 group relative"
                    onclick="toggleNotifications()" title="Notifikasi">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="bell-icon text-white-400 group-hover:scale-110 transition-transform">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                    </svg>
                    <span
                        class="bell-dot hidden absolute top-1 right-1 w-2 h-2 bg-white rounded-full ping"></span>
                </button>
            </div>

            <div class="flex items-center gap-4 flex-shrink-0">
                {{-- Mobile Bell --}}
                <button id="notification-bell-mobile"
                    class="flex lg:hidden p-1.5 rounded-full hover:bg-white/10 transition-all duration-200 group relative"
                    onclick="toggleNotifications()" title="Notifikasi">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="bell-icon text-white-400 group-hover:scale-110 transition-transform">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                    </svg>
                    <span class="bell-dot hidden absolute top-1 right-1 w-2 h-2 bg-white rounded-full"></span>
                </button>
                
                {{-- Desktop Menu --}}
                <div class="hidden lg:flex items-center gap-6 xl:gap-8">
                    <a href="#beranda"
                        class="group flex items-center gap-2 text-slate-300 hover:text-rose-400 text-sm font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        <span>Beranda</span>
                    </a>

                    {{-- DROPDOWN INFORMASI (Desktop) --}}
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-slate-300 hover:text-rose-400 text-sm font-medium transition-all py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            <span>Informasi</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 group-hover:rotate-180">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        
                        {{-- Dropdown Menu List --}}
                        <div class="absolute left-0 mt-0 w-52 bg-[#5f1319] border border-white/10 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 py-2">
                            <a href="#visi-misi" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-rose-400 transition-all">Visi & Misi</a>
                            <a href="#struktur" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-rose-400 transition-all">Struktur Organisasi</a>
                            <a href="#berita" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-rose-400 transition-all">Berita Kegiatan</a>
                            <a href="#keuangan" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-rose-400 transition-all">Statistik Keuangan</a>
                            <a href="#arsip" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-rose-400 transition-all">Arsip Foto</a>
                        </div>
                    </div>

                    <a href="#pendaftaran"
                        class="group flex items-center gap-2 text-slate-300 hover:text-rose-400 text-sm font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="19" y1="8" x2="19" y2="14" />
                            <line x1="22" y1="11" x2="16" y2="11" />
                        </svg>
                        <span>Pendaftaran</span>
                    </a>

                    <a href="#feedback"
                        class="group flex items-center gap-2 text-slate-300 hover:text-rose-400 text-sm font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
                        </svg>
                        <span>Aspirasi</span>
                    </a>

                    @if (Auth::check() && !request()->has('preview'))
                        <a href="#role-panel"
                            class="group flex items-center gap-2 text-slate-300 hover:text-rose-400 text-sm font-medium transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 3h18v18H3z" />
                                <path d="M3 9h18" />
                                <path d="M9 21V9" />
                            </svg>
                            <span>Panel</span>
                        </a>
                    @endif

                    <a href="#kontak"
                        class="group flex items-center gap-2 text-slate-300 hover:text-rose-400 text-sm font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span>Kontak</span>
                    </a>
                </div>

                <button id="mobile-toggle" aria-label="Buka Menu Navigasi" class="lg:hidden p-2 text-white hover:bg-white/10 rounded-lg flex-shrink-0"
                    onclick="toggleMobileMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu"
        class="hidden lg:hidden bg-[#5f1319]/95 backdrop-blur-lg absolute top-[64px] left-0 w-full p-6 space-y-2 shadow-2xl border-b border-white/10 z-[60] max-h-[calc(100vh-64px)] overflow-y-auto">

        <a href="#beranda" onclick="closeMobileMenu()"
            class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-white-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </div>
            <span class="text-sm font-semibold tracking-wide">Beranda</span>
        </a>

        {{-- Kelompok Menu Informasi di Mobile --}}
        <div class="px-4 py-2 text-xs font-bold text-rose-400 uppercase tracking-widest">Informasi OSIS</div>
        
        <a href="#visi-misi" onclick="closeMobileMenu()"
            class="flex items-center gap-4 ml-3 px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <span class="text-sm font-medium">▪ Visi & Misi</span>
        </a>
        <a href="#struktur" onclick="closeMobileMenu()"
            class="flex items-center gap-4 ml-3 px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <span class="text-sm font-medium">▪ Struktur Organisasi</span>
        </a>
        <a href="#berita" onclick="closeMobileMenu()"
            class="flex items-center gap-4 ml-3 px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <span class="text-sm font-medium">▪ Berita & Kegiatan</span>
        </a>
        <a href="#keuangan" onclick="closeMobileMenu()"
            class="flex items-center gap-4 ml-3 px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <span class="text-sm font-medium">▪ Statistik Keuangan</span>
        </a>
        <a href="#arsip" onclick="closeMobileMenu()"
            class="flex items-center gap-4 ml-3 px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <span class="text-sm font-medium">▪ Arsip Foto</span>
        </a>

        <div class="border-t border-white/5 my-2"></div>

        <a href="#pendaftaran" onclick="closeMobileMenu()"
            class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-white-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="4" cy="7" r="4" />
                    <line x1="19" y1="8" x2="19" y2="14" />
                    <line x1="22" y1="11" x2="16" y2="11" />
                </svg>
            </div>
            <span class="text-sm font-semibold tracking-wide">Pendaftaran</span>
        </a>

        <a href="#feedback" onclick="closeMobileMenu()"
            class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-white-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
                </svg>
            </div>
            <span class="text-sm font-semibold tracking-wide">Aspirasi</span>
        </a>

		@if (Auth::check() && !request()->has('preview'))
        <a href="#role-panel" onclick="closeMobileMenu()"
            class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-white-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 3h18v18H3z" />
                    <path d="M3 9h18" />
                    <path d="M9 21V9" />
                </svg>
            </div>
            <span class="text-sm font-semibold tracking-wide">Panel</span>
        </a>
		@endif

        <a href="#kontak" onclick="closeMobileMenu()"
            class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all">
            <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-white-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
            </div>
            <span class="text-sm font-semibold tracking-wide">Kontak</span>
        </a>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        updateBellUI();
    });

    function updateBellUI() {
        const isSubscribed = localStorage.getItem('osis_subscribed_email');
        const bellIcons = document.querySelectorAll('.bell-icon');
        const bellDots = document.querySelectorAll('.bell-dot');

        if (isSubscribed) {
            bellIcons.forEach(icon => {
                icon.classList.add('bell-pulse-active');
                icon.classList.add('text-white');
            });
            bellDots.forEach(dot => dot.classList.remove('hidden'));
        } else {
            bellIcons.forEach(icon => {
                icon.classList.remove('bell-pulse-active');
                icon.classList.add('text-white');
            });
            bellDots.forEach(dot => dot.classList.add('hidden'));
        }
    }

    const toggleNotifications = async () => {
        const currentEmail = localStorage.getItem('osis_subscribed_email');

        if (currentEmail) {
            const result = await Swal.fire({
                title: 'Unsubscribe?',
                text: `Apakah kamu ingin berhenti berlangganan notifikasi untuk email: ${currentEmail}?`,
                icon: 'warning',
                background: '#5f1319',
                color: '#e2e8f0',
                showCancelButton: true,
                confirmButtonText: 'Ya, Berhenti',
                confirmButtonColor: '#ef4444',
                cancelButtonText: 'Batal',
                cancelButtonColor: '#475569',
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('/unsubscribe-bell', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: currentEmail
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) throw new Error(data.message || 'Gagal unsubscribe');

                    localStorage.removeItem('osis_subscribed_email');
                    updateBellUI();

                    Swal.fire({
                        icon: 'info',
                        title: 'Berhasil Unsubscribe',
                        text: 'Kamu tidak akan menerima notifikasi berita baru lagi.',
                        background: '#5f1319',
                        color: '#e2e8f0',
                        confirmButtonColor: '#0ea5e9',
                    });

                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memproses permintaan.',
                        background: '#5f1319',
                        color: '#e2e8f0',
                        confirmButtonColor: '#ef4444'
                    });
                }
            }
            return;
        }

        const {
            value: email
        } = await Swal.fire({
            title: 'Subscribe Now!',
            html: 'Masukkan email untuk menerima berita terbaru <br> <b>OSIS SMKN 1 TARUMAJAYA</b>',
            input: 'email',
            inputPlaceholder: 'contoh@email.com',
            background: '#5f1319',
            color: '#e2e8f0',
            confirmButtonText: 'Subscribe',
            confirmButtonColor: '#0ea5e9',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#475569',
            inputValidator: (value) => {
                if (!value) return 'Email wajib diisi!';
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) return 'Format email tidak valid!';
            }
        });

        if (!email) return;

        try {
            const response = await fetch('/subscribe-bell', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    email: email
                })
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan');

            localStorage.setItem('osis_subscribed_email', email);
            updateBellUI();

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                background: '#5f1319',
                color: '#e2e8f0',
                confirmButtonColor: '#0ea5e9',
            });

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal mengaktifkan notifikasi',
                background: '#5f1319',
                color: '#e2e8f0',
                confirmButtonColor: '#ef4444'
            });
        }
    }
</script>
