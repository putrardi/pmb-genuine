@extends('layouts.admin')
@section('title','Pendaftar Periode Aktif • Admin • PMB Genuine')

@section('content')
  <div class="mb-4">
    <h1 class="text-xl font-semibold">Pendaftar Periode Aktif</h1>
    <div class="text-sm text-slate-600">
      Periode: <strong>{{ $gActive?->nama ?? '—' }}</strong>
    </div>
  </div>

  <form method="GET" action="{{ route('admin.pendaftar.index') }}" class="mb-3 flex flex-wrap items-center gap-2">
    <input name="q" value="{{ $q }}" placeholder="Cari no reg/nama/email" class="input-lg w-64">
    <select name="status" class="input-lg">
      <option value="">Semua Status</option>
      @foreach(['draft'=>'Draft','submitted'=>'Submitted','verified'=>'Verified','rejected'=>'Rejected'] as $k=>$v)
        <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
      @endforeach
    </select>
    <button class="rounded-xl bg-slate-800 px-4 py-2.5 text-white hover:bg-slate-900">Terapkan</button>
  </form>

  <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5">
    <table class="min-w-full">
      <thead class="bg-slate-50 text-left text-sm text-slate-600">
        <tr>
          <th class="px-4 py-3">No Reg</th>
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Prodi</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Submitted</th>
          <th class="px-4 py-3">Verified</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-sm">
        @forelse ($items as $it)
          <tr>
            <td class="px-4 py-3 font-mono">{{ $it->no_reg }}</td>
            <td class="px-4 py-3">{{ $it->user?->name }}</td>
            <td class="px-4 py-3">{{ $it->user?->email }}</td>
            <td class="px-4 py-3">{{ $it->prodi?->nama ? $it->prodi->nama.' ('.$it->prodi->jenjang.')' : '—' }}</td>
            <td class="px-4 py-3 uppercase">{{ $it->status }}</td>
            <td class="px-4 py-3">{{ optional($it->submitted_at)->format('d M Y H:i') }}</td>
            <td class="px-4 py-3">{{ optional($it->verified_at)->format('d M Y H:i') }}</td>
            <td class="px-4 py-3 text-right">
              {{-- reuse halaman detail verifikasi --}}
              <a href="{{ route('staff.verifikasi.show',$it) }}" class="rounded-lg px-3 py-1.5 text-indigo-700 hover:bg-indigo-50">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-4 py-10 text-center text-slate-500">Belum ada data untuk periode aktif.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $items->links() }}
  </div>
@endsection
