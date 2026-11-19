@props([
    'action' => '#',
    'placeholder' => 'Cari data...',
])

<div class="mb-8">
    <form method="GET" action="{{ $action }}" class="relative flex items-center gap-3">
        <div class="relative flex-1">
            <input type="text" name="q" value="{{ request('q') ?? request('search') }}" placeholder="{{ $placeholder }}"
                class="w-full rounded-2xl border border-white/80 backdrop-blur-md text-sm text-slate-800 placeholder-slate-400 font-medium outline-none transition-all focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10"
                style="background: rgba(255,255,255,0.75); padding: 14px 44px 14px 46px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="pointer-events-none text-slate-400" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            @if(request('q') || request('search'))
                <a href="{{ $action }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-600 text-xs font-bold px-1.5 py-0.5 rounded-lg hover:bg-rose-50 transition" title="Hapus Pencarian">
                    ✕
                </a>
            @endif
        </div>
        <button type="submit" aria-label="Cari Data"
            class="px-6 py-3.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white text-sm font-bold rounded-2xl transition-all flex items-center justify-center gap-2 shrink-0"
            style="box-shadow: 0 8px 20px rgba(225,29,72,0.2);">
            <span>Cari</span>
        </button>
    </form>
</div>