<x-guest-layout>
  <div class="mx-auto max-w-5xl px-4 py-8">
    <div class="mb-6">
      <h1 class="text-xl font-semibold">Biodata Calon Mahasiswa</h1>
      <p class="text-sm text-slate-600">Lengkapi data dengan benar. Perubahan masih bisa dilakukan selama status draft.</p>
    </div>

    <div class="mb-6">
      <x-stepper :current="1" :steps="['Biodata','Dokumen','Submit']" />
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700">
        {{ session('success') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-red-700">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('calon.biodata.update') }}" class="grid grid-cols-1 gap-6 md:grid-cols-2">
      @csrf

      {{-- PROFIL --}}
      <div class="md:col-span-2">
        <h2 class="text-lg font-semibold">Profil</h2>
      </div>

      <div>
        <label class="label-sm">NIK</label>
        <input name="nik" value="{{ old('nik', $bio['nik'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Nama Lengkap</label>
        <input name="nama_lengkap" value="{{ old('nama_lengkap', $bio['nama_lengkap'] ?? auth()->user()->name) }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="input-lg">
          <option value="L" @selected(old('jenis_kelamin', $bio['jenis_kelamin'] ?? '')==='L')>Laki-laki</option>
          <option value="P" @selected(old('jenis_kelamin', $bio['jenis_kelamin'] ?? '')==='P')>Perempuan</option>
        </select>
      </div>

      <div>
        <label class="label-sm">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $bio['tanggal_lahir'] ?? '') }}" class="input-lg">
      </div>

      <div>
        <label class="label-sm">No. HP</label>
        <input name="no_hp" value="{{ old('no_hp', $bio['no_hp'] ?? '') }}" class="input-lg" />
      </div>

      {{-- ALAMAT --}}
      <div class="md:col-span-2">
        <h2 class="text-lg font-semibold">Alamat</h2>
      </div>

      <div class="md:col-span-2">
        <label class="label-sm">Alamat Jalan</label>
        <input name="alamat" value="{{ old('alamat', $bio['alamat'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Kecamatan</label>
        <input name="kecamatan" value="{{ old('kecamatan', $bio['kecamatan'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Kabupaten/Kota</label>
        <input name="kabupaten" value="{{ old('kabupaten', $bio['kabupaten'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Provinsi</label>
        <input name="provinsi" value="{{ old('provinsi', $bio['provinsi'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Kode Pos</label>
        <input name="kode_pos" value="{{ old('kode_pos', $bio['kode_pos'] ?? '') }}" class="input-lg" />
      </div>

      {{-- SEKOLAH --}}
      <div class="md:col-span-2">
        <h2 class="text-lg font-semibold">Sekolah</h2>
      </div>

      <div class="md:col-span-2">
        <label class="label-sm">Sekolah Asal</label>
        <input name="sekolah_asal" value="{{ old('sekolah_asal', $bio['sekolah_asal'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Jurusan (opsional)</label>
        <input name="jurusan_sekolah" value="{{ old('jurusan_sekolah', $bio['jurusan_sekolah'] ?? '') }}" class="input-lg" />
      </div>

      <div>
        <label class="label-sm">Tahun Lulus</label>
        <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $bio['tahun_lulus'] ?? '') }}" class="input-lg" />
      </div>

      <div class="md:col-span-2">
        <label class="label-sm">Nilai Akhir/Rata-rata (opsional)</label>
        <input type="number" step="0.01" name="nilai_akhir" value="{{ old('nilai_akhir', $bio['nilai_akhir'] ?? '') }}" class="input-lg" />
      </div>

      <div class="md:col-span-2 mt-2">
        <button class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700">Simpan Biodata</button>
        <a href="{{ route('pendaftaran.dashboard') }}" class="ml-3 rounded-xl bg-slate-200 px-4 py-2.5 font-semibold text-slate-800 hover:bg-slate-300">Kembali ke Dashboard</a>
      </div>
    </form>
  </div>
</x-guest-layout>
