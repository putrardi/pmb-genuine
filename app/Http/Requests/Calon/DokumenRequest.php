<?php

namespace App\Http\Requests\Calon;

use Illuminate\Foundation\Http\FormRequest;

class DokumenRequest extends FormRequest
{
    public function authorize(): bool
{
    $p = \App\Domain\Pendaftaran\Models\Pendaftaran::where('user_id', $this->user()->id)->first();
    return $this->user()->role === 'calon_mahasiswa' && $p && !$p->isLockedForEdits();
}


    public function rules(): array
    {
        // tiap field opsional (boleh upload satu-satu), tapi bila ada -> validasi
        return [
            'ktp'      => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:2048'],  // 2 MB
            'ijazah'   => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:4096'],  // 4 MB
            'pas_foto' => ['nullable','image','mimes:jpg,jpeg,png','max:1024'],     // 1 MB, khusus image
        ];
    }
}
