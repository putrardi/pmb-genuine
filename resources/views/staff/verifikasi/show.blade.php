@extends('layouts.admin')
@section('title','Detail Verifikasi • Staff • PMB Genuine')

@section('content')
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">Detail Pendaftar</h1>
    <a href="{{ route('staff.verifikasi.index') }}" class="text-sm text-slate-600 hover:text-slate-900">← Kembali</a>
  </div>

  @if (session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-red-700">{{ session('error') }}</div>
  @endif

  <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
    <div class="md:col-span-2 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="mb-3 grid grid-cols-2 text-sm">
        <div><span class="text-slate-500">No Reg</span><div class="font-semibold">{{ $pendaftaran->no_reg }}</div></div>
        <div><span class="text-slate-500">Status</span><div class="font-semibold">{{ strtoupper($pendaftaran->status) }}</div></div>
      </div>
      <div class="mb-3 grid grid-cols-2 text-sm">
        <div><span class="text-slate-500">Nama</span><div class="font-semibold">{{ $pendaftaran->user?->name }}</div></div>
        <div><span class="text-slate-500">Email</span><div class="font-semibold">{{ $pendaftaran->user?->email }}</div></div>
      </div>
      <div class="mb-3 grid grid-cols-2 text-sm">
        <div><span class="text-slate-500">Gelombang</span><div class="font-semibold">{{ $pendaftaran->gelombang?->nama ?? '—' }}</div></div>
        <div><span class="text-slate-500">Prodi</span><div class="font-semibold">
          {{ $pendaftaran->prodi?->nama ? $pendaftaran->prodi->nama.' ('.$pendaftaran->prodi->jenjang.')' : '—' }}
        </div></div>
      </div>

      <hr class="my-4">
      <div>
        <div class="font-semibold mb-2">Biodata</div>
        @php $b = $pendaftaran->biodata ?? []; @endphp
        <div class="grid grid-cols-2 gap-3 text-sm">
          <div>NIK: <strong>{{ $b['nik'] ?? '—' }}</strong></div>
          <div>Nama: <strong>{{ $b['nama_lengkap'] ?? '—' }}</strong></div>
          <div>JK: <strong>{{ ($b['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : (($b['jenis_kelamin'] ?? '') === 'P' ? 'Perempuan' : '—') }}</strong></div>
          <div>Tgl Lahir: <strong>{{ isset($b['tanggal_lahir']) ? \Carbon\Carbon::parse($b['tanggal_lahir'])->format('d M Y') : '—' }}</strong></div>
          <div>No HP: <strong>{{ $b['no_hp'] ?? '—' }}</strong></div>
          <div>Alamat: <strong>{{ $b['alamat'] ?? '—' }}, {{ $b['kabupaten'] ?? '' }}, {{ $b['provinsi'] ?? '' }}</strong></div>
          <div>Sekolah: <strong>{{ $b['sekolah_asal'] ?? '—' }} ({{ $b['tahun_lulus'] ?? '—' }})</strong></div>
        </div>
      </div>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="font-semibold mb-2">Dokumen</div>
      @php $d=$pendaftaran->dokumen ?? []; @endphp
      <ul class="space-y-2 text-sm">
        <li>
          KTP:
          @if(!empty($d['ktp']['path'] ?? null))
            <a class="text-indigo-700 hover:underline" target="_blank" href="{{ route('staff.verifikasi.preview',[$pendaftaran,'ktp']) }}">Preview</a>
          @else
            —
          @endif
        </li>
        <li>
          Ijazah:
          @if(!empty($d['ijazah']['path'] ?? null))
            <a class="text-indigo-700 hover:underline" target="_blank" href="{{ route('staff.verifikasi.preview',[$pendaftaran,'ijazah']) }}">Preview</a>
          @else
            —
          @endif
        </li>
        <li>
          Pas Foto:
          @if(!empty($d['pas_foto']['path'] ?? null))
            <a class="text-indigo-700 hover:underline" target="_blank" href="{{ route('staff.verifikasi.preview',[$pendaftaran,'pas_foto']) }}">Preview</a>
          @else
            —
          @endif
        </li>
      </ul>

      @if($pendaftaran->status==='submitted')
        <hr class="my-4">
        <form method="POST" action="{{ route('staff.verifikasi.verify',$pendaftaran) }}" class="space-y-2">
          @csrf
          <textarea name="note" rows="2" class="w-full rounded-xl border border-emerald-300 p-2 text-sm" placeholder="Catatan (opsional) saat menerima"></textarea>
          <button class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-700">Terima (Verified)</button>
        </form>

        <form method="POST" action="{{ route('staff.verifikasi.reject',$pendaftaran) }}" class="mt-3 space-y-2">
          @csrf
          <textarea name="note" rows="2" class="w-full rounded-xl border border-red-300 p-2 text-sm" placeholder="Alasan penolakan (wajib)"></textarea>
          <button class="w-full rounded-xl bg-red-600 px-4 py-2.5 font-semibold text-white hover:bg-red-700">Tolak (Rejected)</button>
        </form>
      @else
        <hr class="my-4">
        <div class="rounded-lg bg-slate-50 p-3 text-sm">
          <div>Terakhir diproses: {{ optional($pendaftaran->verified_at)->format('d M Y H:i') ?? '—' }}</div>
          <div>Oleh: {{ optional($pendaftaran->verifiedBy)->name ?? '—' }}</div>
          <div>Catatan: {{ $pendaftaran->verification_note ?? '—' }}</div>
        </div>
      @endif
    </div>
  </div>
@endsection
