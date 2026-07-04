<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:180'],
            'category_id' => ['required', 'exists:categories,id'],
            'excerpt' => ['nullable', 'string', 'max:280'],
            'body' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // URL thumbnail dari pemilih Pexels (hanya diizinkan dari domain Pexels).
            'thumbnail_url' => ['nullable', 'url', 'starts_with:https://images.pexels.com/'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul artikel wajib diisi.',
            'category_id.required' => 'Pilih kategori terlebih dahulu.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'body.required' => 'Isi artikel tidak boleh kosong.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat jpg, png, atau webp.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'category_id' => 'kategori',
            'excerpt' => 'ringkasan',
            'body' => 'isi artikel',
        ];
    }
}
