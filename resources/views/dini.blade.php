{{-- Component Sidebar OSIS SMKN 1 Tarumajaya (dini.blade.php) --}}
<!-- Google Fonts: Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ====================================================
       UNIFIED DESIGN SYSTEM (OSIS SMKN 1 TARUMAJAYA)
       Konsistensi Dropdown, Card, Input & Tombol
       ==================================================== */

    /* 1. Global Select Dropdown Styling */
    select:not([multiple]):not([size]):not(.no-custom-select) {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #f8fafc;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-weight: 600;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.15rem 1.15rem;
        padding-right: 2.75rem !important;
        transition: all 0.2s ease-in-out;
    }

    select:not([multiple]):not([size]):not(.no-custom-select):hover {
        border-color: #cbd5e1;
        background-color: #f1f5f9;
    }

    select:not([multiple]):not([size]):not(.no-custom-select):focus {
        outline: none;
        border-color: #e11d48 !important;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.12) !important;
    }

    /* 2. Unified Card & Form Focus Utilities */
    .app-card {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 2rem;
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.05);
    }

    .app-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        color: #1e293b;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .app-input:focus {
        outline: none;
        border-color: #e11d48;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.12);
    }

    /* 3. Unified Primary Button */
    .app-btn-primary {
        background: linear-gradient(to right, #f43f5e, #571319);
        color: #ffffff;
        font-weight: 700;
        border-radius: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px -5px rgba(225, 29, 72, 0.3);
    }

    .app-btn-primary:hover {
        background: linear-gradient(to right, #e11d48, #571319);
        box-shadow: 0 15px 30px -5px rgba(225, 29, 72, 0.4);
    }

    .app-btn-primary:active {
        transform: scale(0.98);
    }

    /* 4. Custom Sleek Scrollbar */
    .custom-time-scroll::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }
    .custom-time-scroll::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 6px;
    }
    .custom-time-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
    }
    .custom-time-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

