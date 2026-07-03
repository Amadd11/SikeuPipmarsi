<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndikatorMutuUpdateRequest extends FormRequest
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
        $id = $this->route('indikator_mutu')?->id;

        return [
            'kode'            => ['required', 'string', 'max:10', Rule::unique('indikator_mutu', 'kode')->ignore($id)],
            'bidang_kerja_id' => ['required', 'exists:bidang_kerja,id'],
            'nama'            => ['required', 'string', 'max:250'],
            'target'          => ['required', 'string'],
            'periode'         => ['nullable', 'string', 'max:50'],
            'status'          => ['nullable', Rule::in(['belum', 'proses', 'tercapai', 'tidak tercapai'])],
            'catatan'         => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode'            => 'Kode',
            'bidang_kerja_id' => 'Bidang Kerja',
            'nama'            => 'Nama Indikator',
            'target'          => 'Target',
            'periode'         => 'Periode Evaluasi',
            'status'          => 'Status',
            'catatan'         => 'Catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists'   => ':attribute tidak valid.',
            'max'      => ':attribute maksimal :max karakter.',
            'unique'   => ':attribute sudah digunakan.',
            'in'       => ':attribute tidak valid.',
        ];
    }
}
