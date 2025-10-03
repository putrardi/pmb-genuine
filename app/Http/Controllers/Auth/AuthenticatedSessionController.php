<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Breeze
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /** Tampilkan form login */
    public function create()
    {
        return view('auth.login');
    }

    /** Proses login + redirect per-role */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $role = $request->user()->role ?? null;

        if (in_array($role, ['admin', 'staff'], true)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($role === 'calon_mahasiswa') {
            return redirect()->intended(route('pendaftaran.dashboard'));
        }

        return redirect()->intended('/'); // fallback
    }

    /** Logout */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
