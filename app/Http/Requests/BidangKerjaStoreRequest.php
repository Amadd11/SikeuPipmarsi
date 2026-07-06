<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BidangKerjaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode'      => ['required', 'string', 'max:5', 'unique:bidang_kerja,kode'],
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'warna_hex' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'urutan'    => ['nullable', 'integer', 'min:0', 'max:127'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode'      => 'Kode',
            'nama'      => 'Nama Bidang Kerja',
            'deskripsi' => 'Deskripsi',
            'warna_hex' => 'Warna',
            'urutan'    => 'Urutan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute melebihi batas yang diizinkan.',
            'unique'   => ':attribute sudah digunakan, pilih kode lain.',
            'integer'  => ':attribute harus berupa angka.',
            'regex'    => ':attribute harus berupa kode warna hex yang valid (contoh: #FF5733).',
        ];
    }
}
