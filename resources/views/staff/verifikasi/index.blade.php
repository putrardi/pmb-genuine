@extends('layouts.admin')
@section('title','Verifikasi Pendaftar • Staff • PMB Genuine')

@section('content')
  <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <h1 class="text-xl font-semibold">Verifikasi Pendaftar</h1>
    <form method="GET" action="{{ route('staff.verifikasi.index') }}" class="flex flex-wrap items-center gap-2">
      <input name="q" value="{{ $q }}" placeholder="Cari no reg/nama/email" class="input-lg w-56">

      <select name="gelombang_id" class="input-lg">
        <option value="">Semua Gelombang</option>
        @foreach($gelombang as $g)
          <option value="{{ $g->id }}" @selected($gid==$g->id)>{{ $g->nama }}</option>
        @endforeach
      </select>

      <select name="prodi_id" class="input-lg">
        <option value="">Semua Prodi</option>
        @foreach($prodi as $p)
          <option value="{{ $p->id }}" @selected($pid==$p->id)>{{ $p->nama }} ({{ $p->jenjang }})</option>
        @endforeach
      </select>

      <button class="rounded-xl bg-slate-800 px-4 py-2.5 text-white hover:bg-slate-900">Terapkan</button>
    </form>
  </div>

  <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5">
    <table class="min-w-full">
      <thead class="bg-slate-50 text-left text-sm text-slate-600">
        <tr>
          <th class="px-4 py-3">No Reg</th>
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Gelombang</th>
          <th class="px-4 py-3">Prodi</th>
          <th class="px-4 py-3">Dikirim</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-sm">
        @forelse ($items as $it)
          <tr>
            <td class="px-4 py-3 font-mono">{{ $it->no_reg }}</td>
            <td class="px-4 py-3">{{ $it->user?->name }}</td>
            <td class="px-4 py-3">{{ $it->user?->email }}</td>
            <td class="px-4 py-3">{{ $it->gelombang?->nama ?? '—' }}</td>
            <td class="px-4 py-3">{{ $it->prodi?->nama ? $it->prodi->nama.' ('.$it->prodi->jenjang.')' : '—' }}</td>
            <td class="px-4 py-3">{{ optional($it->submitted_at)->format('d M Y H:i') }}</td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('staff.verifikasi.show',$it) }}" class="rounded-lg px-3 py-1.5 text-indigo-700 hover:bg-indigo-50">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada pendaftar submitted.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $items->links() }}
  </div>
@endsection
