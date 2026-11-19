<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OSIS SMKN 1 Tarumajaya Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('index.css') }}">
</head>

<body class="h-full overflow-x-hidden">
    <div id="login-modal"
        class="fixed inset-0 hidden opacity-0 bg-slate-900/40 backdrop-blur-sm p-4 z-50 transition-all duration-300">
        <div
            class="w-full max-w-md rounded-[2.5rem] bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-slate-100 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-50 rounded-full -z-10"></div>

            <div class="relative mb-8">
                <div>
                    <span
                        class="inline-block px-3 py-1 text-xs font-semibold bg-rose-100 text-[#5f1319] rounded-full mb-3">
                        Sistem Informasi OSIS
                    </span>

                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">
                        Login OSIS
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Selamat datang kembali, Pengurus!
                    </p>
                </div>

                <button onclick="ModalSystem.close('login-modal')"
                    class="absolute top-0 right-0 p-2 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="login-form" action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="login-username" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email</label>
                    <div class="relative group">
                        <input type="email" id="login-username" name="email" required
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#5f1319]/10 focus:border-[#5f1319] focus:bg-white outline-none transition-all duration-200 placeholder:text-slate-400"
                            placeholder="posisi@osis.com" autocomplete="on">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2 ml-1">
                        <label for="login-password" class="text-sm font-bold text-slate-700">Password</label>
                    </div>
                    <input type="password" name="password" id="login-password" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#5f1319]/10 focus:border-[#5f1319] focus:bg-white outline-none transition-all duration-200 placeholder:text-slate-400"
                        placeholder="••••••••" autocomplete="on">
                </div>

                <button type="submit" id="login-submit-btn"
                    class="w-full py-4 bg-[#5f1319] text-white font-bold rounded-2xl hover:bg-rose-900 shadow-lg shadow-rose-200 hover:shadow-slate-200 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-2">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-6-3h12m0 0l-3-3m3 3l-3 3" />
                    </svg>

                    <span>Masuk</span>
                </button>
            </form>
        </div>
    </div>
</body>

</html>
