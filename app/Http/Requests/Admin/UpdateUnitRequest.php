<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_unit' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'nama_unit')->ignore($this->route('unit')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'nama_unit.unique'   => 'Nama unit sudah digunakan.',
        ];
    }
}
