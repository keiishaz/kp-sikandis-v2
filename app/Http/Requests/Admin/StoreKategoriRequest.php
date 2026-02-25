<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => ['required', 'string', 'max:150', 'unique:kategoris,nama_kategori'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama Kategori wajib diisi.',
            'nama_kategori.string'   => 'Format Nama Kategori tidak valid.',
            'nama_kategori.max'      => 'Nama Kategori maksimal 150 karakter.',
            'nama_kategori.unique'   => 'Nama Kategori ini sudah terdaftar. Silakan gunakan nama lain.',
        ];
    }
}
