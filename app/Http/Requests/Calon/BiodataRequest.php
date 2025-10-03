<?php

namespace App\Http\Requests\Calon;

use Illuminate\Foundation\Http\FormRequest;

class BiodataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'calon_mahasiswa';
    }

    public function rules(): array
    {
        return [
            // Profil
            'nik'            => ['required','digits_between:8,20'],
            'nama_lengkap'   => ['required','string','max:150'],
            'jenis_kelamin'  => ['required','in:L,P'],
            'tanggal_lahir'  => ['required','date','before:today'],
            'no_hp'          => ['required','string','max:20'],

            // Alamat
            'alamat'         => ['required','string','max:255'],
            'kecamatan'      => ['nullable','string','max:100'],
            'kabupaten'      => ['required','string','max:100'],
            'provinsi'       => ['required','string','max:100'],
            'kode_pos'       => ['nullable','string','max:10'],

            // Sekolah
            'sekolah_asal'   => ['required','string','max:150'],
            'jurusan_sekolah'=> ['nullable','string','max:100'],
            'tahun_lulus'    => ['required','digits:4','integer','min:1990','max:'.date('Y')],
            'nilai_akhir'    => ['nullable','numeric','min:0','max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_lengkap' => $this->string('nama_lengkap')->trim()->toString(),
            'alamat'       => $this->string('alamat')->trim()->toString(),
            'kabupaten'    => $this->string('kabupaten')->trim()->toString(),
            'provinsi'     => $this->string('provinsi')->trim()->toString(),
            'sekolah_asal' => $this->string('sekolah_asal')->trim()->toString(),
        ]);
    }
}
