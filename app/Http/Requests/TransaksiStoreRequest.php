<?php

namespace App\Http\Requests;

use App\Models\RencanaPendapatan;
use App\Models\RencanaPengeluaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransaksiStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_transaksi'    => 'required|string|max:30|unique:transaksi,kode_transaksi',
            'tahun_anggaran_id' => 'required|exists:tahun_anggaran,id',
            'tanggal'           => 'required|date',
            'jenis'             => 'required|in:pemasukan,pengeluaran',
            'uraian'            => 'required|string|max:300',
            'bidang_kerja_id'   => 'nullable|exists:bidang_kerja,id',
            'transaksable_type' => ['required', Rule::in([
                RencanaPendapatan::class,
                RencanaPengeluaran::class,
            ])],
            'transaksable_id'   => 'required|integer',
            'jumlah'            => 'required|numeric|min:0',
            'nomor_bukti'       => 'nullable|string|max:50',
            'file_bukti'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_transaksi'    => 'Kode Transaksi',
            'tahun_anggaran_id' => 'Tahun Anggaran',
            'tanggal'           => 'Tanggal Transaksi',
            'jenis'             => 'Jenis Transaksi',
            'uraian'            => 'Uraian Transaksi',
            'bidang_kerja_id'   => 'Bidang Kerja',
            'transaksable_type' => 'Tipe Referensi',
            'transaksable_id'   => 'Referensi Anggaran',
            'jumlah'            => 'Jumlah Nominal',
            'nomor_bukti'       => 'Nomor Bukti',
            'file_bukti'        => 'File Bukti Lampiran',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists'   => ':attribute yang dipilih tidak valid atau tidak ditemukan.',
            'date'     => ':attribute harus berupa format tanggal yang valid.',
            'in'       => 'Pilihan :attribute tidak valid.',
            'string'   => ':attribute harus berupa teks.',
            'integer'  => ':attribute harus berupa angka bulat.',
            'numeric'  => ':attribute harus berupa angka.',
            'min'      => ':attribute tidak boleh kurang dari :min.',
            'max'      => [
                'string' => ':attribute maksimal :max karakter.',
                'file'   => 'Ukuran :attribute maksimal :max KB.',
            ],
            'file'     => ':attribute harus berupa sebuah file.',
            'mimes'    => ':attribute hanya mendukung format: :values.',
        ];
    }
}
