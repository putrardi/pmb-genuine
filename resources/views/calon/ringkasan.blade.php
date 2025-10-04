{{-- resources/views/calon/ringkasan.blade.php --}}
<x-guest-layout>
  @php
    /** @var \App\Domain\Pendaftaran\Models\Pendaftaran $pendaftaran */
    $bio = $pendaftaran->biodata ?? [];
    $dok = $pendaftaran->dokumen ?? [];

    $submittedAt = $pendaftaran->submitted_at
      ? ($pendaftaran->submitted_at instanceof \Carbon\Carbon
          ? $pendaftaran->submitted_at
          : \Illuminate\Support\Carbon::parse($pendaftaran->submitted_at))
      : null;

    $verifiedAt = $pendaftaran->verified_at
      ? ($pendaftaran->verified_at instanceof \Carbon\Carbon
          ? $pendaftaran->verified_at
          : \Illuminate\Support\Carbon::parse($pendaftaran->verified_at))
      : null;
  @endphp

  <div class="mx-auto max-w-5xl px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-xl font-semibold">Ringkasan Pendaftaran</h1>
        <div class="text-sm text-slate-600">
          Nomor Registrasi:
          <span class="font-mono font-semibold">{{ $pendaftaran->no_reg ?? '—' }}</span>
        </div>
        <div class="text-xs text-slate-500 mt-1">
          @if($submittedAt)
            Dikirim: {{ $submittedAt->format('d M Y H:i') }}
          @endif
          @if($verifiedAt)
            • Diverifikasi: {{ $verifiedAt->format('d M Y H:i') }}
          @endif
        </div>
      </div>

      <a href="{{ route('pendaftaran.dashboard') }}"
         class="rounded-lg bg-slate-200 px-3 py-1.5 text-slate-800 hover:bg-slate-300">Kembali</a>
    </div>

    {{-- Banner Status --}}
    @if($pendaftaran->status === 'rejected')
      <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
        <div class="font-semibold">Status: REJECTED</div>
        @if($pendaftaran->verification_note)
          <div class="mt-1">Alasan: {{ $pendaftaran->verification_note }}</div>
        @endif
      </div>
    @elseif($pendaftaran->status === 'submitted')
      <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
        <div class="font-semibold">Status: SUBMITTED</div>
        <div class="mt-1 text-sm">Menunggu verifikasi panitia.</div>
      </div>
    @elseif($pendaftaran->status === 'verified')
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
        <div class="font-semibold">Status: VERIFIED</div>
      </div>
    @endif

    {{-- Biodata --}}
    <div class="mb-4 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="mb-2 text-sm font-semibold">Biodata</div>
      <dl class="grid grid-cols-1 gap-3 md:grid-cols-2">
        <div>
          <dt class="text-xs text-slate-500">Nama Lengkap</dt>
          <dd>{{ $bio['nama_lengkap'] ?? $pendaftaran->user?->name ?? '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs text-slate-500">NIK</dt>
          <dd>{{ $bio['nik'] ?? '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs text-slate-500">Jenis Kelamin</dt>
          <dd>
            @php $jk = $bio['jenis_kelamin'] ?? null; @endphp
            {{ $jk === 'L' ? 'Laki-laki' : ($jk === 'P' ? 'Perempuan' : '—') }}
          </dd>
        </div>
        <div>
          <dt class="text-xs text-slate-500">Asal Sekolah</dt>
          <dd>{{ $bio['asal_sekolah'] ?? '—' }}</dd>
        </div>
        <div class="md:col-span-2">
          <dt class="text-xs text-slate-500">Alamat</dt>
          <dd>{{ $bio['alamat'] ?? '—' }}</dd>
        </div>
      </dl>
    </div>

    {{-- Gelombang & Prodi --}}
    <div class="mb-4 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="mb-2 text-sm font-semibold">Gelombang & Program Studi</div>
      <dl class="grid grid-cols-1 gap-3 md:grid-cols-2">
        <div>
          <dt class="text-xs text-slate-500">Gelombang</dt>
          <dd>{{ $pendaftaran->gelombang?->nama ?? '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs text-slate-500">Periode</dt>
          <dd>
            @if($pendaftaran->gelombang)
              {{ $pendaftaran->gelombang->mulai->format('d M Y') }} – {{ $pendaftaran->gelombang->selesai->format('d M Y') }}
            @else — @endif
          </dd>
        </div>
        <div>
          <dt class="text-xs text-slate-500">Program Studi</dt>
          <dd>{{ $pendaftaran->prodi?->nama ?? '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs text-slate-500">Jenjang</dt>
          <dd>{{ strtoupper($pendaftaran->prodi?->jenjang ?? '—') }}</dd>
        </div>
      </dl>
    </div>

    {{-- Dokumen --}}
    <div class="mb-4 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="mb-2 text-sm font-semibold">Dokumen</div>
      <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
        <div>
          <div class="text-xs text-slate-500">KTP</div>
          @if(!empty($dok['ktp']['exists'] ?? false))
            <a href="{{ route('calon.dokumen.preview', 'ktp') }}" target="_blank" class="text-indigo-700 hover:underline">Lihat</a>
          @else <span>—</span> @endif
        </div>
        <div>
          <div class="text-xs text-slate-500">Ijazah</div>
          @if(!empty($dok['ijazah']['exists'] ?? false))
            <a href="{{ route('calon.dokumen.preview', 'ijazah') }}" target="_blank" class="text-indigo-700 hover:underline">Lihat</a>
          @else <span>—</span> @endif
        </div>
        <div>
          <div class="text-xs text-slate-500">Pas Foto</div>
          @if(!empty($dok['pas_foto']['exists'] ?? false))
            <a href="{{ route('calon.dokumen.preview', 'pas_foto') }}" target="_blank" class="text-indigo-700 hover:underline">Lihat</a>
          @else <span>—</span> @endif
        </div>
      </div>
    </div>

    {{-- Catatan / Footer --}}
    <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 text-sm text-slate-600">
      Simpan halaman ini sebagai arsip. Jika status <strong>REJECTED</strong>, Anda dapat memperbaiki data dan melakukan kirim ulang.
    </div>
  </div>
</x-guest-layout>
