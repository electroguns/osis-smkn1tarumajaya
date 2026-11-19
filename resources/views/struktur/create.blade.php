<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Portal resmi OSIS SMKN 1 Tarumajaya. Temukan berita terbaru, program kerja, dan informasi penting lainnya seputar organisasi siswa kami. Bergabunglah untuk menjadi bagian dari perubahan positif di sekolah!" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Pengurus | Panel Manajemen</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <a href="{{ route('struktur.index') }}" aria-label="Manajemen Pengurus OSIS"
                    class="inline-flex items-center text-sm font-bold text-rose-600 hover:text-rose-700 transition gap-2 mb-2 group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">Registrasi <span
                        class="text-rose-600">Pengurus</span></h1>
                <p class="text-slate-500">Lengkapi detail di bawah untuk menambahkan anggota kepengurusan baru.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-5 shadow-sm">
                <div class="flex gap-3 text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm font-medium">
                        <p class="font-bold mb-1">Terjadi kesalahan:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4 space-y-6 text-slate-600">
                <div
                    class="rounded-[32px] bg-white/60 backdrop-blur-md p-8 border border-white shadow-xl shadow-slate-200/50">
                    <div
                        class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-rose-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Panduan Cepat</h3>
                    <ul class="space-y-4 text-sm leading-relaxed">
                        <li class="flex gap-3">
                            <span class="font-bold text-rose-600">01</span>
                            <p>Pastikan nama sesuai dengan kartu identitas siswa.</p>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-rose-600">02</span>
                            <p>Gunakan password yang kuat namun mudah diingat oleh pengurus.</p>
                        </li>
                    </ul>
                </div>

                <div
                    class="rounded-[32px] bg-gradient-to-br from-slate-800 to-slate-900 p-8 text-white shadow-2xl shadow-slate-900/20">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Standar Foto
                    </h4>
                    <p class="text-xs text-slate-400 leading-loose">
                        Sistem akan memotong foto secara otomatis. Gunakan rasio 4:6 dengan latar belakang polos atau
                        transparan untuk estetika terbaik pada <i>landing page</i>.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="rounded-[32px] bg-white p-8 md:p-10 shadow-2xl shadow-slate-200/60 border border-white">
                    <form action="{{ route('struktur.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    placeholder="Masukkan nama lengkap..."
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:border-rose-500 focus:bg-white" />
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Login</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="contoh@mail.com"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:border-rose-500 focus:bg-white" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kelas</label>
                                <input type="text" name="kelas" value="{{ old('kelas') }}" required
                                    placeholder="Contoh: XI RPL 2"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:border-rose-500 focus:bg-white" />
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Jabatan Kepengurusan</label>
                                <div class="relative">
                                    <select name="jabatan" required
                                        class="w-full rounded-2xl border px-5 py-4 pr-12 text-slate-900 outline-none transition focus:border-rose-500 focus:bg-white appearance-none cursor-pointer font-semibold
                                                    {{ $errors->has('jabatan') ? 'border-red-500' : 'border-slate-200 bg-slate-50' }}">
                                        <option value="" disabled {{ old('jabatan') ? '' : 'selected' }}>Pilih jabatan...</option>

                                        <option value="ketua" {{ old('jabatan') == 'ketua' ? 'selected' : '' }}>
                                            Ketua OSIS
                                        </option>

                                        <option value="wakil" {{ old('jabatan') == 'wakil' ? 'selected' : '' }}>
                                            Wakil Ketua OSIS
                                        </option>
                                        <option value="sekretaris" {{ old('jabatan') == 'sekretaris' ? 'selected' : '' }}>
                                            Sekretaris</option>
                                        <option value="bendahara" {{ old('jabatan') == 'bendahara' ? 'selected' : '' }}>
                                            Bendahara</option>

                                        <option value="ketua_divisi_humas"
                                            {{ old('jabatan') == 'ketua_divisi_humas' ? 'selected' : '' }}>Ketua Divisi
                                            Humas</option>
                                        <option value="ketua_divisi_agama"
                                            {{ old('jabatan') == 'ketua_divisi_agama' ? 'selected' : '' }}>Ketua Divisi
                                            Agama</option>
                                        <option value="ketua_divisi_olahraga"
                                            {{ old('jabatan') == 'ketua_divisi_olahraga' ? 'selected' : '' }}>Ketua Divisi
                                            Olahraga</option>
                                        <option value="ketua_divisi_artmedia"
                                            {{ old('jabatan') == 'ketua_divisi_artmedia' ? 'selected' : '' }}>Ketua Divisi
                                            Art & Media</option>

                                        <option value="anggota_divisi_humas"
                                            {{ old('jabatan') == 'anggota_divisi_humas' ? 'selected' : '' }}>Anggota Divisi
                                            Humas</option>
                                        <option value="anggota_divisi_agama"
                                            {{ old('jabatan') == 'anggota_divisi_agama' ? 'selected' : '' }}>Anggota Divisi
                                            Agama</option>
                                        <option value="anggota_divisi_olahraga"
                                            {{ old('jabatan') == 'anggota_divisi_olahraga' ? 'selected' : '' }}>Anggota
                                            Divisi Olahraga</option>
                                        <option value="anggota_divisi_artmedia"
                                            {{ old('jabatan') == 'anggota_divisi_artmedia' ? 'selected' : '' }}>Anggota
                                            Divisi Art & Media</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                @error('jabatan')
                                    <p class="text-red-500 text-xs mt-2 ml-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Upload Foto</label>
                                <label class="relative group cursor-pointer block">
                                    <input type="file" name="foto" id="foto-input" accept="image/*"
                                        class="hidden" />
                                    <div id="dropzone-visual"
                                        class="w-full rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center transition group-hover:border-rose-300 group-hover:bg-rose-50/50">
                                        <div class="flex flex-col items-center gap-3">
                                            <div id="icon-container"
                                                class="p-3 bg-white rounded-xl shadow-sm group-hover:scale-110 transition-transform text-rose-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </div>
                                            <p id="file-name" class="text-sm font-bold text-slate-700">Pilih File
                                                Gambar</p>
                                            <p id="file-size"
                                                class="text-[11px] text-slate-400 uppercase tracking-widest">PNG, JPG,
                                                JPEG (Maks. 5MB)</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password Akses</label>
                                <div class="relative">
                                    <input type="password" name="password" required
                                        placeholder="Buat password keamanan..."
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 outline-none transition input-focus-effect focus:border-rose-500 focus:bg-white" />
                                    <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                class="w-full group flex items-center justify-center gap-3 rounded-2xl bg-rose-600 px-8 py-5 text-white font-extrabold shadow-xl shadow-rose-200 hover:bg-rose-700 hover:shadow-rose-300/60 transition-all duration-300">
                                <span>Daftarkan Pengurus Sekarang</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <script src="{{ asset('js/Cst.min.js') }}"></script>
</body>

</html>
