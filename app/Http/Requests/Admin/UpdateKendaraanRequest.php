<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKendaraanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        if ($this->has('nopol_1') && $this->has('nopol_2')) {
            $suffix = $this->filled('nopol_3') ? ' ' . $this->nopol_3 : '';
            $no_polisi = strtoupper(trim($this->nopol_1 . ' ' . $this->nopol_2 . $suffix));
            
            $this->merge([
                'no_polisi' => $no_polisi
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $kendaraanId = $this->route('kendaraan')->id ?? $this->route('kendaraan');
        
        return [
            'nama_kendaraan'     => 'required|string|max:100',
            'nopol_1'            => 'required|string|alpha|max:2',
            'nopol_2'            => 'required|numeric|digits_between:1,4',
            'nopol_3'            => 'nullable|string|alpha|max:3',
            'no_polisi'          => 'required|string|max:15|unique:kendaraans,no_polisi,' . $kendaraanId,
            'tahun'              => 'required|integer|min:1900|max:' . date('Y'),
            'no_rangka'          => 'required|string|max:17|unique:kendaraans,no_rangka,' . $kendaraanId,
            'no_mesin'           => 'required|string|max:30|unique:kendaraans,no_mesin,' . $kendaraanId,
            'pajak'              => 'required|date',
            'jenis_penggunaan'   => 'required|in:jabatan,operasional',
            'lokasi_operasional' => 'nullable|string|max:100|required_if:jenis_penggunaan,operasional',
            'kategori_id'        => 'required|exists:kategoris,id',
            'warna'              => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'lokasi_operasional.required_if' => 'Lokasi Operasional wajib diisi apabila jenis penggunaan adalah operasional.',
            'no_polisi.unique' => 'Nomor Polisi ini sudah terdaftar.',
            'no_rangka.unique' => 'Nomor Rangka ini sudah terdaftar.',
            'no_mesin.unique'  => 'Nomor Mesin ini sudah terdaftar.',
        ];
    }
}
