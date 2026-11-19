@php
    $isBackendFinance = (request()->routeIs('finance.index') || request()->is('finance*')) && Auth::check() && !request()->has('preview');
    if (!$isBackendFinance) {
        $finances = $finances->filter(fn($f) => ($f->status ?? '') === 'Menyetujui');
        $totalIncome = $finances->where('jenis', 'pemasukan')->sum('nominal');
        $totalExpense = $finances->where('jenis', 'pengeluaran')->sum('nominal');
        $balance = $totalIncome - $totalExpense;
    }
@endphp

<style>
    .finance-status-badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.35rem !important;
        padding: 0.25rem 0.65rem !important;
        line-height: 1 !important;
        border-radius: 9999px !important;
        font-size: 0.75rem !important;
    }
    .finance-status-badge svg {
        width: 13px !important;
        height: 13px !important;
        min-width: 13px !important;
        max-width: 13px !important;
        min-height: 13px !important;
        max-height: 13px !important;
        flex-shrink: 0 !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }
</style>

<section id="keuangan" class="py-20 bg-slate-50 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#5f1319]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 mb-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider">
                        Transparansi Dana
                    </span>
                    @if ($isBackendFinance)
                        <button type="button" onclick="openModal('infoModalFinance')"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all text-xs font-bold uppercase tracking-wider shadow-sm active:scale-95 cursor-pointer group"
                            title="Klik untuk melihat informasi hak akses halaman ini">
                            <span class="w-4 h-4 rounded-full bg-rose-200 text-rose-700 group-hover:bg-white group-hover:text-rose-600 flex items-center justify-center text-[10px] font-black transition-colors shrink-0">!</span>
                            <span>Info Hak Akses</span>
                        </button>
                    @endif
                </div>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
                    Statistik <span class="text-rose-600">Keuangan</span> OSIS
                </h2>
                <p class="mt-4 text-lg text-slate-500 leading-relaxed">
                    Wujud keterbukaan informasi penggunaan anggaran kegiatan OSIS SMKN 1 Tarumajaya secara berkala.
                </p>
            </div>

            @if ($isBackendFinance && in_array(auth()->user()->role, [
                    'ketua',
                    'wakil',
                    'sekretaris',
                    'bendahara',
                    'ketua_divisi_humas',
                    'ketua_divisi_agama',
                    'ketua_divisi_olahraga',
                    'ketua_divisi_artmedia',
                ]))
                <div class="flex-shrink-0 w-full md:w-auto">
                    <button type="button" onclick="openFinanceModal()" aria-label="Tambah Transaksi Keuangan"
                        class="group relative w-full md:w-auto inline-flex items-center justify-center gap-3 px-5 md:px-8 py-4 bg-gradient-to-r from-rose-500 to-[#5f1319] hover:from-rose-600 hover:to-[#5f1319] text-white text-sm md:text-base font-bold rounded-2xl md:rounded-[20px] transition-all duration-300 active:scale-[0.98] hover:shadow-[0_20px_40px_-15px_rgba(95,19,25,0.5)] overflow-hidden">

                        {{-- Efek Kilauan Refleksi (Glint Layer) --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-white/10 via-white/0 to-white/10 transition-opacity duration-500">
                        </div>

                        {{-- Label Teks --}}
                        <span class="relative z-10">
                            Tambah Transaksi
                        </span>

                        {{-- Ikon Plus Interaktif Berputar --}}
                        <div
                            class="relative z-10 h-7 w-7 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </button>
                </div>
            @endif
        </div>

        {{-- SweetAlert Flash Message Success --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: {!! json_encode(session('success')) !!},
                        background: '#5f1319',
                        color: '#e2e8f0',
                        confirmButtonColor: '#0ea5e9'
                    });
                });
            </script>
        @endif

        @if (session('error') || (isset($errors) && $errors->any()))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Perhatian!',
                        text: {!! json_encode(session('error') ?? (isset($errors) && $errors->any() ? $errors->first() : 'Terjadi kesalahan.')) !!},
                        background: '#5f1319',
                        color: '#e2e8f0',
                        confirmButtonColor: '#e11d48'
                    });
                });
            </script>
        @endif

        {{-- Grid Ringkasan Keuangan Dinamis --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Pemasukan</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp
                        {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#5f1319]/10 flex items-center justify-center text-[#5f1319]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                        <line x1="12" y1="4" x2="12" y2="20"></line>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Sisa Saldo Kas</p>
                    <h3 class="text-2xl font-bold mt-1 text-[#5f1319]">Rp {{ number_format($balance, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Bar Filter & Pencarian --}}
        <div
            class="bg-white/80 p-5 md:p-6 rounded-3xl shadow-[0_16px_32px_-8px_rgba(0,0,0,0.03)] border border-white/80 backdrop-blur-xl mb-6 flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="relative w-full lg:max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" id="finance-search" oninput="filterFinanceData()"
                    placeholder="Cari nominal uang atau keterangan pengeluaran..."
                    class="w-full pl-11 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition">
            </div>

            <div class="flex flex-wrap w-full lg:w-auto items-center gap-3">
                <div>
                    <select id="filter-year" onchange="filterFinanceData()"
                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition">
                        <option value="all">Semua Tahun</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <div>
                    <select id="filter-division" onchange="filterFinanceData()"
                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition">
                        <option value="all">Semua Divisi</option>
                        <option value="inti">Manajemen Inti</option>
                        <option value="humas">Divisi Humas</option>
                        <option value="artmedia">Divisi Artmedia</option>
                        <option value="olahraga">Divisi Olahraga</option>
                        <option value="agama">Divisi Agama</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Tabel Dinamis --}}
        <div class="bg-white/80 rounded-3xl shadow-[0_16px_32px_-8px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[820px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Keterangan
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Divisi</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Jenis</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">
                                Nominal</th>
                            @if ($isBackendFinance)
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="finance-table-body" class="divide-y divide-slate-100 text-slate-600 text-sm">
                        @forelse ($finances as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors"
                                data-year="{{ $item->tanggal->format('Y') }}" data-division="{{ $item->divisi }}"
                                data-type="{{ $item->jenis }}">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                    {{ $item->tanggal->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->keterangan }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-md 
                                        {{ $item->divisi == 'inti' ? 'bg-purple-50 text-purple-600' : '' }}
                                        {{ $item->divisi == 'humas' ? 'bg-blue-50 text-blue-600' : '' }}
                                        {{ $item->divisi == 'artmedia' ? 'bg-indigo-50 text-indigo-600' : '' }} 
                                        {{ $item->divisi == 'olahraga' ? 'bg-orange-50 text-orange-600' : '' }}
                                        {{ $item->divisi == 'agama' ? 'bg-emerald-50 text-emerald-600' : '' }} 
                                    ">
                                        {{ $item->divisi == 'inti' ? 'Manajemen Inti' : 'Divisi ' . ucfirst($item->divisi) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->jenis == 'pemasukan')
                                        <span
                                            class="px-2 py-1 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-full">Pemasukan</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-bold text-rose-600 bg-rose-50 rounded-full">Pengeluaran</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-bold whitespace-nowrap {{ $item->jenis == 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                @if ($isBackendFinance)
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        {{-- Status Badge & Approval Action --}}
                                        <div class="inline-flex flex-col items-center gap-1.5">
                                            @if (($item->status ?? 'Menunggu') === 'Menyetujui')
                                                <span class="finance-status-badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm" title="Transaksi Disetujui oleh Ketua/Wakil OSIS">
                                                    <svg width="13" height="13" class="w-3.5 h-3.5 text-emerald-600 shrink-0 inline-block align-middle" style="width:13px !important;height:13px !important;min-width:13px !important;max-width:13px !important;display:inline-block !important;vertical-align:middle !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                                    </svg>
                                                    <span class="inline-block leading-none">Menyetujui</span>
                                                </span>
                                            @elseif (($item->status ?? 'Menunggu') === 'Mengetahui')
                                                <span class="finance-status-badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200 shadow-sm" title="Telah Diketahui oleh Bendahara OSIS">
                                                    <svg width="13" height="13" class="w-3.5 h-3.5 text-sky-600 shrink-0 inline-block align-middle" style="width:13px !important;height:13px !important;min-width:13px !important;max-width:13px !important;display:inline-block !important;vertical-align:middle !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="inline-block leading-none">Mengetahui</span>
                                                </span>
                                            @else
                                                <span class="finance-status-badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm" title="Menunggu Konfirmasi">
                                                    <svg width="13" height="13" class="w-3.5 h-3.5 text-amber-600 shrink-0 inline-block align-middle" style="width:13px !important;height:13px !important;min-width:13px !important;max-width:13px !important;display:inline-block !important;vertical-align:middle !important;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <polyline points="12 6 12 12 16 14" />
                                                    </svg>
                                                    <span class="inline-block leading-none">Menunggu</span>
                                                </span>
                                            @endif

                                            {{-- Approval Action Buttons berdasarkan Role (Hanya aktif di Admin Panel, bukan di Preview Front-End) --}}
                                            @if (auth()->user()->role === 'bendahara' && ($item->status ?? 'Menunggu') === 'Menunggu')
                                                <form action="{{ route('finance.status', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Mengetahui">
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-lg bg-sky-600 hover:bg-sky-700 text-white shadow-sm transition-all hover:scale-105 active:scale-95"
                                                        title="Tandai transaksi telah Anda ketahui sebagai Bendahara">
                                                        <svg width="12" height="12" style="width:12px;height:12px;min-width:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                        <span>Tandai Mengetahui</span>
                                                    </button>
                                                </form>
                                            @elseif (in_array(auth()->user()->role, ['ketua', 'wakil']) && ($item->status ?? 'Menunggu') === 'Mengetahui')
                                                <form action="{{ route('finance.status', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Menyetujui">
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all hover:scale-105 active:scale-95"
                                                        title="Beri persetujuan resmi Ketua/Wakil atas transaksi ini">
                                                        <svg width="12" height="12" style="width:12px;height:12px;min-width:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>Setujui</span>
                                                    </button>
                                                </form>
                                            @elseif (in_array(auth()->user()->role, ['ketua', 'wakil']) && ($item->status ?? 'Menunggu') === 'Menunggu')
                                                <span class="text-[10px] text-slate-400 font-medium">Menunggu Bendahara</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @php
                                            $canEdit = in_array(auth()->user()->role, ['ketua', 'wakil', 'sekretaris', 'bendahara']);
                                            if (!$canEdit && str_contains(auth()->user()->role, 'ketua_divisi_')) {
                                                $userDivision = str_replace('ketua_divisi_', '', auth()->user()->role);
                                                $canEdit = ($item->divisi === $userDivision);
                                            }
                                        @endphp
                                        @if ($canEdit)
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" onclick="openEditFinanceModal(this)"
                                                    data-id="{{ $item->id }}"
                                                    data-tanggal="{{ $item->tanggal->format('Y-m-d') }}"
                                                    data-keterangan="{{ $item->keterangan }}"
                                                    data-divisi="{{ $item->divisi }}" data-jenis="{{ $item->jenis }}"
                                                    data-nominal="{{ $item->nominal }}"
                                                    class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                    title="Edit Data">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path
                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                        </path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDeleteFinance('{{ $item->id }}')"
                                                    class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                    title="Hapus Data">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10"
                                                            y2="17"></line>
                                                        <line x1="14" y1="11" x2="14"
                                                            y2="17"></line>
                                                    </svg>
                                                </button>

                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('finance.destroy', $item->id) }}" method="POST"
                                                    class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isBackendFinance ? 7 : 5 }}" class="p-12 text-center text-slate-400">Belum ada data rekaman
                                    transaksi keuangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="finance-empty-state" class="hidden p-12 text-center">
                <p class="text-sm font-semibold text-slate-700">Data keuangan tidak ditemukan</p>
            </div>
        </div>
    </div>
</section>

{{-- MODAL POPUP TAMBAH DATA --}}
@if ($isBackendFinance)
    <div id="finance-modal"
        class="fixed inset-0 z-[100] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white/95 w-full max-w-md rounded-3xl shadow-[0_25px_70px_rgba(15,23,42,0.18)] border border-white/60 overflow-visible relative transform scale-95 opacity-0 transition-all duration-300"
            id="modal-content">
            <div class="bg-[#5f1319] px-6 py-4 flex items-center justify-between text-white rounded-t-3xl">
                <h3 class="font-bold text-lg">Tambah Transaksi Kas</h3>
                <button onclick="closeFinanceModal()" class="text-white/80 hover:text-white text-xl">&times;</button>
            </div>
            <form action="{{ route('finance.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required
                        class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Keterangan / Deskripsi</label>
                    <input type="text" name="keterangan" required placeholder="Contoh: Pembelian Alat Tulis Kantor"
                        class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Penanggung Jawab</label>
                        @if (str_contains(auth()->user()->role, 'ketua_divisi_'))
                            @php
                                $userDivision = str_replace('ketua_divisi_', '', auth()->user()->role);
                                $divisiNames = [
                                    'inti' => 'Manajemen Inti',
                                    'humas' => 'Divisi Humas',
                                    'artmedia' => 'Divisi Artmedia',
                                    'olahraga' => 'Divisi Olahraga',
                                    'agama' => 'Divisi Agama'
                                ];
                            @endphp
                            <input type="text" readonly
                                class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl bg-slate-100 text-slate-600 font-semibold cursor-not-allowed"
                                value="{{ $divisiNames[$userDivision] ?? $userDivision }}">
                            <input type="hidden" name="divisi" value="{{ $userDivision }}">
                        @else
                            <select name="divisi" required
                                class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-rose-500 text-slate-800 font-semibold cursor-pointer">
                                <option value="inti">Manajemen Inti</option>
                                <option value="humas">Divisi Humas</option>
                                <option value="artmedia">Divisi Artmedia</option>
                                <option value="olahraga">Divisi Olahraga</option>
                                <option value="agama">Divisi Agama</option>
                            </select>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Jenis Transaksi</label>
                        <select name="jenis" required
                            class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-rose-500 text-slate-800 font-semibold cursor-pointer">
                            <option value="pengeluaran">Pengeluaran</option>
                            <option value="pemasukan">Pemasukan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Nominal Uang (Rupiah)</label>
                    <input type="number" name="nominal" min="1" required placeholder="Contoh: 500000"
                        class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 text-slate-800 font-semibold transition">
                </div>
                <div class="flex items-center justify-end gap-2 pt-3">
                    <button type="button" onclick="closeFinanceModal()"
                        class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold bg-[#5f1319] hover:bg-[#4a0e13] text-white rounded-2xl shadow-lg shadow-rose-900/20 active:scale-95 transition">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- MODAL POPUP EDIT DATA --}}
@if ($isBackendFinance)
    @php
        $canShowEditModal = in_array(auth()->user()->role, ['ketua', 'wakil', 'sekretaris', 'bendahara']);
        if (!$canShowEditModal && str_contains(auth()->user()->role, 'ketua_divisi_')) {
            $canShowEditModal = true;
        }
    @endphp
    @if ($canShowEditModal)
        <div id="finance-edit-modal"
            class="fixed inset-0 z-[100] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4">
            <div class="bg-white/95 w-full max-w-md rounded-3xl shadow-[0_25px_70px_rgba(15,23,42,0.18)] border border-white/60 overflow-visible relative transform scale-95 opacity-0 transition-all duration-300"
                id="edit-modal-content">
                <div class="bg-amber-600 px-6 py-4 flex items-center justify-between text-white rounded-t-3xl">
                    <h3 class="font-bold text-lg">Edit Transaksi Kas</h3>
                    <button onclick="closeEditFinanceModal()"
                        class="text-white/80 hover:text-white text-xl">&times;</button>
                </div>

                <form id="edit-finance-form" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Tanggal Kegiatan</label>
                        <input type="date" id="edit-tanggal" name="tanggal" required
                            class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 text-slate-800 font-semibold transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Keterangan / Deskripsi</label>
                        <input type="text" id="edit-keterangan" name="keterangan" required
                            class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 text-slate-800 font-semibold transition">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Penanggung Jawab</label>
                            @if (str_contains(auth()->user()->role, 'ketua_divisi_'))
                                <input type="text" id="edit-divisi-display" readonly
                                    class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl bg-slate-100 text-slate-600 font-semibold cursor-not-allowed"
                                    value="">
                                <input type="hidden" id="edit-divisi" name="divisi">
                            @else
                                <select id="edit-divisi" name="divisi" required
                                    class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-amber-500 text-slate-800 font-semibold cursor-pointer">
                                    <option value="inti">Manajemen Inti</option>
                                    <option value="humas">Divisi Humas</option>
                                    <option value="artmedia">Divisi Artmedia</option>
                                    <option value="olahraga">Divisi Olahraga</option>
                                    <option value="agama">Divisi Agama</option>
                                </select>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Jenis Transaksi</label>
                            <select id="edit-jenis" name="jenis" required
                                class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-amber-500 text-slate-800 font-semibold cursor-pointer">
                                <option value="pengeluaran">Pengeluaran</option>
                                <option value="pemasukan">Pemasukan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 ml-1">Nominal Uang (Rupiah)</label>
                        <input type="number" id="edit-nominal" name="nominal" min="1" required
                            class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-2xl focus:outline-none focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 text-slate-800 font-semibold transition">
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3">
                        <button type="button" onclick="closeEditFinanceModal()"
                            class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold bg-amber-600 hover:bg-amber-700 text-white rounded-2xl shadow-lg shadow-amber-600/20 active:scale-95 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endif

<script src="{{ asset('js/stat.min.js') }}"></script>