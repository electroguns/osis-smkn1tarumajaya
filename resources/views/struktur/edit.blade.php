<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal resmi OSIS SMKN 1 Tarumajaya. Temukan berita terbaru, program kerja, dan informasi penting lainnya seputar organisasi siswa kami. Bergabunglah untuk menjadi bagian dari perubahan positif di sekolah!" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Pengurus | Panel Manajemen</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .input-focus-effect:focus {
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1);
            border-color: #571319;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('scrollbar.css') }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-rose-200 via-slate-50 to-slate-100 bg-fixed font-[Poppins] antialiased">

    {{-- Pemanggilan Komponen Sidebar Dini --}}
    @include('dini')

    <div class="max-w-4xl mx-auto px-6 py-12 pt-20 md:pt-12">

        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <a href="{{ route('struktur.index') }}" aria-label="Manajemen Pengurus OSIS"
                    class="inline-flex items-center text-sm font-bold text-rose-600 hover:text-rose-700 transition gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">Edit <span class="text-rose-600">Profil</span></h1>
                <p class="text-slate-500">Perbarui informasi pengurus secara berkala untuk validitas data.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-5">
                <div class="flex gap-3 text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <ul class="text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4 space-y-6">
                <div class="rounded-[32px] bg-white p-6 shadow-xl shadow-slate-200/60 border border-white text-center">
                    <div class="relative mx-auto w-32 h-44 mb-4">
                        <div class="w-full h-full rounded-[24px] overflow-hidden bg-slate-100 shadow-inner border-4 border-white shadow-slate-200/50">
                            @if ($user->foto)
                                <img id="sidebar-preview" src="{{ asset('storage/struktural/' . $user->foto) }}"
                                    class="w-full h-full object-cover transition-all duration-500" alt="{{ $user->name }}">
                            @else
                                <div id="sidebar-placeholder" class="w-full h-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white text-4xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <img id="sidebar-preview" class="hidden w-full h-full object-cover" alt="Preview" loading="lazy">
                            @endif
                        </div>
                    </div>

                    <h3 class="font-bold text-lg text-slate-900 capitalize tracking-tight">{{ $user->name }}</h3>
                    <div class="inline-flex items-center px-3 py-1 mt-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold uppercase tracking-wider">
                        @if ($user->role === 'ketua_wakil')
                            {{ Str::contains(strtolower($user->name), 'wakil') ? 'Wakil Ketua OSIS' : 'Ketua OSIS' }}
                        @else
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        @endif
                    </div>
                </div>

                <div class="rounded-[24px] bg-rose-900 p-6 text-white shadow-xl shadow-rose-900/20">
                    <p class="text-rose-200 text-xs font-bold uppercase tracking-widest mb-2">Tips</p>
                    <p class="text-sm leading-relaxed text-rose-50">Gunakan foto berformat PNG tanpa background (transparan) untuk hasil visual yang lebih profesional di halaman utama.</p>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="rounded-[32px] bg-white p-8 md:p-10 shadow-xl shadow-slate-200/60 border border-white">
                    <form action="{{ route('struktur.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Contoh: Ahmad Subardjo"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white" />
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Login</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@mail.com"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kelas</label>
                                <input type="text" name="kelas" value="{{ old('kelas', $user->kelas) }}" placeholder="Contoh: XII MIPA 1"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Jabatan Kepengurusan</label>
                                <div class="relative">
                                    <select name="jabatan" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 pr-12 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white appearance-none cursor-pointer font-semibold">
                                        <option value="" disabled>Pilih jabatan...</option>

                                        @php
                                            $currentJabatan = old('jabatan', $user->role);
                                            if ($user->role === 'ketua_wakil') {
                                                $currentJabatan = Str::contains(strtolower($user->name), 'wakil') ? 'wakil ketua osis' : 'ketua osis';
                                            }
                                        @endphp

                                        <option value="ketua" {{ $currentJabatan == 'ketua' ? 'selected' : '' }}>Ketua OSIS</option>
                                        <option value="wakil" {{ $currentJabatan == 'wakil' ? 'selected' : '' }}>Wakil Ketua</option>
                                        <option value="sekretaris" {{ $currentJabatan == 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                        <option value="bendahara" {{ $currentJabatan == 'bendahara' ? 'selected' : '' }}>Bendahara</option>

                                        <option value="ketua_divisi_humas" {{ $currentJabatan == 'ketua_divisi_humas' ? 'selected' : '' }}>Ketua Divisi Humas</option>
                                        <option value="ketua_divisi_agama" {{ $currentJabatan == 'ketua_divisi_agama' ? 'selected' : '' }}>Ketua Divisi Agama</option>
                                        <option value="ketua_divisi_olahraga" {{ $currentJabatan == 'ketua_divisi_olahraga' ? 'selected' : '' }}>Ketua Divisi Olahraga</option>
                                        <option value="ketua_divisi_artmedia" {{ $currentJabatan == 'ketua_divisi_artmedia' ? 'selected' : '' }}>Ketua Divisi Art & Media</option>

                                        <option value="anggota_divisi_humas" {{ $currentJabatan == 'anggota_divisi_humas' ? 'selected' : '' }}>Anggota Divisi Humas</option>
                                        <option value="anggota_divisi_agama" {{ $currentJabatan == 'anggota_divisi_agama' ? 'selected' : '' }}>Anggota Divisi Agama</option>
                                        <option value="anggota_divisi_olahraga" {{ $currentJabatan == 'anggota_divisi_olahraga' ? 'selected' : '' }}>Anggota Divisi Olahraga</option>
                                        <option value="anggota_divisi_artmedia" {{ $currentJabatan == 'anggota_divisi_artmedia' ? 'selected' : '' }}>Anggota Divisi Art & Media</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">Foto Profil Saat Ini</label>
                                <div class="flex flex-col md:flex-row items-center gap-6 p-6 rounded-[24px] border-2 border-slate-100 bg-slate-50/50">
                                    <div class="relative group">
                                        <div class="w-32 h-44 rounded-2xl overflow-hidden bg-slate-200 border-4 border-white shadow-lg">
                                            @if ($user->foto)
                                                <img id="img-preview" src="{{ asset('storage/struktural/' . $user->foto) }}" class="w-full h-full object-cover" alt="Foto Pengurus" loading="lazy">
                                            @else
                                                <div id="placeholder-preview" class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <img id="img-preview" class="hidden w-full h-full object-cover" alt="Preview" loading="lazy">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-1 space-y-3">
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ganti Foto Baru</p>
                                        <label class="relative block cursor-pointer">
                                            <input type="file" name="foto" id="foto-input" accept="image/*" class="hidden" />
                                            <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 transition shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Pilih File Foto
                                            </div>
                                        </label>
                                        <p id="file-info" class="text-[11px] text-slate-400 leading-relaxed">Kosongkan jika tidak ingin mengubah foto profil.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password Baru</label>
                                <input type="password" name="password" placeholder="••••••••"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white" />
                                <p class="text-[11px] text-slate-400 mt-3 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Biarkan kosong jika tidak ingin mengubah password.
                                </p>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center rounded-2xl bg-rose-600 px-10 py-4 text-white font-bold shadow-lg shadow-rose-200 hover:bg-rose-700 hover:shadow-rose-300/40 transition-all duration-300">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <script src="{{asset('js/Est.min.js')}}"></script>
</body>
</html>