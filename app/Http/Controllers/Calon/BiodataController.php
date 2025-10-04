<?php

namespace App\Http\Controllers\Calon;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calon\BiodataRequest;
use App\Domain\Pendaftaran\Models\Pendaftaran;

class BiodataController extends Controller
{
    public function edit()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
            if ($pendaftaran->isLockedForEdits()) {
            return redirect()->route('pendaftaran.dashboard')
         ->with('error','Data sudah dikunci dan tidak dapat diubah lagi.');
        }

        $bio = $pendaftaran->biodata ?? [];

        return view('calon.biodata', compact('pendaftaran','bio'));
    }

    public function update(BiodataRequest $request)
    {
        $pendaftaran = \App\Domain\Pendaftaran\Models\Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        if ($pendaftaran->isLockedForEdits()) {
            return redirect()->route('pendaftaran.dashboard')->with('error','Pendaftaran sudah dikirim dan tidak dapat diubah.');
        }

        $data = $request->validated();

        // simpan ke kolom JSON 'biodata'
        $pendaftaran->biodata = array_merge($pendaftaran->biodata ?? [], $data);
        $pendaftaran->save();

        // >>> Redirect otomatis ke dashboard-calon setelah simpan <<<
        return redirect()->route('pendaftaran.dashboard')
            ->with('success', 'Biodata berhasil disimpan.');
        
    }
}
