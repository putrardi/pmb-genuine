<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Admin • PMB Genuine')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
  <!-- ADMIN LAYOUT ACTIVE -->
  <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
      <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold">PMB</div>
        <div class="-mt-0.5">
          <div class="text-sm font-semibold">PMB Genuine</div>
          <div class="text-xs text-slate-500">Admin Panel</div>
        </div>
      </div>
      <div class="text-sm text-slate-600">
        @auth
          <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
            <button class="rounded-lg bg-slate-800 px-3 py-1.5 font-medium text-white hover:bg-slate-900">
              Logout ({{ auth()->user()->name }})
            </button>
          </form>
        @endauth
      </div>
    </div>
  </header>

  <div class="mx-auto grid max-w-7xl grid-cols-12 gap-6 px-4 py-6">
    {{-- Sidebar --}}
    <aside class="col-span-12 md:col-span-3 lg:col-span-2">
      @php $role = auth()->user()->role; @endphp
  <nav class="space-y-1">
    {{-- Dashboard untuk admin & staff --}}
    @if(in_array($role, ['admin','staff']))
      <a href="{{ route('admin.dashboard') }}" class="sidebar-item">Dashboard</a>
    @endif

    {{-- Master Data hanya admin --}}
    @if($role === 'admin')
      <a href="{{ route('admin.prodi.index') }}" class="sidebar-item">Program Studi</a>
      <a href="{{ route('admin.gelombang.index') }}" class="sidebar-item">Gelombang</a>
    @endif

    {{-- Panel Verifikasi bisa admin & staff (pakai yang sudah ada) --}}
    @if(in_array($role, ['admin','staff']))
      <a href="{{ route('staff.verifikasi.index') }}" class="sidebar-item">Verifikasi</a>
      <a href="{{ route('admin.pendaftar.index') }}" class="sidebar-item">Pendaftar</a>
    @endif
  </nav>

  <style>
    .sidebar-item{display:block;padding:.5rem .75rem;border-radius:.5rem}
    .sidebar-item:hover{background:#eef2ff}
  </style>

    </aside>

    {{-- Konten --}}
    <main class="col-span-12 md:col-span-9 lg:col-span-10">
      @yield('content')
    </main>
  </div>

  <footer class="pb-8 text-center text-xs text-slate-400">© {{ date('Y') }} PMB Genuine</footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts') {{-- untuk halaman yang butuh script tambahan --}}

</body>
</html>
