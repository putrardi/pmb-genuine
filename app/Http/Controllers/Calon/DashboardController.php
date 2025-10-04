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
        $pendaftaran = Pendaftaran::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'status'   => 'draft',
                'biodata'  => [],
                'dokumen'  => [],
                // jika kamu punya generator nomor registrasi, panggil di sini:
                // 'no_reg' => app(\App\Domain\Pendaftaran\Services\NoRegService::class)->generate(),
            ]
        );

        $pendaftaran->load(['user','gelombang','prodi']); // supaya view aman

        return view('calon.dashboard', compact('pendaftaran'));
    }
}