<x-guest-layout>
  <div class="mx-auto max-w-5xl px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-xl font-semibold">Dashboard Calon</h1>
        <p class="text-sm text-slate-600">Selamat datang, {{ auth()->user()->name }}.</p>
      </div>
      <form method="POST" action="{{ route('logout') }}"> @csrf
        <button class="rounded-lg bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-700">Logout</button>
      </form>
    </div>

    {{-- Gelombang Aktif Hari Ini --}}
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
      <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
        <div class="text-xs text-slate-500">Gelombang Aktif</div>
        <div class="mt-1 text-lg font-semibold">
          {{ $gelombangAktif?->nama ?? '—' }}
        </div>
        <div class="text-sm text-slate-500">
          @if($gelombangAktif)
            {{ $gelombangAktif->mulai->format('d M Y') }} – {{ $gelombangAktif->selesai->format('d M Y') }}
            <div>Biaya: Rp {{ number_format($gelombangAktif->biaya,0,',','.') }}</div>
          @else
            Tidak ada gelombang aktif hari ini.
          @endif
        </div>
      </div>

      <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
        <div class="text-xs text-slate-500">No. Registrasi</div>
        <div class="mt-1 text-lg font-semibold">{{ $pendaftaran->no_reg }}</div>
        <div class="text-xs text-slate-500">Status: {{ strtoupper($pendaftaran->status) }}</div>
      </div>

      <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
        <div class="text-xs text-slate-500">Progress</div>
        @php
        $bioDone = !empty($pendaftaran->biodata['nik'] ?? null);
        $dok = $pendaftaran->dokumen ?? [];
        $dokDone = !empty($dok['ktp']['path'] ?? null) && !empty($dok['ijazah']['path'] ?? null) && !empty($dok['pas_foto']['path'] ?? null);
        $prodiChosen = $pendaftaran->hasChosenProdi();
        $subDone = $pendaftaran->isSubmitted();
        $percent = ($bioDone + $dokDone + $subDone) / 3 * 100;
        @endphp
        <div class="mt-2 h-3 w-full overflow-hidden rounded-lg bg-slate-100">
          <div class="h-3 bg-indigo-600" style="width: {{ $percent }}%"></div>
        </div>
        <div class="mt-1 text-xs text-slate-500">{{ number_format($percent) }}% selesai</div>
      </div>
    </div>

    {{-- Stepper --}}
    <div class="mb-6">
      <x-stepper :current="1" :steps="['Biodata','Dokumen','Submit']" />
    </div>

    {{-- Card Aksi --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
  <a href="{{ route('calon.biodata.edit') }}" class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
    <div class="text-lg font-semibold">Isi / Edit Biodata</div>
    <div class="text-sm text-slate-600 mt-1">Data diri, alamat, sekolah</div>
  </a>

  <a href="{{ route('calon.dokumen.edit') }}" class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
    <div class="text-lg font-semibold">Upload Dokumen</div>
    <div class="text-sm text-slate-600 mt-1">KTP, Ijazah, Pas Foto</div>
  </a>

  @if($pendaftaran->isSubmitted())
    <a href="{{ route('calon.ringkasan') }}" class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
      <div class="text-lg font-semibold">Lihat Ringkasan</div>
      <div class="text-sm text-slate-600 mt-1">Pendaftaran sudah dikirim</div>
    </a>
  @else
    <a href="{{ route('calon.pilih-gelombang') }}" class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
      <div class="text-lg font-semibold">Pilih Gelombang</div>
      <div class="text-sm text-slate-600 mt-1">
        Gelombang: {{ $pendaftaran->gelombang?->nama ?? 'Belum' }} •
        Prodi: {{ $pendaftaran->prodi?->nama ? $pendaftaran->prodi->nama.' ('.$pendaftaran->prodi->jenjang.')' : 'Belum' }}
      </div>
    </a>
  @endif
</div>

@if(!$pendaftaran->isSubmitted())
  <div class="mt-6 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
    @if (session('error'))
      <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-700">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('calon.submit') }}"
          onsubmit="return confirm('Kirim pendaftaran untuk verifikasi? Setelah dikirim tidak bisa diubah.')">
      @csrf
      <button class="rounded-xl bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-700"
        @disabled(!$bioDone || !$dokDone || !$pendaftaran->gelombang_id || !$prodiChosen)>
        Kirim untuk Verifikasi
      </button>
      <span class="ml-2 text-sm text-slate-600">
        Syarat: Biodata lengkap, 3 dokumen terunggah, Gelombang & Prodi dipilih.
      </span>
    </form>
  </div>
@endif

  </div>
</x-guest-layout>
