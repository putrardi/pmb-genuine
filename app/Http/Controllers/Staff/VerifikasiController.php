<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    public function terima(Request $request, int $id)
    {
        try {
            DB::transaction(function () use ($request, $id, &$pendaftaran) {
                // Lock row pendaftaran
                $pendaftaran = Pendaftaran::whereKey($id)->lockForUpdate()->firstOrFail();

                if ($pendaftaran->status !== 'submitted') {
                    abort(400, 'Hanya pendaftar berstatus SUBMITTED yang bisa diverifikasi.');
                }

                // Lock row prodi
                $prodi = ProgramStudi::whereKey($pendaftaran->prodi_id)->lockForUpdate()->firstOrFail();

                // Hitung terisi di dalam transaksi
                $filled = Pendaftaran::where('prodi_id', $prodi->id)
                    ->where('status', 'verified')
                    ->lockForUpdate()
                    ->count();

                if ($filled >= (int) $prodi->kuota) {
                    // Gagal: kuota sudah penuh
                    abort(409, 'Kuota program studi sudah penuh. Tidak dapat menerima pendaftar lagi.');
                }

                // Set verified
                $pendaftaran->status = 'verified';
                $pendaftaran->verified_at = now();
                $pendaftaran->verified_by = $request->user()->id ?? null;
                $pendaftaran->verification_note = $request->input('note');
                $pendaftaran->save();
            });

            return back()->with('success', 'Pendaftar diterima.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', $e->getMessage() ?: 'Gagal memverifikasi. Kuota mungkin sudah penuh.');
        }
    }
}
