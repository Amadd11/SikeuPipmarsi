<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RencanaPendapatanUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategori_pendapatan_id' => 'required|exists:kategori_pendapatan,id',
            'nama_sumber' => 'required|string|max:200',
            'keterangan' => 'nullable|string',
            'jumlah_rencana' => 'required|numeric|min:0',
            'details' => 'required|array|min:1',
            'details.*.uraian' => 'required|string|max:255',
            'details.*.satuan' => 'required|string|max:50',
            'details.*.jumlah' => 'required|numeric|min:0',
            'details.*.kuantitas' => 'required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_pendapatan_id' => 'Kategori Pendapatan',
            'nama_sumber' => 'Nama Sumber Pendapatan',
            'keterangan' => 'Keterangan',
            'jumlah_rencana' => 'Jumlah Rencana',
            'details.*.uraian' => 'Uraian',
            'details.*.satuan' => 'Satuan',
            'details.*.jumlah' => 'Jumlah',
            'details.*.kuantitas' => 'Kuantitas',
        ];
    }

    /**
     * Custom error messages (optional)
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'in' => ':attribute tidak valid.',
        ];
    }
}
