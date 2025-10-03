<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\Master\Models\Gelombang;
use App\Domain\Master\Models\ProgramStudi;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Auth\Services\AuthService; // servis registrasi calon yang kamu buat

class LandingController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $gelombangAktif = Gelombang::where('aktif', true)
            ->where('mulai','<=',$today)
            ->where('selesai','>=',$today)
            ->orderBy('mulai','desc')
            ->first();

        // Ambil prodi aktif + info kuota & terpakai (verified pada gelombang aktif)
        $prodi = ProgramStudi::where('aktif', true)->orderBy('nama')->get()->map(function ($p) use ($gelombangAktif) {
            $terpakai = 0;
            if ($gelombangAktif) {
                $terpakai = Pendaftaran::where('gelombang_id', $gelombangAktif->id)
                    ->where('prodi_id', $p->id)
                    ->where('status', 'verified')
                    ->count();
            }
            $p->terpakai = $terpakai;
            return $p;
        });

        return view('landing.index', compact('gelombangAktif','prodi'));
    }

    public function register(Request $request, AuthService $auth)
    {
        // form pendaftaran akun calon (landing)
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'email' => ['required','email','max:150','unique:users,email'],
            'password' => ['required','confirmed','min:8'],
        ]);

        // call service
        $auth->registerCalon($data['name'], $data['email'], $data['password']);

        return redirect()->route('pendaftaran.dashboard')->with('success','Akun berhasil dibuat. Silakan lanjutkan pendaftaran.');
    }
}
