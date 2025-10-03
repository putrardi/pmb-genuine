<x-guest-layout>
  <div class="mx-auto max-w-7xl px-4 py-10">
    {{-- Hero --}}
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 items-center">
      <div>
        <h1 class="text-3xl font-bold leading-tight">Pendaftaran Mahasiswa Baru</h1>
        <p class="mt-2 text-slate-600">
          Selamat datang di sistem PMB. Silakan buat akun terlebih dahulu, lalu lengkapi data dan kirim untuk verifikasi.
        </p>

        {{-- Info gelombang aktif --}}
        <div class="mt-6 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
          <div class="text-sm text-slate-500">Gelombang Aktif</div>
          @if($gelombangAktif)
            <div class="mt-1 text-lg font-semibold">{{ $gelombangAktif->nama }}</div>
            <div class="text-sm text-slate-600">
              {{ $gelombangAktif->mulai->format('d M Y') }} – {{ $gelombangAktif->selesai->format('d M Y') }}
              · Biaya Pendaftaran: <strong>Rp {{ number_format($gelombangAktif->biaya,0,',','.') }}</strong>
            </div>
          @else
            <div class="mt-1 text-slate-600">Saat ini belum ada gelombang aktif.</div>
          @endif
        </div>

        {{-- Langkah-langkah pendaftaran --}}
        <div class="mt-6 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
          <div class="text-sm font-semibold mb-2">Langkah Pendaftaran</div>
          <ol class="list-decimal pl-5 text-sm text-slate-700 space-y-1">
            <li>Buat Akun</li>
            <li>Lengkapi Biodata</li>
            <li>Upload Dokumen</li>
            <li>Pilih Jurusan (Prodi) & Submit</li>
            <li>Verifikasi oleh Panitia</li>
            <li>Pengumuman</li>
          </ol>
        </div>

        {{-- Link ke halaman login pengguna (sudah punya akun) --}}
        <div class="mt-4 text-sm">
          Sudah punya akun?
          <a href="{{ route('login.user') }}" class="font-semibold text-indigo-700 hover:underline">Login di sini</a>
        </div>
      </div>

      {{-- Form daftar akun calon --}}
      <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-black/5">
        <h2 class="text-lg font-semibold">Buat Akun Calon</h2>
        <p class="text-sm text-slate-600 mb-4">Isi formulir di bawah untuk membuat akun baru.</p>

        @if ($errors->any())
          <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-4">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif
        @if (session('success'))
          <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
            {{ session('success') }}
          </div>
        @endif

        <form method="POST" action="{{ route('register.calon') }}" class="space-y-4">
          @csrf
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
          </div>
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
              <input type="password" name="password" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
          </div>

          <button class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700">
            Daftar Akun & Mulai
          </button>

          <p class="mt-2 text-xs text-slate-500">
            Dengan mendaftar Anda menyetujui kebijakan PMB.
          </p>
        </form>
      </div>
    </div>

    {{-- Tabel Prodi & Kuota --}}
    <div class="mt-10">
      <h3 class="text-lg font-semibold mb-2">Program Studi & Kuota</h3>
      <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-left text-slate-600">
            <tr>
              <th class="px-4 py-3">Program Studi</th>
              <th class="px-4 py-3">Jenjang</th>
              <th class="px-4 py-3">Kuota</th>
              <th class="px-4 py-3">Terisi</th>
              <th class="px-4 py-3">Sisa</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse ($prodi as $p)
              <tr>
                <td class="px-4 py-3">{{ $p->nama }}</td>
                <td class="px-4 py-3">{{ $p->jenjang }}</td>
                <td class="px-4 py-3">{{ $p->kuota }}</td>
                <td class="px-4 py-3">{{ $p->terpakai }}</td>
                <td class="px-4 py-3">{{ max(0, ($p->kuota ?? 0) - ($p->terpakai ?? 0)) }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada program studi aktif.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</x-guest-layout>
