<x-guest-layout>
  <div class="mx-auto max-w-4xl px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-xl font-semibold">Ringkasan Pendaftaran</h1>
        @if (session('success'))
          <div class="mt-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700">
            {{ session('success') }}
          </div>
        @endif
        @if (session('info'))
          <div class="mt-2 rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-sky-700">
            {{ session('info') }}
          </div>
        @endif
      </div>
      <a href="{{ route('pendaftaran.dashboard') }}" class="rounded-lg bg-slate-200 px-3 py-1.5 text-slate-800 hover:bg-slate-300">Dashboard</a>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
      <div class="mb-4">
        <div class="text-xs text-slate-500">No. Registrasi</div>
        <div class="text-lg font-semibold">{{ $pendaftaran->no_reg }}</div>
      </div>

      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
          <div class="font-semibold mb-1">Gelombang</div>
          <div class="text-sm text-slate-700">
            {{ $pendaftaran->gelombang?->nama ?? '—' }}
            @if($pendaftaran->gelombang)
              <div class="text-slate-500">
                {{ $pendaftaran->gelombang->mulai->format('d M Y') }} – {{ $pendaftaran->gelombang->selesai->format('d M Y') }}
                · Biaya: Rp {{ number_format($pendaftaran->gelombang->biaya,0,',','.') }}
              </div>
            @endif
          </div>
        </div>
        <div>
          <div class="font-semibold mb-1">Status</div>
          <div class="text-sm text-slate-700">{{ strtoupper($pendaftaran->status) }}</div>
          @if($pendaftaran->submitted_at)
            <div class="text-xs text-slate-500">Dikirim: {{ $pendaftaran->submitted_at->format('d M Y H:i') }}</div>
          @endif
        </div>
      </div>

      <hr class="my-5">

      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
          <div class="font-semibold mb-1">Biodata</div>
          @php $b = $pendaftaran->biodata ?? []; @endphp
          <ul class="text-sm text-slate-700 space-y-1">
            <li>NIK: {{ $b['nik'] ?? '—' }}</li>
            <li>Nama: {{ $b['nama_lengkap'] ?? '—' }}</li>
            <li>JK: {{ ($b['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : (($b['jenis_kelamin'] ?? '') === 'P' ? 'Perempuan' : '—') }}</li>
            <li>Tgl Lahir: {{ isset($b['tanggal_lahir']) ? \Carbon\Carbon::parse($b['tanggal_lahir'])->format('d M Y') : '—' }}</li>
            <li>No HP: {{ $b['no_hp'] ?? '—' }}</li>
            <li>Alamat: {{ $b['alamat'] ?? '—' }}, {{ $b['kabupaten'] ?? '' }}, {{ $b['provinsi'] ?? '' }}</li>
            <li>Sekolah: {{ $b['sekolah_asal'] ?? '—' }} ({{ $b['tahun_lulus'] ?? '—' }})</li>
          </ul>
        </div>
        <div>
        <div class="font-semibold mb-1">Program Studi</div>
        <div class="text-sm text-slate-700">
          {{ $pendaftaran->prodi?->nama ? $pendaftaran->prodi->nama.' ('.$pendaftaran->prodi->jenjang.')' : '—' }}
        </div>
        <div>
          <div class="font-semibold mb-1">Dokumen</div>
          @php $d = $pendaftaran->dokumen ?? []; @endphp
          <ul class="text-sm text-slate-700 space-y-1">
            <li>KTP: {{ empty($d['ktp']['path'] ?? null) ? '—' : '✔' }}</li>
            <li>Ijazah: {{ empty($d['ijazah']['path'] ?? null) ? '—' : '✔' }}</li>
            <li>Pas Foto: {{ empty($d['pas_foto']['path'] ?? null) ? '—' : '✔' }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
