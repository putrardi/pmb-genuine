<?php

namespace App\Http\Controllers\Calon;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\Gelombang;
use App\Domain\Master\Models\ProgramStudi; // <-- ini yang benar (MODEL)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendaftaranSubmittedMail;

class SubmitController extends Controller
{
    /** Halaman pilih gelombang aktif hari ini */
    public function pilihGelombang()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        if ($pendaftaran->isLockedForEdits()) {
            return redirect()->route('pendaftaran.dashboard')->with('error','Sudah dikirim.');
        }

        $today = now()->toDateString();

        $gelombang = Gelombang::where('aktif', true)
            ->where('mulai','<=',$today)
            ->where('selesai','>=',$today)
            ->orderBy('mulai','desc')
            ->get();

        $prodiAktif = ProgramStudi::where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('calon.pilih-gelombang', [
            'pendaftaran' => $pendaftaran,
            'gelombang'   => $gelombang,
            'prodiAktif'  => $prodiAktif,
        ]);
    }

    /** Simpan pilihan gelombang & prodi */
    public function simpanGelombang(Request $request)
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        if ($pendaftaran->isLockedForEdits()) {
            return redirect()->route('pendaftaran.dashboard')->with('error','Sudah dikirim.');
        }

        // tampung ke $data (sebelumnya tidak ditampung)
        $data = $request->validate([
            'gelombang_id' => ['required','exists:gelombang_pendaftaran,id'],
            'prodi_id'     => ['required','exists:program_studi,id'],
        ]);

        $today = now()->toDateString();

        // validasi gelombang benar-benar aktif hari ini
        $g = Gelombang::whereKey((int) $data['gelombang_id'])
            ->where('aktif', true)
            ->where('mulai','<=',$today)
            ->where('selesai','>=',$today)
            ->first();

        if (!$g) {
            return back()->withErrors(['gelombang_id'=>'Gelombang tidak valid/ tidak aktif hari ini.'])->withInput();
        }

        // validasi prodi aktif + cek kuota (opsional di sini, enforcement utama tetap saat verifikasi)
        $p = ProgramStudi::whereKey((int) $data['prodi_id'])
            ->where('aktif', true)
            ->first();

        if (!$p) {
            return back()->withErrors(['prodi_id'=>'Program studi harus aktif.'])->withInput();
        }

        // Cek ketersediaan kursi (UX pre-check)
        if (!$p->hasAvailableSeat()) {
            return back()->withErrors(['prodi_id' => 'Kuota prodi ini sudah penuh. Pilih prodi lain.'])->withInput();
        }

        // simpan
        $pendaftaran->gelombang_id = $g->id;
        $pendaftaran->prodi_id     = $p->id;
        $pendaftaran->save();

        return redirect()->route('pendaftaran.dashboard')->with('success','Gelombang & Prodi tersimpan.');
    }

    /** POST submit final */
    public function submitFinal()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        if ($pendaftaran->isLockedForEdits()) {
            return redirect()->route('pendaftaran.dashboard')
                ->with('error','Data sudah dikunci dan tidak dapat diubah lagi.');
        }

        // prasyarat wajib
        if (!$pendaftaran->hasBiodata() || !$pendaftaran->hasAllDocs() || !$pendaftaran->hasChosenGelombangProdi()) {
            return back()->with('error','Lengkapi biodata, unggah 3 dokumen, dan pilih gelombang & prodi terlebih dahulu.');
        }

        // set status ke SUBMITTED (baik dari draft maupun dari rejected)
        $pendaftaran->status = 'submitted';
        $pendaftaran->submitted_at = now();

        // Bersihkan jejak verifikasi sebelumnya jika resubmission
        $pendaftaran->verified_at = null;
        $pendaftaran->verified_by = null;
        $pendaftaran->verification_note = null;

        $pendaftaran->save();

        // kirim email "submitted" (boleh queue/send)
        try {
            Mail::to($pendaftaran->user->email)->queue(new PendaftaranSubmittedMail($pendaftaran));
        } catch (\Throwable $e) {
            report($e); // tidak menggagalkan flow user
        }

        return redirect()->route('calon.ringkasan')->with('success','Pendaftaran berhasil dikirim untuk verifikasi.');
    }

    // Alias agar route bisa memanggil submit() atau submitFinal()
    public function submit(Request $request)
    {
        return $this->submitFinal();
    }

    /** Halaman Ringkasan */
    public function ringkasan()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        $pendaftaran->load('gelombang','prodi');

        return view('calon.ringkasan', compact('pendaftaran'));
    }
}
