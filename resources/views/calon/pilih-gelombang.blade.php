<x-guest-layout>
  <div class="mx-auto max-w-4xl px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-xl font-semibold">Pilih Gelombang & Program Studi</h1>
      <a href="{{ route('pendaftaran.dashboard') }}" class="rounded-lg bg-slate-200 px-3 py-1.5 text-slate-800 hover:bg-slate-300">Kembali</a>
    </div>

    @if ($errors->any())
      <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-red-700">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif
    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('calon.simpan-gelombang') }}" class="space-y-5">
      @csrf

      {{-- PILIH GEL. AKTIF (wajib salah satu) --}}
      <div class="space-y-3">
        <div class="text-sm font-semibold">Pilih Gelombang (wajib)</div>
        @forelse ($gelombang as $g)
          <label class="flex w-full items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-indigo-300">
            <input type="radio" name="gelombang_id" value="{{ $g->id }}" class="mt-1 h-4 w-4"
                   @checked(old('gelombang_id', $pendaftaran->gelombang_id) == $g->id)>
            <div>
              <div class="font-semibold">{{ $g->nama }}</div>
              <div class="text-sm text-slate-600">
                {{ $g->mulai->format('d M Y') }} – {{ $g->selesai->format('d M Y') }} · Biaya: Rp {{ number_format($g->biaya,0,',','.') }}
              </div>
            </div>
          </label>
        @empty
          <div class="rounded-2xl border border-slate-200 bg-white p-4 text-slate-600">
            Tidak ada gelombang aktif hari ini.
          </div>
        @endforelse
      </div>

      {{-- PILIH PRODI (WAJIB 1) --}}
      <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
        <label class="label-sm mb-1 block">Program Studi (wajib)</label>
        <select name="prodi_id" class="input-lg w-full">
          <option value="">-- pilih program studi --</option>
          @foreach($prodiAktif as $p)
            <option value="{{ $p->id }}" @selected(old('prodi_id', $pendaftaran->prodi_id) == $p->id)>
              {{ $p->nama }} ({{ $p->jenjang }})
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <button class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700"
                @disabled($gelombang->isEmpty())>
          Simpan
        </button>
      </div>
    </form>
  </div>
</x-guest-layout>
