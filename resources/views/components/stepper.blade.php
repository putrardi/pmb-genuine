@props([
  'current' => 1,
  'steps' => ['Buat Akun','Lengkapi Biodata','Upload Dokumen','Submit','Verifikasi','Pengumuman']
])
@php $n = count($steps); @endphp

<div class="mx-auto max-w-4xl">
  <ol class="flex items-center gap-2 text-sm">
    @foreach ($steps as $i => $label)
      @php $idx = $i+1; $active = $idx <= $current; @endphp
      <li class="flex items-center">
        <div class="flex items-center gap-2">
          <span class="inline-flex h-7 w-7 items-center justify-center rounded-full border
            {{ $active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-500 border-slate-300' }}">
            {{ $idx }}
          </span>
          <span class="hidden sm:inline {{ $active ? 'text-slate-800 font-medium' : 'text-slate-500' }}">{{ $label }}</span>
        </div>
        @if($idx < $n)
          <span class="mx-2 block h-px w-8 shrink-0 bg-slate-300/70 sm:w-12"></span>
        @endif
      </li>
    @endforeach
  </ol>
</div>
