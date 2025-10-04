<?php

namespace App\Http\Controllers\Calon;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calon\DokumenRequest;
use App\Domain\Pendaftaran\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class DokumenController extends Controller
{
    public function index()
    {
        $pendaftaran = Pendaftaran::with('user')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('calon.dokumen', compact('pendaftaran'));
    }

    public function edit()
    {
        $pendaftaran = \App\Domain\Pendaftaran\Models\Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        if ($pendaftaran->isLockedForEdits()) {
            return redirect()->route('pendaftaran.dashboard')->with('error','Pendaftaran sudah dikirim dan tidak dapat diubah.');
        }
        $docs = $pendaftaran->dokumen ?? [];

        $previews = [];
        foreach (['ktp','ijazah','pas_foto'] as $key) {
            if (!empty($docs[$key]['path'] ?? null)) {
                $previews[$key] = URL::temporarySignedRoute(
                    'calon.dokumen.preview',
                    now()->addMinutes(10),
                    ['key' => $key]
                );
            }
        }

        return view('calon.dokumen', compact('pendaftaran','docs','previews'));
    }

    public function update(DokumenRequest $request)
{
    $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();

    if ($pendaftaran->isLockedForEdits()) {
        return redirect()->route('pendaftaran.dashboard')
            ->with('error','Pendaftaran sudah dikirim dan tidak dapat diubah.');
    }

    $dir  = 'pendaftar/'.auth()->id();
    $docs = $pendaftaran->dokumen ?? [];

    foreach (['ktp','ijazah','pas_foto'] as $key) {
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $ext  = strtolower($file->getClientOriginalExtension());
            $name = $key.'_'.time().'.'.$ext;
            $path = \Storage::disk('private')->putFileAs($dir, $file, $name);

            $docs[$key] = [
                'path'        => $path,
                'name'        => $file->getClientOriginalName(),
                'mime'        => $file->getClientMimeType(),
                'size'        => $file->getSize(),
                'uploaded_at' => now()->toDateTimeString(),
                'exists'      => true,
            ];
        }
    }

    $pendaftaran->dokumen = $docs;
    $pendaftaran->save();

    // >>> kembali ke dashboard-calon setelah simpan
    return redirect()->route('pendaftaran.dashboard')->with('success','Dokumen berhasil diunggah.');
}

    public function preview(Request $request, string $key)
{
    // Untuk calon sendiri, cukup cek otentikasi & kepemilikan (tanpa signed)
    $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
    $doc = ($pendaftaran->dokumen ?? [])[$key] ?? null;
    if (!$doc || empty($doc['path'])) abort(404);

    $disk = \Storage::disk('private');
    if (!$disk->exists($doc['path'])) abort(404);

    $stream = $disk->readStream($doc['path']);
    if (!$stream) abort(404);

    return \Response::stream(function () use ($stream) {
        fpassthru($stream);
    }, 200, [
        'Content-Type'        => $doc['mime'] ?? 'application/octet-stream',
        'Content-Disposition' => 'inline; filename="'.($doc['name'] ?? basename($doc['path'])).'"',
        'Cache-Control'       => 'private, max-age=600',
    ]);
}

    public function destroy(string $key)
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        if ($pendaftaran->isLockedForEdits()) {
        return redirect()->route('pendaftaran.dashboard')
        ->with('error','Data sudah dikunci dan tidak dapat diubah lagi.');
        }

        $docs = $pendaftaran->dokumen ?? [];
        $doc  = $docs[$key] ?? null;

        if ($doc && !empty($doc['path'])) {
            Storage::disk('private')->delete($doc['path']);
            unset($docs[$key]);
            $pendaftaran->dokumen = $docs;
            $pendaftaran->save();
        }

        return back()->with('success','Dokumen dihapus.');
    }
}
