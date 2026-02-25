<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pegawaiId = $this->route('pegawai')->id;

        return [
            'nama'        => ['required', 'string', 'max:150'],
            'nip'         => [
                'required',
                'numeric',
                'digits:18',
                Rule::unique('pegawais', 'nip')->ignore($pegawaiId),
            ],
            'jabatan'     => ['required', 'string', 'max:100'],
            'unit_id'     => ['required', 'exists:units,id'],
            'sub_unit_id' => ['required', 'exists:sub_units,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'        => 'Nama wajib diisi.',
            'nip.required'         => 'NIP wajib diisi.',
            'nip.numeric'          => 'NIP harus berupa angka (tanpa spasi atau tanda baca).',
            'nip.digits'           => 'NIP harus terdiri dari tepat 18 digit.',
            'nip.unique'           => 'NIP ini sudah terdaftar dalam sistem.',
            'jabatan.required'     => 'Jabatan wajib diisi.',
            'unit_id.required'     => 'Unit wajib dipilih.',
            'unit_id.exists'       => 'Unit yang dipilih tidak valid.',
            'sub_unit_id.required' => 'Sub unit wajib dipilih.',
            'sub_unit_id.exists'   => 'Sub unit yang dipilih tidak valid.',
        ];
    }
}
