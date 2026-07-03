<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuditMonitoringStoreRequest extends FormRequest
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
            'indikator_mutu_id'     => ['required', 'exists:indikator_mutu,id'],
            'tahun_anggaran_id'     => ['required', 'exists:tahun_anggaran,id'],
            'uraian_pelaksanaan'    => ['nullable', 'string'],
            'kendala'               => ['nullable', 'string'],
            'faktor_pendukung'      => ['nullable', 'string'],
            'perbaikan'             => ['nullable', 'string'],
            'rencana_tindak_lanjut' => ['nullable', 'string'],
            'pic'                   => ['nullable', 'string', 'max:150'],
            'tanggal_penyelesaian'  => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'indikator_mutu_id'     => 'Indikator Mutu',
            'tahun_anggaran_id'     => 'Tahun Anggaran',
            'uraian_pelaksanaan'    => 'Uraian Pelaksanaan',
            'kendala'               => 'Kendala',
            'faktor_pendukung'      => 'Faktor Pendukung',
            'perbaikan'             => 'Perbaikan',
            'rencana_tindak_lanjut' => 'Rencana Tindak Lanjut',
            'pic'                   => 'PIC (Penanggung Jawab)',
            'tanggal_penyelesaian'  => 'Tanggal Penyelesaian',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists'   => ':attribute tidak valid.',
            'max'      => ':attribute maksimal :max karakter.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
        ];
    }
}
