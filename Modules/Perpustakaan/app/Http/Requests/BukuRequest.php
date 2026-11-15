<?php

namespace Modules\Perpustakaan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hak akses (admin/petugas) sudah diperiksa oleh middleware `role` pada route.
        return true;
    }

    public function rules(): array
    {
        // Saat update, {buku} berisi model yang sedang diedit -> dikecualikan dari cek unik.
        $buku = $this->route('buku');

        return [
            'kode_buku' => [
                'required', 'string', 'max:20',
                Rule::unique('buku', 'kode_buku')->ignore($buku?->id),
            ],
            'judul' => ['required', 'string', 'max:150'],
            'penulis' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_buku' => 'Kode buku',
            'judul' => 'Judul',
            'penulis' => 'Penulis',
            'kategori' => 'Kategori',
            'stok' => 'Stok',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah digunakan, gunakan kode lain.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'stok.min' => 'Stok tidak boleh negatif.',
        ];
    }
}