@php
    $user = auth()->user();
    $currentRole = $user ? $user->role : null;
    $currentRoleLabel = $user ? ucwords(str_replace('_', ' ', $user->role)) : 'Tamu / Belum Login';

    // Pemetaan nama role resmi OSIS SMKN 1 Tarumajaya
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

    // Definisi hak akses setiap menu di sidebar:
    // 'allowed_roles' => null artinya publik / dapat diakses siapapun
    // 'allowed_roles' => [...] artinya dibatasi hanya untuk role-role tersebut
    $sidebarNavItems = [
        [
            'id' => 'dashboard',
            'title' => 'Dashboard Utama',
            'url' => url('/'),
            'target' => '_self',
            'active' => request()->is('/') && !request('preview'),
            'allowed_roles' => null, // Semua role & publik
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>',
        ],
        [
            'id' => 'live_frontend',
            'title' => 'Live Front End',
            'url' => url('/?preview=1'),
            'target' => '_blank',
            'active' => false,
            'allowed_roles' => null, // Semua role & publik
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        ],
        [
            'id' => 'visionmission',
            'title' => 'Visi & Misi',
            'url' => route('visionmission.index'),
            'target' => '_self',
            'active' => request()->routeIs('visionmission.*'),
            'allowed_roles' => ['ketua', 'wakil', 'sekretaris'],
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0a6 6 0 100-12 6 6 0 000 12zm0 0a3 3 0 100-6 3 3 0 000 6z" /></svg>',
        ],
        [
            'id' => 'struktur',
            'title' => 'Struktur Pengurus',
            'url' => route('struktur.index'),
            'target' => '_self',
            'active' => request()->routeIs('struktur.*'),
            'allowed_roles' => $allPengurusRoles, // Semua pengurus terdaftar
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.999-3.199a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>',
        ],
        [
            'id' => 'reports',
            'title' => 'Tracking Laporan',
            'url' => route('reports.index'),
            'target' => '_self',
            'active' => request()->routeIs('reports.*'),
            'allowed_roles' => $allPengurusRoles, // Semua pengurus terdaftar
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
        ],
        [
            'id' => 'event',
            'title' => 'Agenda & Absensi',
            'url' => route('event.index'),
            'target' => '_self',
            'active' => (request()->routeIs('event.*') || request()->routeIs('attendance.*')),
            'allowed_roles' => $allPengurusRoles, // Semua pengurus terdaftar
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>',
        ],
        [
            'id' => 'admin_osis',
            'title' => 'Anggota OSIS',
            'url' => route('admin.osis.approved'),
            'target' => '_self',
            'active' => request()->routeIs('admin.osis.*'),
            'allowed_roles' => ['ketua', 'wakil', 'sekretaris', 'bendahara', 'ketua_divisi_humas', 'anggota_divisi_humas'],
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>',
        ],
        [
            'id' => 'suara',
            'title' => 'Kotak Aspirasi',
            'url' => route('suara.index'),
            'target' => '_self',
            'active' => request()->routeIs('suara.*'),
            'allowed_roles' => ['ketua', 'wakil', 'sekretaris', 'ketua_divisi_humas', 'anggota_divisi_humas'],
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>',
        ],
        [
            'id' => 'berita',
            'title' => 'Berita & Kegiatan',
            'url' => route('berita.index'),
            'target' => '_self',
            'active' => request()->routeIs('berita.*'),
            'allowed_roles' => null, // Publik & semua role
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" /></svg>',
        ],
        [
            'id' => 'finance',
            'title' => 'Keuangan',
            'url' => route('finance.index'),
            'target' => '_self',
            'active' => request()->routeIs('finance.*'),
            'allowed_roles' => ['ketua', 'wakil', 'sekretaris', 'bendahara', 'ketua_divisi_humas', 'ketua_divisi_agama', 'ketua_divisi_olahraga', 'ketua_divisi_artmedia'],
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v11.25M2.25 6h19.5m0 0v11.25m0-11.25a2.25 2.25 0 00-2.25-2.25H4.5A2.25 2.25 0 002.25 6m19.5 0v.75c0 .414-.336.75-.75.75H21m0 0a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 7.5m18 0v8.25m0 0A2.25 2.25 0 0118.75 18H5.25A2.25 2.25 0 013 15.75V7.5" /></svg>',
        ],
        [
            'id' => 'arsip',
            'title' => 'Arsip Dokumentasi',
            'url' => route('arsip.index'),
            'target' => '_self',
            'active' => request()->routeIs('arsip.*'),
            'allowed_roles' => null, // Publik & semua role
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>',
        ],
        [
            'id' => 'logbook',
            'title' => 'Logbook Aktivitas',
            'url' => route('logbook.index'),
            'target' => '_self',
            'active' => request()->routeIs('logbook.*'),
            'allowed_roles' => ['ketua', 'wakil'], // Khusus Ketua & Wakil OSIS
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>',
        ],
        [
            'id' => 'rbac',
            'title' => 'Matriks RBAC',
            'url' => route('rbac.index'),
            'target' => '_self',
            'active' => request()->routeIs('rbac.*'),
            'allowed_roles' => ['ketua'], // Khusus Ketua OSIS
            'icon' => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>',
        ],
    ];
@endphp

