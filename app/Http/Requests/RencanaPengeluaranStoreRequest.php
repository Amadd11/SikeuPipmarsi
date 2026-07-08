<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RencanaPengeluaranStoreRequest extends FormRequest
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
            //
            'tahun_anggaran_id' => 'required|exists:tahun_anggaran,id',
            'bidang_kerja_id' => 'required|exists:bidang_kerja,id',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
            'indikator_mutu_id' => 'required|exists:indikator_mutu,id',
            'nama_kegiatan' => 'required|string|max:255',
            'details' => 'required|array|min:1',
            'details.*.uraian' => 'required|string|max:255',
            'details.*.satuan' => 'required|string|max:50',
            'details.*.harga' => 'required|numeric|min:0',
            'details.*.kuantitas' => 'required|integer|min:1',
            'jumlah_anggaran' => 'required|numeric|min:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'tahun_anggaran_id' => 'Tahun Anggaran',
            'bidang_kerja_id' => 'Bidang Kerja',
            'kategori_pengeluaran_id' => 'Kategori Pengeluaran',
            'indikator_mutu_id' => 'Indikator Mutu',
            'nama_kegiatan' => 'Nama Kegiatan',
            'details' => 'Rincian Pengeluaran',
            'details.*.uraian' => 'Uraian',
            'details.*.satuan' => 'Satuan',
            'details.*.harga' => 'Harga',
            'details.*.kuantitas' => 'Kuantitas',
            'jumlah_anggaran' => 'Jumlah Anggaran',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute tidak boleh kurang dari :min.',
        ];
    }
}
