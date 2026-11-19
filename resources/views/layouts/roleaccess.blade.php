<section id="role-panel" class="relative overflow-hidden py-10 {{ Auth::check() ? '' : 'hidden' }}">
    <!-- Background Decor Grid & Apple Ambient Liquid Blobs -->
    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 inset-0 pointer-events-none overflow-hidden bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:28px_28px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)]">
    </div>

    {{-- Subtle Apple Ambient Liquid Light behind the whole section --}}
    <div class="absolute top-10 left-1/4 w-[500px] h-[500px] bg-rose-500/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 right-1/4 w-[450px] h-[450px] bg-[#571319]/10 rounded-full blur-[130px] pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 overflow-x-hidden">
        @auth
            @php
                $user = auth()->user();
                $currentRole = $user ? $user->role : null;

                $roleLabels = [
                    'ketua' => 'Ketua OSIS',
                    'wakil' => 'Wakil Ketua OSIS',
                    'sekretaris' => 'Sekretaris',
                    'bendahara' => 'Bendahara',
                    'ketua_divisi_humas' => 'Ketua Divisi Humas',
                    'ketua_divisi_agama' => 'Ketua Divisi Agama',
                    'ketua_divisi_olahraga' => 'Ketua Divisi Olahraga',
                    'ketua_divisi_artmedia' => 'Ketua Divisi Art & Media',
                    'anggota_divisi_humas' => 'Anggota Divisi Humas',
                    'anggota_divisi_agama' => 'Anggota Divisi Agama',
                    'anggota_divisi_olahraga' => 'Anggota Divisi Olahraga',
                    'anggota_divisi_artmedia' => 'Anggota Divisi Art & Media',
                ];

                $allPengurusRoles = array_keys($roleLabels);

                $dashboardAccess = [
                    'finance' => [
                        'title' => 'Review Keuangan',
                        'allowed' => ['ketua', 'wakil', 'sekretaris', 'bendahara', 'ketua_divisi_humas', 'ketua_divisi_agama', 'ketua_divisi_olahraga', 'ketua_divisi_artmedia'],
                    ],
                    'admin_osis' => [
                        'title' => 'Manajemen OSIS',
                        'allowed' => ['ketua', 'wakil', 'sekretaris', 'bendahara', 'ketua_divisi_humas', 'anggota_divisi_humas'],
                    ],
                    'calon_osis' => [
                        'title' => 'Data Calon OSIS',
                        'allowed' => ['ketua', 'wakil', 'sekretaris', 'bendahara', 'ketua_divisi_humas', 'ketua_divisi_agama', 'ketua_divisi_olahraga', 'ketua_divisi_artmedia'],
                    ],
                    'event' => [
                        'title' => 'Manajemen Event',
                        'allowed' => $allPengurusRoles,
                    ],
                    'reports' => [
                        'title' => 'Pelacakan Dokumen',
                        'allowed' => $allPengurusRoles,
                    ],
                    'visionmission' => [
                        'title' => 'Visi & Misi',
                        'allowed' => ['ketua', 'wakil', 'sekretaris'],
                    ],
                    'struktur' => [
                        'title' => 'Struktur Pengurus',
                        'allowed' => $allPengurusRoles,
                    ],
                    'suara' => [
                        'title' => 'Kotak Suara Siswa',
                        'allowed' => ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas'],
                    ],
                    'berita' => [
                        'title' => 'Berita & Kegiatan',
                        'allowed' => null,
                    ],
                    'arsip_index' => [
                        'title' => 'Arsip Foto Kenangan',
                        'allowed' => null,
                    ],
                    'arsip_create' => [
                        'title' => 'Upload Kenangan Baru',
                        'allowed' => ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'ketua_divisi_artmedia', 'anggota_divisi_humas', 'anggota_divisi_artmedia'],
                    ],
                    'logbook' => [
                        'title' => 'Logbook Aktivitas',
                        'allowed' => ['ketua', 'wakil'],
                    ],
                ];

                $canAccess = function($key) use ($dashboardAccess, $currentRole) {
                    $allowed = $dashboardAccess[$key]['allowed'] ?? null;
                    return ($allowed === null) || ($currentRole && in_array($currentRole, $allowed));
                };

                $getAllowedLabels = function($key) use ($dashboardAccess, $roleLabels) {
                    $allowed = $dashboardAccess[$key]['allowed'] ?? [];
                    if (!$allowed) return [];
                    return array_map(function($r) use ($roleLabels) {
                        return $roleLabels[$r] ?? ucwords(str_replace('_', ' ', $r));
                    }, $allowed);
                };

                $isFullAccess = in_array($currentRole, ['ketua', 'wakil']);
            @endphp

            {{-- ================= HEADER DASHBOARD ADMINISTRATOR ================= --}}
            <div class="mb-8">
                <div class="max-w-xl">
                    <div class="apple-glass-pill mb-3">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span class="text-[11px] font-extrabold tracking-[0.2em] uppercase text-rose-700">
                            Administrator
                        </span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                        Dashboard
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 via-rose-700 to-[#571319]">
                            Administrator
                        </span>
                    </h2>

                    <p class="text-slate-600 mt-2.5 text-sm sm:text-base leading-relaxed">
                        Selamat datang,
                        <span class="font-bold text-rose-600">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-rose-100/80 border border-rose-200/50 text-rose-700 uppercase tracking-wide ml-1">
                            {{ $roleLabels[$currentRole] ?? str_replace('_', ' ', Auth::user()->role ?? 'Admin') }}
                        </span>.
                        Semua sistem validasi dan monitoring organisasi aktif.
                    </p>
                </div>
            </div>

            {{-- ================= INFO OTORITAS: WARNING BAR SEBARIS SENDIRI ================= --}}
            <div class="apple-glass-card mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-3.5 rounded-2xl text-amber-950 !bg-gradient-to-r !from-amber-500/[0.08] !via-rose-500/[0.05] !to-white/60 !border-amber-300/40">
                <div class="flex items-center gap-2.5 shrink-0">
                    <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-[11px] font-black uppercase tracking-wider text-amber-900">Info Otoritas</span>
                </div>
                <div class="h-3.5 w-px bg-amber-300/60 hidden sm:block shrink-0"></div>
                <p class="text-xs text-slate-700 font-medium leading-relaxed flex-1">
                    Role akun Anda adalah <strong class="text-rose-700">{{ $roleLabels[$currentRole] ?? ucfirst(str_replace('_', ' ', $currentRole)) }}</strong>. Akses fitur dashboard disesuaikan dengan wewenang resmi organisasi Anda. Modul yang terkunci dapat diklik untuk melihat informasi izin role.
                </p>
                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-white/90 border border-amber-200/80 text-amber-900 uppercase tracking-widest shrink-0 self-start sm:self-auto shadow-sm">
                    {{ $isFullAccess ? 'Full Access' : ($roleLabels[$currentRole] ?? 'Restricted Access') }}
                </span>
            </div>

            {{-- ================= PUSAT KENDALI & MODUL TERPADU (ALL-IN-ONE 3-KOLOM LIQUID GLASS) ================= --}}
            <div class="mb-12">
                <div class="apple-glass-panel group relative rounded-[2.5rem] p-6 sm:p-10 overflow-hidden transition-all">
                    {{-- Specular Highlight Hairline --}}
                    <div class="apple-glass-specular"></div>

                    {{-- Liquid Glass Ambient Background Decor --}}
                    <div class="absolute -top-28 -right-28 w-96 h-96 bg-gradient-to-br from-rose-500/15 via-rose-300/10 to-transparent rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-28 -left-28 w-96 h-96 bg-gradient-to-tr from-[#571319]/15 via-rose-400/10 to-transparent rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10">
                        {{-- Header Pusat Kendali (Renamed Dashboard Utama) --}}
                        <div class="mb-8">
                            <div class="apple-glass-pill mb-2.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-700">Pusat Kontrol</span>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                                <span>⚡ Pusat Kendali & Modul Terpadu</span>
                            </h3>
                            <p class="text-slate-500 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
                                Akses terpadu ke seluruh modul monitoring organisasi, keuangan, administrasi anggota, aspirasi siswa, kearsipan foto, hingga pratinjau landing page publik.
                            </p>
                        </div>

                        {{-- Grid 3 Kolom ke Kanan (Liquid Glass Style dengan Aksen Merah) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            <!-- 1. Review Keuangan -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <span class="text-xl font-black font-mono">$</span>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Review Keuangan
                                            </h4>
                                        </div>
                                        @if(!$canAccess('finance'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>

                                    @php
                                        $percentage = ($totalIncome ?? 0) > 0 ? min(100, max(0, (($balance ?? 0) / $totalIncome) * 100)) : 0;
                                    @endphp

                                    <div class="apple-glass-subcard my-2">
                                        <div class="flex justify-between items-end mb-1.5">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Saldo Saat Ini</span>
                                            <span class="text-base font-black text-emerald-600">Rp {{ number_format($balance ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-200/80 rounded-full overflow-hidden mb-2">
                                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-700 shadow-sm" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-[10px] pt-1.5 border-t border-slate-200/50">
                                            <div>
                                                <span class="text-slate-400 block font-medium">Masuk</span>
                                                <span class="font-bold text-cyan-600">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block font-medium">Keluar</span>
                                                <span class="font-bold text-rose-600">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('finance'))
                                        <a href="{{ route('finance.index') }}" aria-label="Lihat Statistik Keuangan" class="apple-glass-btn">
                                            <span>Statistik Keuangan</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['finance']['title']) }}', {{ json_encode($getAllowedLabels('finance')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Statistik Keuangan (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 2. Manajemen OSIS -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="20" x2="12" y2="10" />
                                                    <line x1="18" y1="20" x2="18" y2="4" />
                                                    <line x1="6" y1="20" x2="6" y2="16" />
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Manajemen OSIS
                                            </h4>
                                        </div>
                                        @if(!$canAccess('admin_osis'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Update susunan divisi, anggota terverifikasi, dan program kerja kepengurusan.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('admin_osis'))
                                        <a href="{{ route('admin.osis.approved') }}" aria-label="Lihat Data OSIS" class="apple-glass-btn">
                                            <span>Data OSIS</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['admin_osis']['title']) }}', {{ json_encode($getAllowedLabels('admin_osis')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Data OSIS (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 3. Data Calon OSIS -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                                                    <circle cx="9" cy="7" r="4" />
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Data Calon OSIS
                                            </h4>
                                        </div>
                                        @if(!$canAccess('calon_osis'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Analisis pendaftar otomatis, seleksi berkas, dan sistem scoring berbasis AI.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('calon_osis'))
                                        <a href="{{ route('admin.osis.history') }}" aria-label="Lihat Data Calon OSIS" class="apple-glass-btn">
                                            <span>Data Calon OSIS</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['calon_osis']['title']) }}', {{ json_encode($getAllowedLabels('calon_osis')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Data Calon OSIS (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 4. Manajemen Event -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Manajemen Event
                                            </h4>
                                        </div>
                                        @if(!$canAccess('event'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Kelola agenda kegiatan, absensi kehadiran QR Code, dan pantau rekapitulasi.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('event'))
                                        <a href="{{ route('event.index') }}" aria-label="Lihat Manajemen Event" class="apple-glass-btn">
                                            <span>Manajemen Event</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['event']['title']) }}', {{ json_encode($getAllowedLabels('event')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Manajemen Event (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 5. Pelacakan Dokumen -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Pelacakan Dokumen
                                            </h4>
                                        </div>
                                        @if(!$canAccess('reports'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Tracking status alur proposal, surat pengesahan, dan laporan kegiatan organisasi.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('reports'))
                                        <a href="{{ route('reports.index') }}" aria-label="Lihat Tracking Laporan" class="apple-glass-btn">
                                            <span>Tracking Laporan</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['reports']['title']) }}', {{ json_encode($getAllowedLabels('reports')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Tracking Laporan (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 6. Visi & Misi -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <circle cx="12" cy="12" r="6"></circle>
                                                    <circle cx="12" cy="12" r="2"></circle>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Visi & Misi
                                            </h4>
                                        </div>
                                        @if(!$canAccess('visionmission'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Kelola rumusan visi, misi, dan sasaran strategis program kepengurusan OSIS.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('visionmission'))
                                        <a href="{{ route('visionmission.index') }}" aria-label="Lihat Visi & Misi" class="apple-glass-btn">
                                            <span>Kelola Visi Misi</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['visionmission']['title']) }}', {{ json_encode($getAllowedLabels('visionmission')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Kelola Visi Misi (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 7. Struktur Pengurus -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Struktur Pengurus
                                            </h4>
                                        </div>
                                        @if(!$canAccess('struktur'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Bagan organisasi, susunan pengurus tiap divisi, dan pencetakan ID Card pengurus.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('struktur'))
                                        <a href="{{ route('struktur.index') }}" aria-label="Lihat Struktur Pengurus" class="apple-glass-btn">
                                            <span>Struktur Organisasi</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['struktur']['title']) }}', {{ json_encode($getAllowedLabels('struktur')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Struktur Organisasi (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 8. Kotak Suara Siswa -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Kotak Suara Siswa
                                            </h4>
                                        </div>
                                        @if(!$canAccess('suara'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Pantau saran anonim, aspirasi, dan kritik membangun dari seluruh siswa.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('suara'))
                                        <a href="{{ route('suara.index') }}" aria-label="Lihat Kotak Suara" class="apple-glass-btn">
                                            <span>Kotak Aspirasi</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['suara']['title']) }}', {{ json_encode($getAllowedLabels('suara')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Kotak Aspirasi (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 9. Berita & Kegiatan -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Berita & Kegiatan
                                            </h4>
                                        </div>
                                        @if(!$canAccess('berita'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Publikasikan pengumuman resmi, artikel dokumentasi, dan warta kegiatan sekolah.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('berita'))
                                        <a href="{{ route('berita.index') }}" aria-label="Lihat Berita" class="apple-glass-btn">
                                            <span>Kelola Berita</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['berita']['title']) }}', {{ json_encode($getAllowedLabels('berita')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Kelola Berita (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 10. Arsip Foto Kenangan -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Arsip Foto Kenangan
                                            </h4>
                                        </div>
                                        @if(!$canAccess('arsip_index'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Galeri dokumentasi momen kebersamaan dan kenangan OSIS setiap periode.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('arsip_index'))
                                        <a href="{{ route('arsip.index') }}" aria-label="Lihat Arsip Foto" class="apple-glass-btn">
                                            <span>Galeri Foto</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['arsip_index']['title']) }}', {{ json_encode($getAllowedLabels('arsip_index')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Galeri Foto (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                            <!-- 11. Upload Kenangan Baru -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Upload Kenangan Baru
                                            </h4>
                                        </div>
                                        @if(!$canAccess('arsip_create'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Unggah momen dokumentasi atau kegiatan baru langsung ke dalam galeri arsip.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('arsip_create'))
                                        <a href="{{ route('arsip.create') }}" aria-label="Tambah Kenangan Baru" class="apple-glass-btn">
                                            <span>Tambah Kenangan</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['arsip_create']['title']) }}', {{ json_encode($getAllowedLabels('arsip_create')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Tambah Kenangan (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- 11. Logbook Aktivitas (Audit Trail) -->
                            <div class="apple-glass-card group/card relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between">
                                <div class="apple-glass-specular"></div>
                                <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-rose-500/15 via-rose-400/5 to-transparent rounded-full blur-2xl pointer-events-none group-hover/card:scale-150 transition-transform duration-500"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="apple-icon-squircle">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-base group-hover/card:text-[#571319] transition-colors leading-snug">
                                                Logbook Aktivitas
                                            </h4>
                                        </div>
                                        @if(!$canAccess('logbook'))
                                            <span class="apple-lock-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100/90 text-rose-700 border border-rose-200 shadow-sm shrink-0" title="Akses Terkunci">
                                                <svg width="11" height="11" style="width:11px;height:11px;min-width:11px;max-width:11px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <span>Terkunci</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                        Pantau rekam jejak aksi pengurus secara otomatis, riwayat CRUD, verifikasi persetujuan, dan perubahan data.
                                    </p>
                                </div>

                                <div class="relative z-10 mt-auto pt-4">
                                    @if($canAccess('logbook'))
                                        <a href="{{ route('logbook.index') }}" aria-label="Buka Logbook Aktivitas" class="apple-glass-btn">
                                            <span>Buka Logbook</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openRoleDeniedModal('{{ addslashes($dashboardAccess['logbook']['title']) }}', {{ json_encode($getAllowedLabels('logbook')) }})" class="apple-glass-btn-locked" title="Akses terbatas untuk role Anda">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;max-width:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            <span>Buka Logbook (Terkunci)</span>
                                        </button>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== FLOATING PREVIEW WIDGET (Apple Liquid Glass Control Center) ===== --}}
            <div id="fpv-widget" class="apple-control-center" style="position:fixed;bottom:20px;right:20px;z-index:9999;width:130px;border-radius:20px;overflow:hidden;background:linear-gradient(150deg, #e11d48 0%, #be123c 50%, #700b1d 100%) !important;box-shadow:0 16px 40px -8px rgba(190,18,60,0.65), 0 4px 14px rgba(0,0,0,0.3) !important;border:1.5px solid rgba(255,255,255,0.4) !important;color:#ffffff !important;padding:10px 8px 8px;display:flex;flex-direction:column;gap:7px;">
                {{-- Specular Highlight --}}
                <div class="apple-glass-specular" style="position:absolute;top:0;left:8%;right:8%;height:1px;background:linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.9) 50%, transparent 100%);pointer-events:none;"></div>

                {{-- Label --}}
                <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#34d399;display:inline-block;animation:fpvPulse 1.5s infinite;flex-shrink:0;box-shadow:0 0 8px rgba(52,211,153,0.9);"></span>
                    <span style="font-size:10px;font-weight:900;letter-spacing:0.14em;text-transform:uppercase;color:#ffffff !important;text-shadow:0 1px 3px rgba(0,0,0,0.5);">Preview</span>
                </div>

                {{-- Switcher Desktop / Mobile --}}
                <div style="display:flex;gap:5px;">
                    <button type="button" id="fpv-desktop" onclick="fpvSet('desktop')" title="Desktop View" class="fpv-switch-btn fpv-active" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px 4px;border-radius:10px;cursor:pointer;background:#ffffff !important;color:#9f1239 !important;border:none !important;outline:none !important;box-shadow:0 3px 8px rgba(0,0,0,0.25) !important;">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color:inherit !important;">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                        <span style="font-size:8px;font-weight:800;line-height:1;color:inherit !important;">Desktop</span>
                    </button>
                    <button type="button" id="fpv-mobile" onclick="fpvSet('mobile')" title="Mobile View" class="fpv-switch-btn fpv-inactive" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px 4px;border-radius:10px;cursor:pointer;background:rgba(0,0,0,0.3) !important;color:#ffffff !important;border:1px solid rgba(255,255,255,0.3) !important;outline:none !important;">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color:inherit !important;">
                            <rect x="6" y="2" width="12" height="20" rx="2" ry="2"/>
                            <line x1="12" y1="18" x2="12.01" y2="18"/>
                        </svg>
                        <span style="font-size:8px;font-weight:700;line-height:1;color:inherit !important;">Mobile</span>
                    </button>
                </div>

                {{-- Launch Button --}}
                <button type="button" onclick="fpvLaunch()" class="fpv-launch-btn" style="width:100%;height:28px;border-radius:10px;border:none !important;outline:none !important;cursor:pointer;background:#ffffff !important;color:#9f1239 !important;font-size:11px;font-weight:800;box-shadow:0 3px 10px rgba(0,0,0,0.25) !important;display:flex;align-items:center;justify-content:center;gap:5px;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="color:inherit !important;">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    <span style="color:inherit !important;">Buka</span>
                </button>
            </div>

            {{-- ================= APPLE LIQUID GLASS STYLES ================= --}}
            <style>
                /* Apple Frosted Glass Panel */
                .apple-glass-panel {
                    background: linear-gradient(155deg, rgba(255, 255, 255, 0.72) 0%, rgba(255, 255, 255, 0.48) 50%, rgba(255, 255, 255, 0.32) 100%);
                    -webkit-backdrop-filter: blur(32px) saturate(190%);
                    backdrop-filter: blur(32px) saturate(190%);
                    border: 1px solid rgba(255, 255, 255, 0.85);
                    box-shadow: 0 30px 70px -15px rgba(87, 19, 25, 0.08), 0 4px 16px -2px rgba(0, 0, 0, 0.02), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 1);
                }

                /* Apple Liquid Glass Cards */
                .apple-glass-card {
                    background: linear-gradient(145deg, rgba(255, 255, 255, 0.78) 0%, rgba(255, 255, 255, 0.50) 50%, rgba(255, 255, 255, 0.34) 100%);
                    -webkit-backdrop-filter: blur(24px) saturate(190%);
                    backdrop-filter: blur(24px) saturate(190%);
                    border: 1px solid rgba(255, 255, 255, 0.8);
                    box-shadow: 0 12px 32px -10px rgba(0, 0, 0, 0.04), 0 20px 40px -15px rgba(87, 19, 25, 0.06), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95), inset 0 -1px 1px 0 rgba(0, 0, 0, 0.02);
                    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .apple-glass-card:hover {
                    transform: translateY(-4px);
                    border-color: rgba(225, 29, 72, 0.4);
                    box-shadow: 0 24px 50px -10px rgba(87, 19, 25, 0.15), 0 8px 20px -4px rgba(0, 0, 0, 0.04), inset 0 1.5px 2px 0 rgba(255, 255, 255, 1);
                }

                /* Apple Signature Specular Hairline */
                .apple-glass-specular {
                    position: absolute;
                    top: 0;
                    left: 8%;
                    right: 8%;
                    height: 1px;
                    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.95) 45%, rgba(255, 255, 255, 0.95) 55%, transparent 100%);
                    pointer-events: none;
                }

                /* Apple Icon Squircles */
                .apple-icon-squircle {
                    width: 3rem;
                    height: 3rem;
                    flex-shrink: 0;
                    border-radius: 16px;
                    background: linear-gradient(145deg, #f43f5e 0%, #be123c 45%, #7a1921 80%, #571319 100%);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    box-shadow: 0 8px 20px -4px rgba(122, 25, 33, 0.35), inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.45), inset 0 -1px 2px 0 rgba(0, 0, 0, 0.25);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #ffffff;
                    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .apple-glass-card:hover .apple-icon-squircle {
                    transform: scale(1.06);
                    box-shadow: 0 12px 24px -4px rgba(190, 18, 60, 0.45), inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.6);
                }

                /* Apple Liquid Glass CTA Buttons */
                .apple-glass-btn {
                    width: 100%;
                    height: 2.75rem;
                    border-radius: 14px;
                    background: linear-gradient(145deg, #e11d48 0%, #be123c 40%, #881337 75%, #571319 100%);
                    color: #ffffff !important;
                    font-size: 0.75rem;
                    font-weight: 700;
                    border: 1px solid rgba(255, 255, 255, 0.25);
                    box-shadow: 0 4px 14px 0 rgba(190, 18, 60, 0.3), inset 0 1px 1px 0 rgba(255, 255, 255, 0.4);
                    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding-left: 1rem;
                    padding-right: 1rem;
                    white-space: nowrap;
                    text-decoration: none;
                }

                .apple-glass-btn:hover {
                    background: linear-gradient(145deg, #f43f5e 0%, #e11d48 40%, #9f1239 75%, #7a1921 100%);
                    box-shadow: 0 8px 22px 0 rgba(190, 18, 60, 0.45), inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.55);
                    transform: translateY(-1px);
                    color: #ffffff !important;
                }

                .apple-glass-btn:active {
                    transform: scale(0.98);
                }

                /* Apple Liquid Glass Locked CTA Button (Restricted Role) */
                .apple-glass-btn-locked {
                    width: 100%;
                    height: 2.75rem;
                    border-radius: 14px;
                    background: linear-gradient(145deg, rgba(244, 63, 94, 0.08) 0%, rgba(190, 18, 60, 0.04) 100%);
                    color: #9f1239 !important;
                    font-size: 0.75rem;
                    font-weight: 700;
                    border: 1.5px dashed rgba(225, 29, 72, 0.35);
                    box-shadow: 0 2px 8px 0 rgba(190, 18, 60, 0.06), inset 0 1px 1px 0 rgba(255, 255, 255, 0.8);
                    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding-left: 1rem;
                    padding-right: 1rem;
                    white-space: nowrap;
                    cursor: pointer;
                    text-decoration: none;
                }

                .apple-glass-btn-locked:hover {
                    background: linear-gradient(145deg, rgba(244, 63, 94, 0.16) 0%, rgba(190, 18, 60, 0.10) 100%);
                    border-color: rgba(225, 29, 72, 0.65);
                    color: #be123c !important;
                    box-shadow: 0 4px 14px 0 rgba(190, 18, 60, 0.12), inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.95);
                    transform: translateY(-1px);
                }

                .apple-glass-btn-locked:active {
                    transform: scale(0.98);
                }

                .apple-glass-btn-locked svg {
                    width: 14px !important;
                    height: 14px !important;
                    min-width: 14px !important;
                    max-width: 14px !important;
                    min-height: 14px !important;
                    max-height: 14px !important;
                    flex-shrink: 0 !important;
                    display: inline-block !important;
                    stroke: currentColor !important;
                    vertical-align: middle !important;
                }

                .apple-lock-badge svg {
                    width: 11px !important;
                    height: 11px !important;
                    min-width: 11px !important;
                    max-width: 11px !important;
                    min-height: 11px !important;
                    max-height: 11px !important;
                    flex-shrink: 0 !important;
                    display: inline-block !important;
                    stroke: currentColor !important;
                    vertical-align: middle !important;
                }

                /* Apple Glass Pill Badges */
                .apple-glass-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.375rem 0.875rem;
                    border-radius: 9999px;
                    background: rgba(255, 255, 255, 0.65);
                    -webkit-backdrop-filter: blur(16px);
                    backdrop-filter: blur(16px);
                    border: 1px solid rgba(255, 255, 255, 0.8);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03), inset 0 1px 1px rgba(255, 255, 255, 0.9);
                }

                /* Apple Glass Sub-card */
                .apple-glass-subcard {
                    padding: 0.875rem;
                    border-radius: 18px;
                    background: rgba(255, 255, 255, 0.65);
                    -webkit-backdrop-filter: blur(14px);
                    backdrop-filter: blur(14px);
                    border: 1px solid rgba(255, 255, 255, 0.85);
                    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02), 0 2px 8px rgba(0, 0, 0, 0.02);
                }

                /* Apple Control Center Floating Widget */
                .apple-control-center {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    z-index: 9999;
                    width: 130px;
                    border-radius: 20px;
                    overflow: hidden;
                    background: linear-gradient(150deg, rgba(225, 29, 72, 0.95) 0%, rgba(190, 18, 60, 0.95) 50%, rgba(122, 25, 33, 0.96) 100%);
                    -webkit-backdrop-filter: blur(28px) saturate(200%);
                    backdrop-filter: blur(28px) saturate(200%);
                    box-shadow: 0 16px 40px -8px rgba(190, 18, 60, 0.6), 0 4px 12px rgba(0, 0, 0, 0.25), inset 0 1px 1.5px rgba(255, 255, 255, 0.55);
                    border: 1.5px solid rgba(255, 255, 255, 0.35);
                    font-family: inherit;
                    color: #fff;
                    padding: 10px 8px 8px;
                    display: flex;
                    flex-direction: column;
                    gap: 7px;
                }

                .fpv-switch-btn {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 3px;
                    padding: 6px 4px;
                    border-radius: 10px;
                    cursor: pointer;
                    font-family: inherit;
                    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .fpv-switch-btn.fpv-active {
                    background: #ffffff;
                    color: #9f1239;
                    border: none;
                    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.9);
                }

                .fpv-switch-btn.fpv-inactive {
                    background: rgba(0, 0, 0, 0.28);
                    color: #ffffff;
                    border: 1px solid rgba(255, 255, 255, 0.25);
                }

                .fpv-switch-btn.fpv-inactive:hover {
                    background: rgba(0, 0, 0, 0.4);
                }

                .fpv-launch-btn {
                    width: 100%;
                    height: 28px;
                    border-radius: 10px;
                    border: none;
                    cursor: pointer;
                    background: #ffffff;
                    color: #9f1239;
                    font-size: 11px;
                    font-weight: 800;
                    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.9);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 5px;
                    font-family: inherit;
                    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .fpv-launch-btn:hover {
                    opacity: 0.92;
                    transform: translateY(-1px);
                    box-shadow: 0 5px 14px rgba(0, 0, 0, 0.3);
                }

                .fpv-launch-btn:active {
                    transform: scale(0.96);
                }

                @keyframes fpvPulse {
                    0%, 100% { opacity: 1; transform: scale(1); }
                    50%       { opacity: 0.5; transform: scale(0.85); }
                }
            </style>

            <script>
                let _fpvMode = 'desktop';
                function fpvSet(mode) {
                    _fpvMode = mode;
                    const desk = document.getElementById('fpv-desktop');
                    const mob  = document.getElementById('fpv-mobile');
                    if (!desk || !mob) return;
                    
                    const activeCss = 'flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px 4px;border-radius:10px;cursor:pointer;background:#ffffff !important;color:#9f1239 !important;border:none !important;outline:none !important;box-shadow:0 3px 8px rgba(0,0,0,0.25) !important;';
                    const inactiveCss = 'flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px 4px;border-radius:10px;cursor:pointer;background:rgba(0,0,0,0.3) !important;color:#ffffff !important;border:1px solid rgba(255,255,255,0.3) !important;outline:none !important;';
                    
                    if (mode === 'desktop') {
                        desk.style.cssText = activeCss;
                        mob.style.cssText = inactiveCss;
                    } else {
                        mob.style.cssText = activeCss;
                        desk.style.cssText = inactiveCss;
                    }
                }
                function fpvLaunch() {
                    const url = '{{ url('/?preview=1') }}';
                    if (_fpvMode === 'mobile') {
                        window.open(url, 'PreviewMobile', 'width=390,height=844,resizable=yes,scrollbars=yes');
                    } else {
                        window.open(url, '_blank');
                    }
                }

                // Fallback jika dini.blade.php tidak termuat atau modal dipanggil independen
                if (typeof window.openRoleDeniedModal === 'undefined') {
                    window.openRoleDeniedModal = function(menuTitle, allowedRoles) {
                        const modal = document.getElementById('roleDeniedModal');
                        if (modal) {
                            const titleEl = document.getElementById('deniedMenuTitle');
                            const listEl = document.getElementById('deniedAllowedRolesList');
                            if (titleEl) titleEl.textContent = `"${menuTitle}"`;
                            if (listEl && allowedRoles && allowedRoles.length > 0) {
                                listEl.innerHTML = '';
                                allowedRoles.forEach(r => {
                                    const pill = document.createElement('span');
                                    pill.className = 'role-pill';
                                    pill.innerHTML = `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:12px;height:12px;display:inline-block;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>${r}`;
                                    listEl.appendChild(pill);
                                });
                            }
                            modal.classList.add('active');
                            const backdrop = document.getElementById('roleDeniedBackdrop');
                            if (backdrop) backdrop.classList.add('active');
                            document.body.style.overflow = 'hidden';
                            return;
                        }

                        if (typeof Swal !== 'undefined') {
                            let roleHtml = '<div style="display:flex;flex-wrap:wrap;gap:6px;justify-content:center;margin-top:12px;">';
                            (allowedRoles || []).forEach(r => {
                                roleHtml += `<span style="background:rgba(225,29,72,0.1);color:#be123c;border:1px solid rgba(225,29,72,0.25);border-radius:9999px;padding:4px 10px;font-size:11px;font-weight:600;">${r}</span>`;
                            });
                            roleHtml += '</div>';

                            Swal.fire({
                                icon: 'warning',
                                title: '<span style="color:#e2e8f0;font-size:18px;font-weight:700;">Akses Modul Terbatas</span>',
                                html: `<p style="color:#cbd5e1;font-size:13px;line-height:1.5;">Anda tidak memiliki otoritas untuk mengakses modul <b>${menuTitle}</b>.</p><p style="color:#94a3b8;font-size:11px;margin-top:10px;text-transform:uppercase;letter-spacing:0.05em;font-weight:700;">Role Yang Memiliki Akses:</p>${roleHtml}`,
                                background: '#5f1319',
                                confirmButtonColor: '#be123c',
                                confirmButtonText: 'Saya Mengerti',
                                customClass: {
                                    popup: 'rounded-2xl border border-rose-800/40 shadow-2xl'
                                }
                            });
                        } else {
                            alert(`Akses Terbatas: Anda tidak memiliki akses ke modul "${menuTitle}".`);
                        }
                    };
                }
            </script>
        @endauth
    </div>
</section>