<style>
    /* ================= TOMBOL FLOATING MENU (HAMBURGER) ================= */
    .menu-toggle {
        position: fixed;
        top: 22px;
        left: 22px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #5f1319;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 18px;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.3px;
        cursor: pointer;
        z-index: 990;
        box-shadow: 0 10px 25px -5px rgba(95, 19, 25, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .menu-toggle:hover {
        background: #7a1921;
        transform: translateY(-2px);
        box-shadow: 0 14px 28px -5px rgba(95, 19, 25, 0.45);
    }

    .menu-toggle:active {
        transform: translateY(0);
    }

    .menu-toggle svg {
        width: 18px;
        height: 18px;
        stroke-width: 2.5;
    }

    /* ================= BACKDROP OVERLAY ================= */
    .sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 998;
    }

    .sidebar-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    /* ================= MODERN SIDEBAR DRAWER ================= */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 300px;
        max-width: 88vw;
        height: 100%;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 10px 0 40px rgba(0, 0, 0, 0.12);
        border-right: 1px solid rgba(226, 232, 240, 0.8);
        transform: translateX(-100%);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 999;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    /* Header Sidebar */
    .sidebar-header {
        padding: 22px 20px 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: linear-gradient(to bottom, #ffffff, #fafafa);
    }

    .brand-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-logo {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        object-fit: contain;
        background: #fff;
        padding: 3px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .brand-text h4 {
        font-size: 14px;
        font-weight: 800;
        color: #5f1319;
        line-height: 1.2;
        letter-spacing: -0.2px;
        text-transform: uppercase;
        margin: 0;
    }

    .brand-text span {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    /* Tombol Tutup (X) */
    .btn-close-sidebar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-close-sidebar:hover {
        background: #fee2e2;
        color: #dc2626;
        transform: rotate(90deg);
    }

    /* Konten Navigasi Sidebar */
    .sidebar-body {
        flex: 1;
        padding: 16px 14px;
        overflow-y: auto;
    }

    .nav-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 12px 10px;
    }

    .sidebar-menu {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin: 0;
        padding: 0;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .sidebar-menu a .menu-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .sidebar-menu a .menu-icon svg {
        width: 17px;
        height: 17px;
    }

    /* Hover & Active States */
    .sidebar-menu a:hover {
        color: #5f1319;
        background: rgba(95, 19, 25, 0.05);
        transform: translateX(4px);
    }

    .sidebar-menu a:hover .menu-icon {
        background: #5f1319;
        color: #ffffff;
    }

    .sidebar-menu a.active {
        color: #5f1319;
        background: linear-gradient(90deg, rgba(95, 19, 25, 0.1) 0%, rgba(95, 19, 25, 0.03) 100%);
        font-weight: 700;
    }

    .sidebar-menu a.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 4px;
        border-radius: 0 4px 4px 0;
        background: #5f1319;
    }

    .sidebar-menu a.active .menu-icon {
        background: #5f1319;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(95, 19, 25, 0.3);
    }

    /* Restricted / Locked Menu Style */
    .sidebar-menu a.menu-restricted {
        opacity: 0.78;
    }

    .sidebar-menu a.menu-restricted:hover {
        opacity: 1;
        background: rgba(225, 29, 72, 0.06);
    }

    .sidebar-menu a.menu-restricted:hover .menu-icon {
        background: #9f1239;
        color: #fff;
    }

    .menu-lock-badge {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #fef2f2;
        border: 1px solid #fecdd3;
        color: #e11d48;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .sidebar-menu a:hover .menu-lock-badge {
        background: #e11d48;
        color: #ffffff;
        border-color: #e11d48;
        transform: scale(1.1);
    }

    /* Footer Sidebar */
    .sidebar-footer {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .footer-card {
        background: linear-gradient(135deg, #5f1319 0%, #871922 100%);
        color: white;
        padding: 14px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 8px 16px -4px rgba(95, 19, 25, 0.3);
    }

    .footer-card p {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
        margin: 0;
    }

    .footer-card small {
        font-size: 10px;
        opacity: 0.85;
        display: block;
        margin-top: 2px;
    }

    /* ================= ROLE ACCESS DENIED POPUP MODAL (APPLE LIQUID GLASS) ================= */
    .role-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(14px) saturate(180%);
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .role-modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .role-modal-card {
        width: 100%;
        max-width: 480px;
        border-radius: 28px;
        background: linear-gradient(155deg, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.88) 100%);
        backdrop-filter: blur(32px) saturate(200%);
        -webkit-backdrop-filter: blur(32px) saturate(200%);
        border: 1px solid rgba(255, 255, 255, 0.95);
        box-shadow: 0 25px 60px -12px rgba(87, 19, 25, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.8) inset, 0 8px 24px rgba(0, 0, 0, 0.1);
        padding: 28px 26px;
        position: relative;
        overflow: hidden;
        transform: scale(0.92) translateY(12px);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease;
    }

    .role-modal-backdrop.show .role-modal-card {
        transform: scale(1) translateY(0);
    }

    .role-modal-specular {
        position: absolute;
        top: 0;
        left: 10%;
        right: 10%;
        height: 1.5px;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 1) 50%, transparent 100%);
        pointer-events: none;
    }

    .role-modal-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: linear-gradient(145deg, #f43f5e 0%, #be123c 45%, #7a1921 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(190, 18, 60, 0.45), inset 0 1px 1.5px rgba(255, 255, 255, 0.6);
        margin: 0 auto 16px;
    }

    .role-modal-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .role-modal-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        background: #fee2e2;
        border: 1px solid #fecdd3;
        color: #be123c;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .role-modal-title {
        font-size: 20px;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 8px;
        letter-spacing: -0.4px;
    }

    .role-modal-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.55;
        margin: 0;
    }

    .role-current-box {
        margin-top: 10px;
        padding: 8px 14px;
        border-radius: 12px;
        background: rgba(241, 245, 249, 0.85);
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #334155;
    }

    .role-current-box strong {
        color: #be123c;
    }

    /* Box Daftar Role yang Diizinkan */
    .role-allowed-container {
        margin: 18px 0;
        padding: 16px;
        border-radius: 18px;
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.85) 0%, rgba(248, 250, 252, 0.75) 100%);
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .role-allowed-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 10px;
    }

    .role-allowed-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        max-height: 180px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-size: 11.5px;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.15s ease;
    }

    .role-pill svg {
        color: #10b981;
        width: 13px;
        height: 13px;
        flex-shrink: 0;
    }

    /* Action Buttons Modal */
    .role-modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .role-modal-btn-close {
        flex: 1;
        height: 42px;
        border-radius: 14px;
        background: linear-gradient(145deg, #e11d48 0%, #be123c 45%, #7a1921 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 4px 14px rgba(190, 18, 60, 0.35), inset 0 1px 1px rgba(255, 255, 255, 0.4);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }

    .role-modal-btn-close:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(190, 18, 60, 0.45);
        color: #ffffff;
    }

    .role-modal-btn-close:active {
        transform: scale(0.98);
    }
</style>

<!-- TOMBOL FLOATING UNTUK MEMBUKA SIDEBAR -->
<button type="button" class="menu-toggle" onclick="toggleSidebar()" aria-label="Buka Menu Sidebar">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
    <span>Menu</span>
</button>

<!-- BACKDROP GELAP KETIKA SIDEBAR TERBUKA -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

<!-- SIDEBAR MODERN DENGAN BRANDING OSIS -->
<aside class="sidebar" id="mySidebar" aria-label="Sidebar Menu">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <div class="brand-wrapper">
            <img class="brand-logo" src="{{ asset('image/logosmkn1trmj.png') }}" alt="Logo SMKN 1 Tarumajaya" onerror="this.style.display='none'">
            <div class="brand-text">
                <h4>OSIS SMKN 1</h4>
                <span>TARUMAJAYA</span>
            </div>
        </div>
        <button type="button" class="btn-close-sidebar" onclick="closeSidebar()" aria-label="Tutup Menu">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Body & Navigasi -->
    <div class="sidebar-body">
        <div class="nav-label">Panel Navigasi</div>
        <ul class="sidebar-menu">
            @foreach($sidebarNavItems as $item)
                @php
                    // Cek apakah role user saat ini berhak mengakses modul ini
                    $isAllowed = ($item['allowed_roles'] === null) || ($currentRole && in_array($currentRole, $item['allowed_roles']));
                    
                    // Siapkan array nama-nama role yang diizinkan untuk dikirim ke modal JavaScript
                    $allowedNames = [];
                    if ($item['allowed_roles']) {
                        foreach ($item['allowed_roles'] as $r) {
                            $allowedNames[] = $roleLabels[$r] ?? ucwords(str_replace('_', ' ', $r));
                        }
                    }
                @endphp

                <li>
                    @if($isAllowed)
                        {{-- User Memiliki Hak Akses -> Navigasi Normal --}}
                        <a href="{{ $item['url'] }}" 
                           target="{{ $item['target'] }}" 
                           class="nav-link {{ $item['active'] ? 'active' : '' }}">
                            <span class="menu-icon">
                                {!! $item['icon'] !!}
                            </span>
                            <span>{{ $item['title'] }}</span>
                        </a>
                    @else
                        {{-- User TIDAK Memiliki Hak Akses -> Cegah Navigasi & Buka Pop-up Otoritas --}}
                        <a href="javascript:void(0)" 
                           onclick="openRoleDeniedModal('{{ addslashes($item['title']) }}', {{ json_encode($allowedNames) }})" 
                           class="nav-link menu-restricted" 
                           title="Akses terbatas untuk role Anda">
                            <span class="menu-icon">
                                {!! $item['icon'] !!}
                            </span>
                            <span>{{ $item['title'] }}</span>
                            <span class="menu-lock-badge" title="Terkunci - Klik untuk melihat otoritas role">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Footer Sidebar -->
    <div class="sidebar-footer">
        <div class="footer-card">
            <p>{{ auth()->check() ? auth()->user()->name : 'OSIS SMKN 1' }}</p>
            <small>{{ auth()->check() ? ($roleLabels[auth()->user()->role] ?? ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'Admin'))) : 'Tamu / Belum Login' }}</small>
            @if(auth()->check())
            <a href="{{ route('struktur.show', auth()->user()->id) }}" class="w-full text-center text-[11px] text-white font-bold py-1.5 rounded-lg bg-rose-500/30 hover:bg-rose-500/50 border border-rose-400/40 transition flex items-center justify-center gap-1.5 mt-2">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                </svg>
                <span>ID Card & QR Saya</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1.5">
                @csrf
                <button type="submit" class="w-full text-center text-[11px] text-white/90 hover:text-white font-bold py-1.5 rounded-lg bg-black/20 hover:bg-black/30 transition flex items-center justify-center gap-1.5">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
            @else
            <button type="button" onclick="handleAuthButton()" class="w-full text-center text-[11px] text-white/90 hover:text-white font-bold py-1.5 rounded-lg bg-black/20 hover:bg-black/30 transition flex items-center justify-center gap-1.5 mt-2.5">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H2.25" />
                </svg>
                <span>Login Pengurus</span>
            </button>
            @endif
        </div>
    </div>
