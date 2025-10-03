@extends('layouts.admin')
@section('title', ($item->exists ? 'Edit' : 'Tambah').' Gelombang • Admin • PMB Genuine')

@section('content')
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">{{ $item->exists ? 'Edit' : 'Tambah' }} Gelombang</h1>
    <a href="{{ route('admin.gelombang.index') }}" class="text-sm text-slate-600 hover:text-slate-900">← Kembali</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-red-700">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ $item->exists ? route('admin.gelombang.update',$item->id) : route('admin.gelombang.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="md:col-span-2">
      <label class="label-sm">Nama Gelombang</label>
      <input name="nama" value="{{ old('nama',$item->nama) }}" class="input-lg" placeholder="Gelombang 1 / Early Bird">
    </div>

    <div>
      <label class="label-sm">Mulai</label>
      <input type="date" name="mulai" value="{{ old('mulai', optional($item->mulai)->format('Y-m-d')) }}" class="input-lg">
    </div>
    <div>
      <label class="label-sm">Selesai</label>
      <input type="date" name="selesai" value="{{ old('selesai', optional($item->selesai)->format('Y-m-d')) }}" class="input-lg">
    </div>

    <div>
      <label class="label-sm">Biaya Pendaftaran (Rp)</label>
      <input type="number" name="biaya" min="0" value="{{ old('biaya',$item->biaya ?? 0) }}" class="input-lg">
    </div>

    <div class="flex items-end">
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="aktif" value="1" @checked(old('aktif',$item->aktif ?? false)) class="h-4 w-4 rounded border-slate-300 text-indigo-600">
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
