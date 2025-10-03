<?php

namespace App\Http\Controllers\Calon;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendaftaranSubmittedMail;

class SubmitController extends Controller
{
    /** Halaman pilih gelombang aktif hari ini */
    public function pilihGelombang()
{
    $pendaftaran = \App\Domain\Pendaftaran\Models\Pendaftaran::where('user_id', auth()->id())->firstOrFail();
    if ($pendaftaran->isSubmitted()) {
        return redirect()->route('pendaftaran.dashboard')->with('error','Sudah dikirim.');
    }

    $today = now()->toDateString();
    $gelombang = \App\Domain\Master\Models\Gelombang::where('aktif', true)
        ->where('mulai','<=',$today)
        ->where('selesai','>=',$today)
        ->orderBy('mulai','desc')
        ->get();

    $prodiAktif = \App\Domain\Master\Models\ProgramStudi::where('aktif', true)
        ->orderBy('nama')
        ->get();

    return view('calon.pilih-gelombang', [
        'pendaftaran' => $pendaftaran,
        'gelombang'   => $gelombang,
        'prodiAktif'  => $prodiAktif,
    ]);
}


    /** Simpan pilihan gelombang */
    public function simpanGelombang(\Illuminate\Http\Request $request)
{
    $pendaftaran = \App\Domain\Pendaftaran\Models\Pendaftaran::where('user_id', auth()->id())->firstOrFail();
    if ($pendaftaran->isSubmitted()) {
        return redirect()->route('pendaftaran.dashboard')->with('error','Sudah dikirim.');
    }

    $request->validate([
        'gelombang_id' => ['required','exists:gelombang_pendaftaran,id'],
        'prodi_id'     => ['required','exists:program_studi,id'],
    ]);

    $today = now()->toDateString();

    // validasi gelombang benar-benar aktif hari ini
    $g = \App\Domain\Master\Models\Gelombang::whereKey($request->integer('gelombang_id'))
        ->where('aktif', true)
        ->where('mulai','<=',$today)
        ->where('selesai','>=',$today)
        ->first();

    if (!$g) {
        return back()->withErrors(['gelombang_id'=>'Gelombang tidak valid/ tidak aktif hari ini.'])->withInput();
    }

    // validasi prodi aktif
    $p = \App\Domain\Master\Models\ProgramStudi::whereKey($request->integer('prodi_id'))
        ->where('aktif', true)
        ->first();

    if (!$p) {
        return back()->withErrors(['prodi_id'=>'Program studi harus aktif.'])->withInput();
    }

    $pendaftaran->gelombang_id = $g->id;
    $pendaftaran->prodi_id     = $p->id;
    $pendaftaran->save();

    return redirect()->route('pendaftaran.dashboard')->with('success','Gelombang & Prodi tersimpan.');
}


    /** POST submit final */
    public function submitFinal()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();

        if ($pendaftaran->isSubmitted()) {
            return redirect()->route('calon.ringkasan')->with('info','Sudah dikirim.');
        }

        // Validasi kelengkapan
        if (!$pendaftaran->gelombang_id) {
            return back()->with('error','Silakan pilih gelombang terlebih dahulu.');
        }
        if (!$pendaftaran->hasCompleteBiodata()) {
            return back()->with('error','Biodata belum lengkap.');
        }
        if (!$pendaftaran->hasAllDocs()) {
            return back()->with('error','Dokumen belum lengkap.');
        }

        if (!$pendaftaran->hasChosenProdi()) {
        return back()->with('error','Silakan pilih program studi terlebih dahulu.');
        }


        // Kunci
        $pendaftaran->status = 'submitted';
        $pendaftaran->submitted_at = now();
        $pendaftaran->save();
        //Mail::to($pendaftaran->user->email)->queue(new PendaftaranSubmittedMail($pendaftaran));
        // kalau pakai sync: 
        \Mail::to($pendaftaran->user->email)->send(new \App\Mail\PendaftaranSubmittedMail($pendaftaran));

        // Notifikasi sederhana (flash) – queue-ready bisa ditambahkan event di sini
        return redirect()->route('calon.ringkasan')->with('success','Pendaftaran berhasil dikirim untuk verifikasi.');
    }

    /** Halaman Ringkasan */
    public function ringkasan()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        $pendaftaran->load('gelombang');

        return view('calon.ringkasan', compact('pendaftaran'));
    }
}
