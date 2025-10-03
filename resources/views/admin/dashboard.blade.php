@extends('layouts.admin')
@section('title','Dashboard • Admin • PMB Genuine')

@section('content')
  <div class="mb-6">
    <h1 class="text-xl font-semibold">Dashboard</h1>
    <div class="text-sm text-slate-600">
      Periode aktif: <strong>{{ $gActive?->nama ?? '—' }}</strong>
      @if($gActive) ({{ $gActive->mulai->format('d M Y') }} – {{ $gActive->selesai->format('d M Y') }}) @endif
    </div>
  </div>

  {{-- KPI Cards --}}
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
      <div class="text-xs text-slate-500">Total Pendaftar</div>
      <div class="mt-1 text-2xl font-semibold">{{ $total }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
      <div class="text-xs text-slate-500">Submitted</div>
      <div class="mt-1 text-2xl font-semibold">{{ $submitted }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
      <div class="text-xs text-slate-500">Verified (Diterima)</div>
      <div class="mt-1 text-2xl font-semibold text-emerald-700">{{ $verified }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow ring-1 ring-black/5 p-4">
      <div class="text-xs text-slate-500">Rejected (Ditolak)</div>
      <div class="mt-1 text-2xl font-semibold text-red-700">{{ $rejected }}</div>
    </div>
  </div>

  {{-- Charts --}}
  <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
      <div class="mb-2 text-sm font-semibold">By Gender</div>
      <canvas id="chartGender" height="220"></canvas>
    </div>
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5 xl:col-span-2">
      <div class="mb-2 text-sm font-semibold">By Program Studi</div>
      <canvas id="chartProdi" height="220"></canvas>
    </div>
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5 xl:col-span-3">
      <div class="mb-2 text-sm font-semibold">Submit per Hari (14 hari)</div>
      <canvas id="chartSubmit" height="70"></canvas>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Data dari controller
  const genderLabels = @json($genderLabels);
  const genderData   = @json($genderData);
  const prodiLabels  = @json($prodiLabels);
  const prodiData    = @json($prodiData);
  const dateLabels   = @json($dateLabels);
  const submitData   = @json($countSubmit);

  // Pie Gender
  new Chart(document.getElementById('chartGender'), {
    type: 'pie',
    data: { labels: genderLabels, datasets: [{ data: genderData }] },
    options: { plugins:{ legend:{ position:'bottom' } } }
  });

  // Bar Prodi
  new Chart(document.getElementById('chartProdi'), {
    type: 'bar',
    data: { labels: prodiLabels, datasets: [{ data: prodiData }] },
    options: {
      scales: { y: { beginAtZero: true, precision:0 } },
      plugins:{ legend:{ display:false } }
    }
  });

  // Line Submit per Hari
  new Chart(document.getElementById('chartSubmit'), {
    type: 'line',
    data: { labels: dateLabels, datasets: [{ data: submitData, tension: .3, fill:false }] },
    options: {
      scales: { y: { beginAtZero:true, precision:0 } },
      plugins:{ legend:{ display:false } }
    }
  });
</script>
@endpush
