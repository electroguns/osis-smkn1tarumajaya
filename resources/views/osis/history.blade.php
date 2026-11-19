<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Calon Pengurus | Dashboard</title>
        @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div class="max-w-7xl mx-auto px-6 py-12 pt-20 md:pt-12">

        {{-- Header --}}
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-12 border-b border-slate-200/60 pb-8">
            <div class="space-y-2">
                <div class="flex flex-wrap gap-2">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.osis.approved') }}" aria-label="Landing Page"
                            class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>

                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold uppercase tracking-wider">
                            <span>Admin Panel</span>
                        </div>

                        <!-- Tombol Info -->
                        <button onclick="openModal('infoModalPendaftar')"
                            class="ml-2 text-rose-600 hover:text-rose-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">
                    Calon <span class="text-rose-600">OSIS</span>
                </h1>
                <p class="text-slate-500 text-base md:text-lg max-w-xl leading-relaxed">
                    Pantau, tinjau, dan seleksi data formulir berkas para pendaftar siswa berdasarkan analisis
                    kecerdasan buatan.
                </p>
            </div>
        </div>

        <!-- Modal khusus halaman Pendaftar -->
        <div id="infoModalPendaftar"
            class="fixed inset-0 top-0 left-0 w-full h-full bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8 max-w-md w-full border border-slate-100">
                <h2 class="text-xl font-bold mb-4 text-slate-800">Informasi Pendaftar</h2>
                <p class="text-slate-600 mb-6 text-sm leading-relaxed">
                    Hanya Ketua dan Wakil OSIS yang dapat menyetujui atau menolak calon pendaftar.
                </p>
                <button type="button" onclick="closeModal('infoModalPendaftar')"
                    class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition">
                    Mengerti
                </button>
            </div>
        </div>

        {{-- Search Form --}}
        <x-search-form :action="request()->url()" placeholder="Cari calon osis..." />

        {{-- Main Grid Card Container --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ activeCandidate: null }">
            @forelse ($pendaftars as $p)
                @php
                    $ai = is_string($p->analisis_ai) ? json_decode($p->analisis_ai, true) : $p->analisis_ai;
                    $ai = $ai ?? [];
                    $probabilitas = $ai['probabilitas_total'] ?? 0;
                    $rekomendasi = $ai['rekomendasi'] ?? 'PERLU DIWAWANCARA';

                    // Konfigurasi Badge Rekomendasi
                    $recomColor = 'bg-slate-50 text-slate-600 border-slate-200';
                    if ($rekomendasi == 'DITERIMA' || $rekomendasi == 'TERIMA') {
                        $recomColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    } elseif ($rekomendasi == 'TOLAK') {
                        $recomColor = 'bg-rose-50 text-rose-700 border-rose-200';
                    }

                    // Konfigurasi Progress Bar Probabilitas
                    if ($probabilitas <= 30) {
                        $colorClass = 'bg-rose-50 text-rose-700 border-rose-200/60';
                        $barColor = 'from-rose-500 to-red-600';
                    } elseif ($probabilitas > 30 && $probabilitas <= 55) {
                        $colorClass = 'bg-amber-50 text-amber-700 border-amber-200/60';
                        $barColor = 'from-amber-400 to-orange-500';
                    } else {
                        $colorClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                        $barColor = 'from-emerald-400 to-teal-600';
                    }
                @endphp

                {{-- Card Container --}}
                <div class="candidate-card group relative bg-white rounded-3xl border border-slate-100 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl hover:border-rose-100 transition-all duration-300 transform hover:-translate-y-1"
                    data-name="{{ strtolower($p->nama) }}" data-division="{{ strtolower($p->divisi) }}"
                    data-date="{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y') }}">

                    <div class="space-y-4">
                        {{-- Profile Header Row --}}
                        <div class="flex items-center gap-4">

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-base text-slate-800 truncate capitalize"
                                    title="{{ $p->nama }}">
                                    {{ $p->nama }}
                                </h3>
                                <p class="text-xs font-semibold text-slate-400">Kelas {{ $p->kelas }}</p>
                            </div>

                            {{-- Status Badge Pojok Kanan --}}
                            <span
                                class="text-[11px] font-bold px-2.5 py-1 rounded-full border 
                        {{ $p->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($p->status === 'rejected' ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-amber-50 text-amber-600 border-amber-100') }}">
                                {{ $p->status === 'approved' ? 'Lolos' : ($p->status === 'rejected' ? 'Gagal' : 'Pending') }}
                            </span>
                        </div>

                        {{-- Divisi Pilihan --}}
                        <div
                            class="inline-flex items-center gap-2 bg-rose-50/60 border border-rose-100/50 px-3 py-1.5 rounded-xl">
                            <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-rose-800">{{ $p->divisi }}</span>
                        </div>

                        {{-- Mini AI Bar (Clean version) --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 space-y-2">
                            <div class="flex justify-between text-xs font-bold text-slate-600">
                                <span>Kecocokan AI</span>
                                <span class="text-slate-800">{{ $probabilitas }}%</span>
                            </div>
                            <div class="w-full bg-slate-200/70 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-gradient-to-r {{ $barColor }} h-1.5 rounded-full transition-all duration-500"
                                    style="width: {{ $probabilitas }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="mt-5 pt-4 border-t border-slate-100 space-y-3">

                        {{-- Tombol Utama: Picu Modal Detail --}}
                        <button type="button"
                            @click="activeCandidate = {{ json_encode([
                                'nama' => $p->nama,
                                'kelas' => $p->kelas,
                                'divisi' => $p->divisi,
                                'email' => $p->email,
                                'no_telp' => $p->no_telp,
                                'motivasi' => $p->motivasi,
                                'probabilitas' => $probabilitas,
                                'rekomendasi' => $rekomendasi,
                                'recomColor' => $recomColor,
                                'analisis_parameter' => $ai['analisis_parameter'] ?? null,
                                'hard_skills' => is_string($p->hard_skills) ? json_decode($p->hard_skills, true) : $p->hard_skills,
                                'soft_skills' => is_string($p->soft_skills) ? json_decode($p->soft_skills, true) : $p->soft_skills,
                            ]) }}"
                            class="w-full flex justify-center items-center py-2.5 rounded-xl bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-xs font-bold text-slate-600 hover:text-rose-700 transition-all">
                            📄 Lihat Detail & Motivasi
                        </button>

                        {{-- Tombol Keputusan (Hanya untuk Pending & Role Otoritas) --}}
                        @if ($p->status === 'pending')
                            @if (Auth::check() && in_array(strtolower(Auth::user()->role), ['ketua', 'wakil']))
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" onclick="approveSiswa({{ $p->id }})"
                                        class="py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-emerald-600/10 text-center">
                                        ✓ Terima
                                    </button>
                                    <button type="button" onclick="rejectSiswa({{ $p->id }})"
                                        class="py-2 px-3 bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 text-xs font-bold rounded-xl transition-all text-center">
                                        ✕ Tolak
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                    <p class="text-slate-400 font-medium">Belum ada calon pendaftar yang terdata.</p>
                </div>
            @endforelse

            {{-- ======================================================== --}}
            {{-- MODAL DETAIL INTERAKTIF (DI-RENDER GLOBAL OLEH ALPINE.JS) --}}
            {{-- ======================================================== --}}
            <div x-show="activeCandidate" x-cloak
                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                {{-- Box Modal --}}
                <div @click.away="activeCandidate = null"
                    class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 flex flex-col"
                    x-transition:enter="transition ease-out duration-300 transform scale-95"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                    {{-- Header Modal --}}
                    <div
                        class="p-6 border-b border-slate-100 flex justify-between items-start sticky top-0 bg-white z-10">
                        <div>
                            <h4 class="text-xl font-bold text-slate-900" x-text="activeCandidate?.nama"></h4>
                            <p class="text-xs font-semibold text-slate-400 mt-1">
                                Kelas <span x-text="activeCandidate?.kelas"></span> • Divisi <span
                                    class="text-rose-600" x-text="activeCandidate?.divisi"></span>
                            </p>
                        </div>
                        <button @click="activeCandidate = null"
                            class="p-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Isi Konten Modal --}}
                    <div class="p-6 space-y-6 overflow-y-auto">
                        {{-- Grid Kontak --}}
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs font-medium text-slate-600">
                            <div class="flex items-center gap-2 truncate"><span>📧</span> <span
                                    x-text="activeCandidate?.email"></span></div>
                            <div class="flex items-center gap-2"><span>📞</span> <span>+62 </span><span
                                    x-text="activeCandidate?.no_telp"></span></div>
                        </div>

                        {{-- Teks Motivasi Pendaftar --}}
                        <div class="space-y-2">
                            <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Surat Pernyataan
                                Motivasi</h5>
                            <div
                                class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-sm text-slate-600 leading-relaxed italic font-medium">
                                "<span x-text="activeCandidate?.motivasi"></span>"
                            </div>
                        </div>

                        {{-- Ringkasan Penilaian AI --}}
                        <div class="space-y-3">
                            <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Hasil Ekstraksi
                                & Analisis AI Expert</h5>

                            {{-- Badge Rekomendasi di Modal --}}
                            <div
                                class="flex justify-between items-center p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-xs font-bold text-slate-500">Rekomendasi Mutlak:</span>
                                <span class="px-3 py-1 font-black rounded-lg border text-xs tracking-wide"
                                    :class="activeCandidate?.recomColor" x-text="activeCandidate?.rekomendasi"></span>
                            </div>

                            {{-- Dinamis Parameter Analisis --}}
                            <template x-if="activeCandidate?.analisis_parameter">
                                <div class="border border-slate-100 rounded-2xl p-4 space-y-3.5 bg-white shadow-sm">
                                    <template x-for="(detail, param) in activeCandidate.analisis_parameter"
                                        :key="param">
                                        <div
                                            class="space-y-1 pb-2.5 border-b border-slate-100 last:pb-0 last:border-b-0">
                                            <div
                                                class="flex justify-between font-bold text-xs text-slate-700 capitalize">
                                                <span x-text="param.replace(/_/g, ' ')"></span>
                                                <span class="text-rose-600" x-text="detail.skor + '/100'"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 leading-snug italic"
                                                x-text="'&ldquo;' + detail.alasan + '&rdquo;'"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Skills Badges --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Hard Skills --}}
                            <div class="space-y-2">
                                <h5 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Hard
                                    Skills</h5>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="skill in activeCandidate?.hard_skills" :key="skill">
                                        <span
                                            class="text-[11px] bg-rose-50 text-rose-700 border border-rose-100 px-2.5 py-0.5 rounded-lg font-bold"
                                            x-text="skill"></span>
                                    </template>
                                    <template
                                        x-if="!activeCandidate?.hard_skills || activeCandidate?.hard_skills.length === 0">
                                        <span class="text-xs text-slate-400 italic">Tidak terdeteksi</span>
                                    </template>
                                </div>
                            </div>

                            {{-- Soft Skills --}}
                            <div class="space-y-2">
                                <h5 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Soft
                                    Skills</h5>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="skill in activeCandidate?.soft_skills" :key="skill">
                                        <span
                                            class="text-[11px] bg-teal-50 text-teal-700 border border-teal-100 px-2.5 py-0.5 rounded-lg font-bold"
                                            x-text="skill"></span>
                                    </template>
                                    <template
                                        x-if="!activeCandidate?.soft_skills || activeCandidate?.soft_skills.length === 0">
                                        <span class="text-xs text-slate-400 italic">Tidak terdeteksi</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end rounded-b-3xl">
                        <button type="button" @click="activeCandidate = null"
                            class="px-5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-600 rounded-xl transition-all">
                            Selesai Meninjau
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Total Count Label --}}
        <p class="mt-10 text-center text-slate-400 text-sm italic">
            Total {{ $pendaftars->total() }} Calon Pendaftar Terdata
        </p>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $pendaftars->links() }}
        </div>
    </div>

    <script>
        function approveSiswa(id) {
            let urlApprove = "{{ route('admin.osis.approve', ':id') }}".replace(':id', id);
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menyetujui siswa ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Setujui!',
                customClass: {
                    popup: 'rounded-[24px]',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-medium px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(urlApprove, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response status error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message ||
                                    'Siswa telah disetujui.',
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'rounded-[24px]',
                                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                                }
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menyetujui siswa.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Coba Lagi',
                                customClass: {
                                    popup: 'rounded-[24px]',
                                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                                }
                            });
                        });
                }
            });
        }

        function rejectSiswa(id) {
            let urlReject = "{{ route('admin.osis.reject', ':id') }}".replace(':id', id);
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menolak pendaftar ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[24px]',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-medium px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(urlReject, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then((data) => {
                            if (data.success || data.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message || 'Pendaftar telah ditolak.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'rounded-[24px]',
                                        confirmButton: 'rounded-xl font-bold px-4 py-2'
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal memproses permintaan.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'rounded-[24px]',
                                        confirmButton: 'rounded-xl font-bold px-4 py-2'
                                    }
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menolak pendaftar.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Coba Lagi',
                                customClass: {
                                    popup: 'rounded-[24px]',
                                    confirmButton: 'rounded-xl font-bold px-4 py-2'
                                }
                            });
                        });
                }
            });
        }

        function showDetailModal(id) {
            let urlDetail = "{{ route('admin.osis.detail', ':id') }}".replace(':id', id);
            fetch(urlDetail)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').innerText = data.nama;
                    document.getElementById('modal-content').innerHTML = `
                    <p><strong>Kelas:</strong> ${data.kelas}</p>
                    <p><strong>Analisis:</strong> ${data.analisis}</p>
                    <p><strong>Motivasi:</strong> ${data.motivasi}</p>
                `;

                    const modal = document.getElementById('detail-modal');
                    modal.classList.remove('invisible', 'opacity-0');
                    modal.classList.add('visible', 'opacity-100');
                })
                .catch(error => {
                    console.error('Error fetching student details:', error);
                });
        }

        function closeModal() {
            const modal = document.getElementById('detail-modal');
            modal.classList.remove('visible', 'opacity-100');
            modal.classList.add('invisible', 'opacity-0');
        }
    </script>

    <!-- Modal Structure -->
    <div id="detail-modal"
        class="invisible opacity-0 fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h3 id="modal-title" class="text-lg font-bold text-slate-900">Detail Siswa</h3>
            <div id="modal-content" class="mt-4 text-slate-600">
                <!-- Dynamic content -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();

            const cards = document.querySelectorAll('.candidate-card');

            cards.forEach(card => {

                const division = card.dataset.division;
                const name = card.dataset.name;
                const date = card.dataset.date;

                const isMatch =
                    name.includes(keyword) ||
                    date.includes(keyword) ||
                    division.includes(keyword);

                if (isMatch) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

    <script src="{{ asset('js/infomodal.min.js') }}"></script>
</body>

</html>
