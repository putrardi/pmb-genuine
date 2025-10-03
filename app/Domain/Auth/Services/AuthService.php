<?php

namespace App\Domain\Auth\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerCalon(string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email'=> $email,
            'password' => Hash::make($password),
            'role' => 'calon_mahasiswa',
        ]);

        Pendaftaran::create([
            'user_id' => $user->id,
            'no_reg'  => 'REG-'.Str::upper(Str::random(10)),
            'status'  => 'draft',
            'biodata' => [],
        ]);

        return $user;
    }

    public function loginAdminStaff(string $email, string $password): bool
    {
        if (!Auth::attempt(['email'=>$email,'password'=>$password])) {
            return false;
        }
        $user = Auth::user();
        if (!in_array($user->role, ['admin','staff'])) {
            Auth::logout();
            return false;
        }
        session()->regenerate();
        return true;
    }

    public function loginCalon(string $email, string $password): bool
{
    if (!\Auth::attempt(['email'=>$email,'password'=>$password])) {
        return false;
    }
    $user = \Auth::user();
    if ($user->role !== 'calon_mahasiswa') {
        \Auth::logout();
        return false;
    }
    session()->regenerate();
    return true;
}

}
