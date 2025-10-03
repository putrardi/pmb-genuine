@extends('layouts.admin')
@section('title', ($item->exists ? 'Edit' : 'Tambah').' Program Studi • Admin • PMB Genuine')

@section('content')
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">{{ $item->exists ? 'Edit' : 'Tambah' }} Program Studi</h1>
    <a href="{{ route('admin.prodi.index') }}" class="text-sm text-slate-600 hover:text-slate-900">← Kembali</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-red-700">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ $item->exists ? route('admin.prodi.update',$item->id) : route('admin.prodi.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="md:col-span-1">
      <label class="label-sm">Kode</label>
      <input name="kode" value="{{ old('kode',$item->kode) }}" class="input-lg" placeholder="mis. TI-S1">
    </div>

    <div class="md:col-span-1">
      <label class="label-sm">Jenjang</label>
      <select name="jenjang" class="input-lg">
        @foreach (['S1','D3','D4'] as $jj)
          <option value="{{ $jj }}" @selected(old('jenjang',$item->jenjang) === $jj)>{{ $jj }}</option>
        @endforeach
      </select>
    </div>

    <div class="md:col-span-2">
      <label class="label-sm">Nama Program Studi</label>
      <input name="nama" value="{{ old('nama',$item->nama) }}" class="input-lg" placeholder="Teknik Informatika">
    </div>

    <div class="md:col-span-1">
      <label class="label-sm">Kuota</label>
      <input type="number" name="kuota" value="{{ old('kuota',$item->kuota ?? 0) }}" class="input-lg" min="0">
    </div>

    <div class="md:col-span-1 flex items-end">
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="aktif" value="1" @checked(old('aktif',$item->aktif ?? true)) class="h-4 w-4 rounded border-slate-300 text-indigo-600">
        <span class="text-sm text-slate-700">Aktif</span>
      </label>
    </div>

    <div class="md:col-span-2 mt-2">
      <button class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700">
        {{ $item->exists ? 'Simpan Perubahan' : 'Simpan' }}
      </button>
    </div>
  </form>
@endsection
