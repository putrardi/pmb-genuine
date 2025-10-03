<?php
namespace App\Http\Requests\Landing;

use Illuminate\Foundation\Http\FormRequest;

class CalonLoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'email' => ['required','email'],
            'password' => ['required','string','min:6'],
        ];
    }
}
