<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unitId = $this->route('unit')->id;

        return [
            'nama_sub_unit' => [
                'required',
                'string',
                'max:150',
                Rule::unique('sub_units', 'nama_sub_unit')->where('unit_id', $unitId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sub_unit.required' => 'Nama sub unit wajib diisi.',
            'nama_sub_unit.unique'   => 'Nama sub unit sudah ada dalam unit ini.',
        ];
    }
}
