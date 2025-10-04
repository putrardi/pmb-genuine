<?php

namespace App\Http\Controllers\Calon;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;

class RingkasanController extends Controller
{
    public function show()
    {
        $pendaftaran = Pendaftaran::with(['user','gelombang','prodi'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('calon.ringkasan', compact('pendaftaran'));
    }
}
