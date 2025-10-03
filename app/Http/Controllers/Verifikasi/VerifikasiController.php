<?php

namespace App\Http\Controllers\Verifikasi;

use App\Http\Controllers\Controller;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use App\Domain\Master\Models\Gelombang;
use App\Domain\Master\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendaftaranStatusMail;

class VerifikasiController extends Controller
{
    /** List submitted + filter + search */
    public function index(Request $request)
    {
        $q    = trim((string) $request->get('q',''));
        $gid  = $request->integer('gelombang_id');
        $pid  = $request->integer('prodi_id');

        $items = Pendaftaran::query()
            ->with(['user','gelombang','prodi'])
            ->where('status','submitted')
            ->when($q !== '', function($qb) use ($q) {
                $qb->where(function($w) use ($q){
                    $w->where('no_reg','like',"%$q%")
                      ->orWhereHas('user', fn($u)=>$u->where('name','like',"%$q%")->orWhere('email','like',"%$q%"));
                });
            })
            ->when($gid, fn($qb)=>$qb->where('gelombang_id',$gid))
            ->when($pid, fn($qb)=>$qb->where('prodi_id',$pid))
            ->orderByDesc('submitted_at')
            ->paginate(12)->withQueryString();

        $gelombang = Gelombang::orderByDesc('mulai')->get(['id','nama']);
        $prodi     = ProgramStudi::orderBy('nama')->get(['id','nama','jenjang']);

        return view('staff.verifikasi.index', compact('items','q','gid','pid','gelombang','prodi'));
    }

    /** Detail ringkasan */
    public function show(Pendaftaran $pendaftaran)
    {
        abort_unless(in_array(auth()->user()->role, ['staff','admin'], true), 403);
        $pendaftaran->load(['user','gelombang','prodi']);
        return view('staff.verifikasi.show', compact('pendaftaran'));
    }

    /** Preview dokumen privat (staff/admin) */
    public function previewDoc(Pendaftaran $pendaftaran, string $key)
    {
        abort_unless(in_array(auth()->user()->role, ['staff','admin'], true), 403);

        $doc = ($pendaftaran->dokumen ?? [])[$key] ?? null;
        if (!$doc || empty($doc['path'])) abort(404);

        $disk = Storage::disk('private');
        if (!$disk->exists($doc['path'])) abort(404);

        $stream = $disk->readStream($doc['path']);
        if (!$stream) abort(404);

        return Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => $doc['mime'] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.($doc['name'] ?? basename($doc['path'])).'"',
            'Cache-Control'       => 'private, max-age=600',
        ]);
    }

    /** Terima (verified) */
    public function verify(Request $request, Pendaftaran $pendaftaran)
    {
        abort_unless(in_array(auth()->user()->role, ['staff','admin'], true), 403);
        if (!$pendaftaran->isSubmitted()) return back()->with('error','Status tidak valid.');

        $data = $request->validate([
            'note' => ['nullable','string','max:2000'],
        ]);

        $pendaftaran->status = 'verified';
        $pendaftaran->verification_note = $data['note'] ?? null;
        $pendaftaran->verified_at = now();
        $pendaftaran->verified_by = auth()->id();
        $pendaftaran->save();
        //Mail::to($pendaftaran->user->email)->queue(new PendaftaranStatusMail($pendaftaran));
        \Mail::to($pendaftaran->user->email)->send(new \App\Mail\PendaftaranStatusMail($pendaftaran));

        return redirect()->route('staff.verifikasi.show', $pendaftaran)->with('success','Pendaftar diterima (verified).');
    }

    /** Tolak (rejected) */
    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        abort_unless(in_array(auth()->user()->role, ['staff','admin'], true), 403);
        if (!$pendaftaran->isSubmitted()) return back()->with('error','Status tidak valid.');

        $data = $request->validate([
            'note' => ['required','string','max:2000'],
        ]);

        $pendaftaran->status = 'rejected';
        $pendaftaran->verification_note = $data['note'];
        $pendaftaran->verified_at = now();
        $pendaftaran->verified_by = auth()->id();
        $pendaftaran->save();
        //Mail::to($pendaftaran->user->email)->queue(new \App\Mail\PendaftaranStatusMail($pendaftaran));
        \Mail::to($pendaftaran->user->email)->send(new \App\Mail\PendaftaranStatusMail($pendaftaran));

        return redirect()->route('staff.verifikasi.show', $pendaftaran)->with('success','Pendaftar ditolak (rejected).');
    }
}
