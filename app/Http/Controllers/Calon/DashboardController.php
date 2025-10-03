<?php

namespace App\Http\Controllers\Calon;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\Gelombang;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Buat record pendaftaran jika belum ada
        $pendaftaran = Pendaftaran::firstOrCreate(
            ['user_id' => $user->id],
            [
                'no_reg'   => 'REG-' . Str::upper(Str::random(10)),
                'status'   => 'draft',
                'biodata'  => [],
            ]
        );

        // Gelombang aktif hari ini
        $today = now()->toDateString();
        $gelombangAktif = Gelombang::where('aktif', true)
            ->where('mulai', '<=', $today)
            ->where('selesai', '>=', $today)
            ->orderBy('mulai', 'desc')
            ->first();

        return view('calon.dashboard', compact('pendaftaran', 'gelombangAktif'));
    }
}
