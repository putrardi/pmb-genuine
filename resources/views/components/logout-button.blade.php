<form method="POST" action="{{ route('logout') }}" {{ $attributes }}>
  @csrf
  <button class="rounded-lg bg-slate-800 px-3 py-1.5 text-white hover:bg-slate-900">
    {{ $slot ?: 'Logout' }}
  </button>
</form>
