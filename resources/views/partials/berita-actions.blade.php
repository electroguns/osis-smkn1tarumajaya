<div class="px-6 pb-6 pt-4 border-t border-slate-800/60 flex gap-3 bg-slate-900/30">
    <a href="{{ route('berita.edit', $berita) }}"
        class="flex-1 text-center py-2 text-xs font-bold text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 rounded-xl transition border border-amber-500/20">
        Sunting
    </a>

    @if ($canDelete)
        <form action="{{ route('berita.destroy', $berita) }}" method="POST" class="flex-1 delete-form">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="w-full text-center py-2 text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500/20 rounded-xl transition border border-red-500/20">
                Hapus
            </button>
        </form>
    @endif
</div>
