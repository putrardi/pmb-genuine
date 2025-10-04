<?php

public function handle($request, Closure $next)
{
    // GET aman: tetap boleh lihat
    if ($request->isMethod('GET')) return $next($request);

    $p = \App\Domain\Pendaftaran\Models\Pendaftaran::where('user_id', $request->user()->id)->first();
    if ($p && $p->isLockedForEdits()) {
        return redirect()->route('calon.ringkasan')
            ->with('error','Pendaftaran dikunci. Perubahan tidak diizinkan.');
    }
    return $next($request);
}