</aside>

<!-- ================= ROLE ACCESS DENIED POPUP MODAL ================= -->
<div class="role-modal-backdrop" id="roleDeniedModal" onclick="closeRoleDeniedModal(event)">
    <div class="role-modal-card" onclick="event.stopPropagation()">
        <div class="role-modal-specular"></div>

        <div class="role-modal-icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        <div class="role-modal-header">
            <div class="role-modal-badge">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>Otoritas Dibatasi</span>
            </div>
            <h3 class="role-modal-title">Akses Halaman Ditolak</h3>
            <p class="role-modal-desc">
                Mohon maaf, Anda tidak memiliki izin untuk membuka halaman <strong id="deniedMenuTitle" style="color:#0f172a;"></strong>.
            </p>

            <div class="role-current-box">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span>Role Akun Anda: <strong>{{ auth()->check() ? ($roleLabels[auth()->user()->role] ?? $currentRoleLabel) : 'Tamu (Belum Login)' }}</strong></span>
            </div>
        </div>

        <div class="role-allowed-container">
            <div class="role-allowed-label">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
                <span>Role yang Memiliki Hak Akses:</span>
            </div>
            <div class="role-allowed-list" id="deniedAllowedRolesList">
                {{-- Diisi secara dinamis oleh JavaScript --}}
            </div>
        </div>

        <div class="role-modal-actions">
            <button type="button" class="role-modal-btn-close" onclick="closeRoleDeniedModal()">
                <span>Saya Mengerti</span>
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT INTERAKTIF SIDEBAR & ROLE MODAL -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mySidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (!sidebar || !backdrop) return;
        const isOpen = sidebar.classList.toggle('active');
        backdrop.classList.toggle('active', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    function closeSidebar() {
        const sidebar = document.getElementById('mySidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (!sidebar || !backdrop) return;
        sidebar.classList.remove('active');
        backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Fungsi Menampilkan Pop-Up Hak Akses
    function openRoleDeniedModal(menuTitle, allowedRoles) {
        const modal = document.getElementById('roleDeniedModal');
        const titleEl = document.getElementById('deniedMenuTitle');
        const listEl = document.getElementById('deniedAllowedRolesList');
        if (!modal || !titleEl || !listEl) return;

        titleEl.textContent = `"${menuTitle}"`;

        // Render role badges
        listEl.innerHTML = '';
        if (allowedRoles && allowedRoles.length > 0) {
            allowedRoles.forEach(roleName => {
                const pill = document.createElement('span');
                pill.className = 'role-pill';
                pill.innerHTML = `
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>${roleName}</span>
                `;
                listEl.appendChild(pill);
            });
        } else {
            listEl.innerHTML = '<span style="font-size:12px;color:#64748b;">Hanya Pengurus Inti Terverifikasi</span>';
        }

        modal.classList.add('show');
    }

    function closeRoleDeniedModal(event) {
        if (event && event.target && event.target.id !== 'roleDeniedModal') {
            return;
        }
        const modal = document.getElementById('roleDeniedModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    // Keyboard navigation (ESC key)
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('roleDeniedModal');
            if (modal && modal.classList.contains('show')) {
                modal.classList.remove('show');
                return;
            }
            const sidebar = document.getElementById('mySidebar');
            if (sidebar && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        }
    });
</script>
<script src="{{ asset('js/custom-ui.js') }}"></script>
