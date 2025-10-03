<x-guest-layout>
  <div class="mx-auto max-w-xl px-4 py-8">
    <h1 class="mb-4 text-xl font-semibold">Profil</h1>

    @if (session('status') === 'profile-updated')
      <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700">
        Profil diperbarui.
      </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-3">
      @csrf @method('patch')
      <div>
        <label class="label-sm">Nama</label>
        <input name="name" value="{{ old('name', $user->name) }}" class="input-lg">
        @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
      </div>
      <div>
        <label class="label-sm">Email</label>
        <input name="email" type="email" value="{{ old('email', $user->email) }}" class="input-lg">
        @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
      </div>
      <button class="rounded-xl bg-indigo-600 px-4 py-2.5 font-semibold text-white">Simpan</button>
    </form>

    <hr class="my-6">

    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Hapus akun?')">
      @csrf @method('delete')
      <div class="mb-2">
        <label class="label-sm">Password Saat Ini</label>
        <input name="password" type="password" class="input-lg">
        @error('password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
      </div>
      <button class="rounded-xl bg-red-600 px-4 py-2.5 font-semibold text-white">Hapus Akun</button>
    </form>
  </div>
</x-guest-layout>
