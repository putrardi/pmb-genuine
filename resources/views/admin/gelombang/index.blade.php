@extends('layouts.admin')
@section('title','Gelombang Pendaftaran • Admin • PMB Genuine')

@section('content')
  <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <h1 class="text-xl font-semibold">Gelombang Pendaftaran</h1>
    <div class="flex gap-2">
      <form method="GET" action="{{ route('admin.gelombang.index') }}" class="flex items-center gap-2">
        <input name="q" value="{{ $q }}" placeholder="Cari nama gelombang" class="input-lg w-64">
        <button class="rounded-xl bg-slate-800 px-4 py-2.5 text-white hover:bg-slate-900">Cari</button>
      </form>
      <a href="{{ route('admin.gelombang.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700">
        + Tambah
      </a>
    </div>
  </div>

  @if (session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5">
    <table class="min-w-full">
      <thead class="bg-slate-50 text-left text-sm text-slate-600">
        <tr>
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Periode</th>
          <th class="px-4 py-3">Biaya</th>
          <th class="px-4 py-3">Aktif</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-sm">
        @forelse ($items as $it)
          <tr>
            <td class="px-4 py-3">{{ $it->nama }}</td>
            <td class="px-4 py-3">
              {{ $it->mulai?->format('d M Y') }} – {{ $it->selesai?->format('d M Y') }}
            </td>
            <td class="px-4 py-3">Rp {{ number_format($it->biaya,0,',','.') }}</td>
            <td class="px-4 py-3">
              @if($it->aktif)
                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs text-emerald-700">Aktif</span>
              @else
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
              @endif
            </td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('admin.gelombang.edit',$it->id) }}" class="rounded-lg px-3 py-1.5 text-indigo-700 hover:bg-indigo-50">Edit</a>
              <form method="POST" action="{{ route('admin.gelombang.destroy',$it->id) }}" class="inline" onsubmit="return confirm('Hapus {{ $it->nama }}?')">
                @csrf @method('DELETE')
                <button class="rounded-lg px-3 py-1.5 text-red-700 hover:bg-red-50">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $items->links() }}
  </div>
@endsection
