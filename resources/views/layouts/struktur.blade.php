  <section id="struktur" class="py-16 sm:py-24 bg-slate-50/50 overflow-x-clip">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 overflow-x-hidden">
          <div class="text-center mb-12">
              <span class="text-xs font-bold tracking-[0.3em] uppercase text-rose-500">Organisasi</span>
              <div class="flex items-center justify-center gap-3">
                  <h2 class="text-3xl sm:text-4xl font-black text-[#571319] leading-tight">
                      Struktur Pengurus OSIS

                      @if (Auth::check() && !request()->has('preview'))
                          <a href="{{ route('struktur.index') }}" title="Kelola Data"
                              class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 ml-2 rounded-full bg-rose-500 text-white shadow-md shadow-rose-200 transition-all duration-300 hover:bg-rose-600 hover:scale-110 hover:-rotate-12 active:scale-95 align-middle">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
                                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z">
                                  </path>
                                  <line x1="6" y1="10" x2="14" y2="10"></line>
                                  <line x1="6" y1="14" x2="10" y2="14"></line>
                              </svg>
                          </a>
                      @endif  
                  </h2>
              </div>
              <div class="w-12 h-1 bg-rose-500 mx-auto mt-4 rounded-full"></div>
              <p class="text-slate-500 mt-4 max-w-lg mx-auto text-sm sm:text-base">
                  Sinergi pemimpin muda yang menggerakkan inovasi di lingkungan SMKN 1 Tarumajaya.
              </p>
          </div>

          @php
              $ketua = ($struktur['ketua'] ?? collect())->first();
              $wakil = ($struktur['wakil'] ?? collect())->first();

              $sekretaris = $struktur['sekretaris'] ?? collect();
              $bendahara = $struktur['bendahara'] ?? collect();

              $divisions = [
                  'humas' => 'Humas',
                  'agama' => 'Agama',
                  'olahraga' => 'Olahraga',
                  'artmedia' => 'Art & Media',
              ];
          @endphp

          {{-- Pengurus Inti OSIS --}}
          <div class="p-8 py-8 bg-[#f8fafc] flex justify-center">
              <div class="max-w-2xl w-full flex flex-col gap-6">

                  {{-- Wrapper Ketua & Wakil --}}
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                      {{-- Ketua OSIS --}}
                      <div
                          class="group bg-white p-8 rounded-[24px] border border-slate-200 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02] hover:shadow-[0_20px_40px_-10px_rgba(71,85,105,0.25)] hover:border-rose-300">
                          <div
                              class="group-hover:border-rose-400 group-hover:border-2 w-24 h-24 rounded-full border-4 border-white shadow-[0_8px_20px_-4px_rgba(87,19,25,0.4)] overflow-hidden mb-6 flex items-center justify-center">
                              <img src="{{ optional($ketua)->foto ? asset('storage/struktural/' . $ketua->foto) : asset('image/fotoosis.png') }}"
                                  alt="Ketua OSIS"
                                  class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110 {{ !optional($ketua)->name ? 'grayscale opacity-50' : '' }}">
                          </div>

                          <h3 class="font-bold text-xl text-slate-900">
                              {{ optional($ketua)->name ?? 'Menunggu Pengurus' }}
                          </h3>

                          <p class="text-sm font-bold text-[#571319] uppercase tracking-wide mt-1">
                              KETUA OSIS
                          </p>

                          @if (optional($ketua)->name)
                              <span
                                  class="text-xs text-slate-600 mt-2 bg-slate-100 inline-block px-3 py-1 rounded-full">
                                  {{ $ketua->kelas ?? '-' }}
                              </span>
                          @endif

                      </div>

                      {{-- Wakil Ketua OSIS --}}
                      <div
                          class="group bg-white p-8 rounded-[24px] border border-slate-200 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02] hover:shadow-[0_20px_40px_-10px_rgba(71,85,105,0.25)] hover:border-rose-300">
                          <div
                              class="group-hover:border-rose-400 group-hover:border-2 w-24 h-24 rounded-full border-4 border-white shadow-[0_8px_20px_-4px_rgba(87,19,25,0.4)] overflow-hidden mb-6 flex items-center justify-center">
                              <img src="{{ optional($wakil)->foto ? asset('storage/struktural/' . $wakil->foto) : asset('image/fotoosis.png') }}"
                                  alt="Wakil Ketua OSIS"
                                  class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110 {{ !optional($wakil)->name ? 'grayscale opacity-50' : '' }}">
                          </div>

                          <h3 class="font-bold text-xl text-slate-900">
                              {{ optional($wakil)->name ?? 'Menunggu Pengurus' }}
                          </h3>

                          <p class="text-sm font-bold text-[#571319] uppercase tracking-wide mt-1">
                              WAKIL KETUA
                          </p>

                          @if (optional($wakil)->name)
                              <span
                                  class="text-xs text-slate-600 mt-2 bg-slate-100 inline-block px-3 py-1 rounded-full">
                                  {{ $wakil->kelas ?? '-' }}
                              </span>
                          @endif

                      </div>
                  </div>

                  {{-- Wrapper Sekretaris & Bendahara --}}
                  <div
                      class="group bg-white p-8 rounded-[24px] border border-slate-200 shadow-[0_8px_20px_-4px_rgba(87,19,25,0.4)]
                        transition-all duration-300 ease-out hover:-translate-y-2 hover:scale-[1.02]
                        hover:shadow-[0_20px_40px_-10px_rgba(71,85,105,0.25)] hover:border-rose-300">

                      <div
                          class="flex flex-col md:flex-row justify-center items-center md:items-stretch gap-10 md:gap-0">

                          {{-- SEKRETARIS --}}
                          <div class="flex flex-col items-center flex-1">

                              <h2 class="text-sm font-bold tracking-[0.2em] text-[#571319] uppercase mb-6">
                                  Sekretaris
                              </h2>

                              <div class="flex justify-center items-start gap-6 w-full">

                                  @foreach ($sekretaris->isEmpty() ? [json_decode('{}')] : $sekretaris as $item)
                                      <div class="flex flex-col items-center text-center">

                                          <div
                                              class="group-hover:border-rose-400 group-hover:border-2 w-24 h-24 rounded-full overflow-hidden border-4 border-white
                                               shadow-[0_8px_20px_-4px_rgba(87,19,25,0.4)] mb-3">

                                              <img src="{{ $item->foto ?? null ? asset('storage/struktural/' . $item->foto) : asset('image/fotoosis.png') }}"
                                                  alt="{{ $item->name ?? 'Sekretaris' }}" loading="lazy"
                                                  class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110 {{ !($item->name ?? null) ? 'grayscale opacity-50' : '' }}">
                                          </div>

                                          <h3
                                              class="font-bold text-slate-800 text-sm leading-tight
                                                    text-center min-h-[40px] flex items-center justify-center max-w-[110px]">
                                              {{ $item->name ?? 'Menunggu Pengurus' }}
                                          </h3>

                                          @if (isset($item->name))
                                              <span
                                                  class="mt-2 text-[11px] px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">
                                                  {{ $item->kelas ?? '-' }}
                                              </span>
                                          @endif

                                      </div>
                                  @endforeach

                              </div>
                          </div>

                          {{-- Garis pembatas vertical --}}
                          <div class="hidden md:block w-px bg-rose-400 mx-10 self-stretch"></div>

                          {{-- BENDAHARA --}}
                          <div class="flex flex-col items-center flex-1">

                              <h2 class="text-sm font-bold tracking-[0.2em] text-[#571319] uppercase mb-6">
                                  Bendahara
                              </h2>

                              <div class="flex justify-center items-start gap-6 w-full">

                                  @foreach ($bendahara->isEmpty() ? [json_decode('{}')] : $bendahara as $item)
                                      <div class="flex flex-col items-center text-center">

                                          <div
                                              class="group-hover:border-rose-400 group-hover:border-2 w-24 h-24 rounded-full overflow-hidden border-4 border-white
                                                shadow-[0_8px_20px_-4px_rgba(87,19,25,0.4)] mb-3">

                                              <img src="{{ $item->foto ?? null ? asset('storage/struktural/' . $item->foto) : asset('image/fotoosis.png') }}"
                                                  alt="{{ $item->name ?? 'Bendahara' }}" loading="lazy"
                                                  class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110 {{ !($item->name ?? null) ? 'grayscale opacity-50' : '' }}">
                                          </div>

                                          <h3
                                              class="font-bold text-slate-800 text-sm leading-tight
                                                text-center min-h-[40px] flex items-center justify-center max-w-[110px]">
                                              {{ $item->name ?? 'Menunggu Pengurus' }}
                                          </h3>

                                          @if (isset($item->name))
                                              <span
                                                  class="mt-2 text-[11px] px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">
                                                  {{ $item->kelas ?? '-' }}
                                              </span>
                                          @endif

                                      </div>
                                  @endforeach

                              </div>
                          </div>

                      </div>
                  </div>
              </div>
          </div>

          {{-- Divisi --}}
          <div class="p-8 pt-0 pb-16 bg-gray-50 flex justify-center">
              <div class="max-w-6xl w-full bg-white p-8 md:p-12 rounded-3xl border border-gray-100 shadow-sm">

                  <h2
                      class="text-center font-black text-[#571319] tracking-[0.2em] text-sm uppercase mb-12 flex items-center justify-center gap-3">
                      <span class="w-8 h-[1px] bg-slate-400"></span>
                      Bidang Divisi Kerja
                      <span class="w-8 h-[1px] bg-slate-400"></span>
                  </h2>

                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                      @foreach ($divisions as $key => $label)
                          @php
                              $leader = optional($struktur['ketua_divisi_' . $key] ?? collect())->first();
                              $members = $struktur['anggota_divisi_' . $key] ?? collect();
                          @endphp

                          <div @if ($leader) onclick="document.getElementById('modal_{{ $key }}').showModal()" @endif
                              class="group relative bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all duration-300 flex flex-col items-center text-center overflow-hidden {{ $leader ? 'cursor-pointer hover:shadow-md hover:border-rose-400' : 'opacity-60' }}">

                              {{-- Foto Ketua Divisi --}}
                              <div
                                  class="w-20 h-20 rounded-full overflow-hidden border-2 border-white shadow-md shadow-blue-100 mb-4 {{ $leader ? 'group-hover:scale-105 transition-transform duration-300' : '' }} flex items-center justify-center bg-gray-50">
                                  @if ($leader && $leader->foto)
                                      <img src="{{ asset('storage/struktural/' . $leader->foto) }}"
                                          alt="Ketua Divisi {{ $label }}" loading="lazy"
                                          class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110">
                                  @else
                                      <img src="{{ asset('image/fotoosis.png') }}" alt="Default Photo"
                                          class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110">
                                  @endif
                              </div>

                              {{-- Nama Divisi & Nama Ketua --}}
                              <h3
                                  class="text-base font-bold text-gray-800 tracking-wide {{ $leader ? 'group-hover:text-rose-500 transition-colors' : '' }}">
                                  {{ Str::upper($label) }}
                              </h3>

                              <div class="mt-2 text-xs text-slate-400 font-medium">
                                  <span
                                      class="text-[10px] text-slate-400 block uppercase tracking-wider font-semibold">Ketua
                                      Divisi</span>
                                  <span
                                      class="text-slate-700 font-bold block truncate max-w-[160px] group-hover:text-slate-900">{{ optional($leader)->name ?? 'Belum Tersedia' }}</span>
                              </div>
                              @if ($leader && $leader->kelas)
                                  <span
                                      class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $leader->kelas }}</span>
                              @else
                                  <span class="text-[10px] text-slate-400 mt-0.5 font-medium">-</span>
                              @endif

                              @if ($leader)
                                  <span
                                      class="mt-4 text-[10px] font-bold text-rose-500 bg-rose-50 px-3 py-1 rounded-full opacity-80 group-hover:opacity-100 group-hover:bg-[#571319] group-hover:text-white transition-all duration-300 flex items-center gap-1">
                                      Lihat Anggota ➔
                                  </span>
                              @else
                                  <span
                                      class="mt-4 text-[10px] font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                                      Belum Tersedia
                                  </span>
                              @endif
                          </div>

                          {{-- Pop-up Modal Divisi --}}
                          @if ($leader)
                              <dialog id="modal_{{ $key }}"
                                  class="modal p-0 rounded-2xl shadow-2xl border border-gray-100
                                        max-w-2xl w-11/12 outline-none
                                        backdrop:bg-black/30 backdrop-blur-sm
                                        animate-[fadeIn_.2s_ease-out]">
                                  <div class="p-6 bg-white rounded-2xl">
                                      <div class="flex justify-between items-center pb-5 mb-6 border-b border-gray-100">
                                          <div>
                                              <h3 class="text-lg font-bold text-gray-900">
                                                  Anggota Divisi {{ $label }}
                                              </h3>

                                              <p class="text-xs text-gray-400 mt-1">
                                                  Daftar pengurus dalam divisi ini
                                              </p>
                                          </div>
                                          <button
                                              onclick="document.getElementById('modal_{{ $key }}').close()"
                                              class="w-8 h-8 flex items-center justify-center rounded-lg
                                                    text-gray-400 hover:text-gray-700 hover:bg-gray-100
                                                    transition focus:outline-none focus:ring-0 text-xl">
                                              &times;
                                          </button>
                                      </div>

                                      <div
                                          class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-1">
                                          @forelse ($members as $member)
                                              <div
                                                  class="flex items-center gap-3 p-3 bg-white border border-gray-100
                                                        rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5
                                                        transition-all duration-300">
                                                  <div
                                                      class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-gray-100 flex-shrink-0">
                                                      @if ($member->foto)
                                                          <img src="{{ asset('storage/struktural/' . $member->foto) }}"
                                                              alt="{{ $member->name }}"
                                                              class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110">
                                                      @else
                                                          <img src="{{ asset('image/fotoosis.png') }}" alt="Default"
                                                              class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110">
                                                      @endif
                                                  </div>
                                                  <div class="min-w-0">
                                                      <p class="text-sm font-semibold text-gray-900 truncate">
                                                          {{ $member->name }}
                                                      </p>

                                                      <p class="text-xs text-gray-400 truncate mt-0.5">
                                                          {{ $member->role ? Str::title(str_replace('_', ' ', $member->role)) : 'Anggota Divisi' }}
                                                          @if ($member->kelas)
                                                              • {{ $member->kelas }}
                                                          @endif
                                                      </p>
                                                  </div>
                                              </div>
                                          @empty
                                              <div class="col-span-2 text-center py-8 text-gray-400 text-sm">
                                                  Belum ada anggota yang terdaftar di divisi ini.
                                              </div>
                                          @endforelse
                                      </div>
                                  </div>
                              </dialog>
                          @endif
                      @endforeach
                  </div>
              </div>
          </div>
      </div>
  </section>
