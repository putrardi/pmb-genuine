<x-guest-layout>
  <div class="mx-auto max-w-md px-4 py-10">
    <div class="mb-6 text-center">
      <h1 class="text-2xl font-bold">Login Pengguna</h1>
      <p class="text-sm text-slate-600">Admin • Staff • Calon (yang sudah punya akun)</p>
    </div>

    @if (session('status'))
      <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
        <ul class="list-disc pl-4">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
        <input type="email" name="email" autofocus class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
        <input type="password" name="password" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
      </div>
      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="remember" class="rounded">
          <span>Remember me</span>
        </label>
        @if (Route::has('password.request'))
          <a class="text-sm text-indigo-700 hover:underline" href="{{ route('password.request') }}">Lupa password?</a>
        @endif
      </div>

      <button class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white hover:bg-indigo-700">
        Login
      </button>

      <div class="mt-4 text-center text-sm">
        Belum punya akun? <a href="{{ route('landing') }}" class="text-indigo-700 hover:underline">Daftar sebagai Calon</a>
      </div>
    </form>
  </div>
</x-guest-layout>
