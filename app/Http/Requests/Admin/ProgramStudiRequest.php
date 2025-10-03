<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramStudiRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->role === 'admin'; }

    public function rules(): array
    {
        $id = $this->route('program_studi')?->id ?? null;

        return [
            'kode'   => ['required','string','max:20', Rule::unique('program_studi','kode')->ignore($id)],
            'nama'   => ['required','string','max:150'],
            'jenjang'=> ['required','string','in:S1,D3,D4'],
            'kuota'  => ['required','integer','min:0','max:100000'],
            'aktif'  => ['nullable','boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'aktif' => (bool) $this->boolean('aktif'),
        ]);
    }
}
