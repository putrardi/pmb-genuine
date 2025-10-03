<x-guest-layout>
  <div class="mx-auto max-w-5xl px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-xl font-semibold">Upload Dokumen</h1>
        <p class="text-sm text-slate-600">Unggah KTP, Ijazah, dan Pas Foto. File disimpan secara privat.</p>
      </div>
      <a href="{{ route('pendaftaran.dashboard') }}" class="rounded-lg bg-slate-200 px-3 py-1.5 text-slate-800 hover:bg-slate-300">Kembali</a>
    </div>

    <div class="mb-6">
      {{-- Stepper: di step 2 --}}
      <x-stepper :current="2" :steps="['Biodata','Dokumen','Submit']" />
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

    <form method="POST" action="{{ route('calon.dokumen.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 md:grid-cols-2">
      @csrf

      {{-- KTP --}}
      <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
        <h2 class="text-lg font-semibold">KTP</h2>
        <p class="text-xs text-slate-500 mb-3">JPG/PNG/PDF, maks 2 MB.</p>
        <input type="file" name="ktp" class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-2 file:text-white hover:file:bg-indigo-700">
        @if (!empty($docs['ktp']['path'] ?? null))
          <div class="mt-3 flex items-center justify-between text-sm">
            <a href="{{ $previews['ktp'] ?? '#' }}" target="_blank" class="text-indigo-700 hover:underline">Preview</a>
            <form method="POST" action="{{ route('calon.dokumen.destroy','ktp') }}" onsubmit="return confirm('Hapus KTP?')">
              @csrf @method('DELETE')
              <button class="text-red-700 hover:underline">Hapus</button>
            </form>
          </div>
        @endif
      </div>

      {{-- Ijazah --}}
      <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
        <h2 class="text-lg font-semibold">Ijazah</h2>
        <p class="text-xs text-slate-500 mb-3">JPG/PNG/PDF, maks 4 MB.</p>
        <input type="file" name="ijazah" class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-2 file:text-white hover:file:bg-indigo-700">
        @if (!empty($docs['ijazah']['path'] ?? null))
          <div class="mt-3 flex items-center justify-between text-sm">
            <a href="{{ $previews['ijazah'] ?? '#' }}" target="_blank" class="text-indigo-700 hover:underline">Preview</a>
            <form method="POST" action="{{ route('calon.dokumen.destroy','ijazah') }}" onsubmit="return confirm('Hapus Ijazah?')">
              @csrf @method('DELETE')
              <button class="text-red-700 hover:underline">Hapus</button>
            </form>
          </div>
        @endif
      </div>

      {{-- Pas Foto --}}
      <div class="md:col-span-2 rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
        <h2 class="text-lg font-semibold">Pas Foto</h2>
        <p class="text-xs text-slate-500 mb-3">JPG/PNG, maks 1 MB. Disarankan latar polos.</p>
        <input type="file" name="pas_foto" class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-2 file:text-white hover:file:bg-indigo-700">
        @if (!empty($docs['pas_foto']['path'] ?? null))
          <div class="mt-3 flex items-center justify-between text-sm">
            <a href="{{ $previews['pas_foto'] ?? '#' }}" target="_blank" class="text-indigo-700 hover:underline">Preview</a>
            <form method="POST" action="{{ route('calon.dokumen.destroy','pas_foto') }}" onsubmit="return confirm('Hapus Pas Foto?')">
              @csrf @method('DELETE')
              <button class="text-red-700 hover:underline">Hapus</button>
            </form>
          </div>
        @endif
      </div>

      <div class="md:col-span-2">
        <button class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700">Simpan / Unggah</button>
      </div>
    </form>

    {{-- Hint ke langkah selanjutnya (nanti) --}}
    <div class="mt-6 text-sm text-slate-500">
      Setelah semua dokumen lengkap, lanjut ke langkah <strong>Submit</strong>.
    </div>
  </div>
</x-guest-layout>
