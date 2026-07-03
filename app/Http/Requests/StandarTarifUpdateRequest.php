<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StandarTarifUpdateRequest extends FormRequest
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
            'kode'     => ['nullable', 'string', 'max:20'],
            'nama'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            // File bersifat opsional pada update; hanya divalidasi jika diunggah
            'file'     => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode'     => 'Kode',
            'nama'     => 'Nama Standar Tarif',
            'deskripsi' => 'Deskripsi',
            'file'     => 'File PDF',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute melebihi batas yang diizinkan.',
            'mimes'    => ':attribute harus berupa file PDF.',
            'file'     => ':attribute harus berupa file yang valid.',
        ];
    }
}
