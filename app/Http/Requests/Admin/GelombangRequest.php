<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Domain\Master\Models\Gelombang;

class GelombangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama'   => ['required','string','max:120'],
            'mulai'  => ['required','date'],
            'selesai'=> ['required','date','after_or_equal:mulai'],
            'biaya'  => ['required','integer','min:0','max:100000000'],
            'aktif'  => ['nullable','boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'aktif' => (bool) $this->boolean('aktif'),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $id = $this->route('gelombang')?->id   // jika pakai parameter 'gelombang'
               ?? $this->route('gelombang_pendaftaran')?->id
               ?? $this->route('gelombang')        // fallback jika binding manual
               ?? $this->route('id');

            $mulai   = (string) $this->input('mulai');
            $selesai = (string) $this->input('selesai');
            $aktif   = (bool) $this->input('aktif', false);

            if ($aktif) {
                // Kebijakan 1: tidak boleh overlap dengan gelombang aktif lain
                if (Gelombang::existsActiveOverlap($id, $mulai, $selesai)) {
                    $v->errors()->add('aktif', 'Periode aktif bertabrakan dengan gelombang aktif lain.');
                }
                // Kebijakan 2: hanya satu gelombang aktif pada saat bersamaan
                // (Sebetulnya kebijakan 1 sudah cukup, tapi tambahkan ini untuk jaga-jaga
                // jika ingin hanya satu aktif kapan pun)
                // if (Gelombang::existsAnotherActive($id)) {
                //     $v->errors()->add('aktif', 'Sudah ada gelombang aktif lain.');
                // }
            }
        });
    }
}
