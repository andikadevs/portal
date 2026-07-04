<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Masukkan format email yang benar, misalnya nama@contoh.com.',
            'body.required' => 'Komentar tidak boleh kosong.',
            'body.min' => 'Komentar terlalu pendek, tulis minimal 3 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'body' => 'komentar',
        ];
    }
}
