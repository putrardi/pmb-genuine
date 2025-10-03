<?php

namespace App\Http\Requests\Calon;

use Illuminate\Foundation\Http\FormRequest;

class DokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'calon_mahasiswa';
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
