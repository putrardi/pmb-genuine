{{-- resources/views/calon/dashboard.blade.php --}}
<x-guest-layout>
  @php
    /** @var \App\Domain\Pendaftaran\Models\Pendaftaran $pendaftaran */
    $locked    = $pendaftaran->isLockedForEdits();       // true untuk SUBMITTED/VERIFIED
    $progress  = $pendaftaran->progressPercent();        // 0..100 (REJECTED dihitung 0..67, SUBMITTED/VERIFIED=100)

    // dihitung sekali saja → dipakai ulang di seluruh halaman
    $bioDone   = $pendaftaran->hasBiodata();
    $dokDone   = $pendaftaran->hasAllDocs();
    $pilihDone = $pendaftaran->hasChosenGelombangProdi();
    $eligible  = $bioDone && $dokDone && $pilihDone;
  @endphp

  <div class="mx-auto max-w-7xl px-4 py-8">

    {{-- Header --}}
    <div class="mb-5 flex items-start justify-between gap-4">
      <div>
        <h1 class="text-xl font-semibold">Dashboard Calon</h1>
        <div class="text-sm text-slate-600">
          Nomor Registrasi:
          <span class="font-mono font-semibold">{{ $pendaftaran->no_reg ?? '—' }}</span>
        </div>
        <div class="text-sm text-slate-700">
          Nama: <span class="font-medium">{{ $pendaftaran->user?->name ?? '—' }}</span>
        </div>
        <div class="text-sm text-slate-700">
          Email: <span class="font-medium">{{ $pendaftaran->user?->email ?? '—' }}</span>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('calon.ringkasan') }}"
           class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
          Lihat Ringkasan
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-300">
            Logout
          </button>
        </form>
      </div>
    </div>

    {{-- Banner Status --}}
    @if($pendaftaran->status === 'rejected')
      <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
        <div class="font-semibold">Status: REJECTED</div>
        @if($pendaftaran->verification_note)
          <div class="mt-1">Alasan: {{ $pendaftaran->verification_note }}</div>
        @endif
        <div class="mt-1 text-sm">Silakan perbaiki data/dokumen lalu kirim ulang untuk verifikasi.</div>
      </div>
    @elseif($pendaftaran->status === 'submitted')
      <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
        <div class="font-semibold">Status: SUBMITTED</div>
        <div class="mt-1 text-sm">Menunggu verifikasi panitia.</div>
      </div>
    @elseif($pendaftaran->status === 'verified')
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
        <div class="font-semibold">Status: VERIFIED</div>
        <div class="mt-1 text-sm">Pendaftaran selesai. Terima kasih.</div>
      </div>
    @endif

    {{-- Progress --}}
    <div class="mb-6 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="mb-2 flex items-center justify-between">
        <div class="text-sm font-semibold">Progress Pendaftaran</div>
        <div class="text-sm text-slate-600">{{ $progress }}%</div>
      </div>
      <div class="h-3 w-full overflow-hidden rounded-full bg-slate-200">
        <div class="h-full rounded-full bg-indigo-600 transition-all" style="width: {{ $progress }}%"></div>
      </div>
      @if(in_array($pendaftaran->status, ['submitted','verified'], true))
        <div class="mt-2 text-xs text-emerald-700">
          Pendaftaran telah {{ strtoupper($pendaftaran->status) }}. Dari sisi calon, semua langkah selesai.
        </div>
      @elseif($pendaftaran->status === 'rejected')
        <div class="mt-2 text-xs text-red-700">
          Perbaiki data lalu kirim ulang untuk verifikasi.
        </div>
      @endif
    </div>

    {{-- Kartu Aksi / Navigasi Langkah --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      {{-- Biodata --}}
      @if($locked)
        <div class="rounded-2xl bg-white p-5 opacity-60 shadow ring-1 ring-black/5">
          <div class="text-lg font-semibold">Biodata</div>
          <div class="mt-1 text-sm text-slate-600">Terkunci ({{ strtoupper($pendaftaran->status) }})</div>
          <div class="mt-3 text-xs text-slate-500">Data tidak dapat diubah setelah SUBMITTED/VERIFIED.</div>
        </div>
      @else
        <a href="{{ route('calon.biodata.edit') }}"
           class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
          <div class="text-lg font-semibold">Biodata</div>
          <div class="mt-1 text-sm text-slate-600">{{ $bioDone ? 'Lengkap' : 'Belum lengkap' }}</div>
          <div class="mt-3 text-xs text-slate-500">Lengkapi/ubah biodata pribadi & alamat.</div>
        </a>
      @endif

      {{-- Dokumen --}}
      @if($locked)
        <div class="rounded-2xl bg-white p-5 opacity-60 shadow ring-1 ring-black/5">
          <div class="text-lg font-semibold">Dokumen</div>
          <div class="mt-1 text-sm text-slate-600">Terkunci ({{ strtoupper($pendaftaran->status) }})</div>
          <div class="mt-3 text-xs text-slate-500">Unggahan tidak dapat diubah setelah SUBMITTED/VERIFIED.</div>
        </div>
      @else
        <a href="{{ route('calon.dokumen') }}"
           class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
          <div class="text-lg font-semibold">Dokumen</div>
          <div class="mt-1 text-sm text-slate-600">{{ $dokDone ? 'Lengkap (3 dokumen)' : 'Belum lengkap (butuh 3 dokumen)' }}</div>
          <div class="mt-3 text-xs text-slate-500">Unggah KTP, Ijazah, dan Pas Foto.</div>
        </a>
      @endif

      {{-- Gelombang & Prodi (HANYA SATU KARTU) --}}
      @if($locked)
        <div class="rounded-2xl bg-white p-5 opacity-60 shadow ring-1 ring-black/5">
          <div class="text-lg font-semibold">Gelombang & Prodi</div>
          <div class="mt-1 text-sm text-slate-600">Terkunci ({{ strtoupper($pendaftaran->status) }})</div>
          <div class="mt-3 text-xs text-slate-500">Pilihan tidak dapat diubah setelah SUBMITTED/VERIFIED.</div>
        </div>
      @else
        <a href="{{ route('calon.pilih-gelombang') }}"
           class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 hover:bg-indigo-50/40">
          <div class="text-lg font-semibold">Gelombang & Prodi</div>
          <div class="mt-1 text-sm text-slate-600">
            Gelombang: {{ $pendaftaran->gelombang?->nama ?? 'Belum' }} •
            Prodi: {{ $pendaftaran->prodi?->nama ?? 'Belum' }}
          </div>
          <div class="mt-3 text-xs text-slate-500">Pilih gelombang aktif & satu program studi.</div>
        </a>
      @endif
    </div>

    {{-- Tombol Submit / Submit Ulang (tanpa duplikasi) --}}
    <div class="mt-6">
      @if($pendaftaran->status === 'rejected')
        @if($eligible)
          <form method="POST" action="{{ route('calon.submit-final') }}">
            @csrf
            <button class="rounded-xl bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-700">
              Kirim Ulang untuk Verifikasi
            </button>
            <span class="ml-2 text-sm text-slate-600">Data sudah lengkap, silakan kirim ulang.</span>
          </form>
        @else
          <button type="button"
                  class="cursor-not-allowed rounded-xl bg-emerald-600/50 px-4 py-2.5 font-semibold text-white"
                  title="Lengkapi biodata, 3 dokumen, serta gelombang & prodi">
            Kirim Ulang untuk Verifikasi
          </button>
          <span class="ml-2 text-sm text-slate-600">Lengkapi semua syarat terlebih dahulu.</span>
        @endif

      @elseif(!$locked)
        @if($eligible)
          <form method="POST" action="{{ route('calon.submit-final') }}">
            @csrf
            <button class="rounded-xl bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-700">
              Kirim untuk Verifikasi
            </button>
            <span class="ml-2 text-sm text-slate-600">Data sudah lengkap, silakan kirim.</span>
          </form>
        @else
          <button type="button"
                  class="cursor-not-allowed rounded-xl bg-emerald-600/50 px-4 py-2.5 font-semibold text-white"
                  title="Lengkapi biodata, 3 dokumen, serta gelombang & prodi">
            Kirim untuk Verifikasi
          </button>
          <span class="ml-2 text-sm text-slate-600">Syarat: Biodata, 3 dokumen, Gelombang & Prodi.</span>
        @endif
      @endif
    </div>

  </div>
</x-guest-layout>
