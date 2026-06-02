<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // hanya user yang login
    }

    public function rules(): array
    {
        return [
            'category_id'  => ['required', 'integer', 'exists:categories,category_id'],
            'event_id'     => ['nullable', 'integer', 'exists:events,id'],
            'item_name'    => ['required', 'string', 'max:150'],
            'description'  => ['required', 'string', 'min:10'],
            'location'     => ['required', 'string', 'max:255'],
            'condition'    => ['required', 'in:baru,sangat baik,baik,cukup baik'],
            'image'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'  => 'Kategori barang wajib dipilih.',
            'category_id.exists'    => 'Kategori tidak ditemukan.',
            'item_name.required'    => 'Nama barang wajib diisi.',
            'item_name.max'         => 'Nama barang maksimal 150 karakter.',
            'description.required'  => 'Deskripsi barang wajib diisi.',
            'description.min'       => 'Deskripsi minimal 10 karakter.',
            'location.required'     => 'Lokasi barang wajib diisi.',
            'location.max'          => 'Lokasi maksimal 255 karakter.',
            'condition.required'    => 'Kondisi barang wajib dipilih.',
            'condition.in'          => 'Kondisi tidak valid.',
            'image.required'        => 'Foto barang wajib diupload.',
            'image.image'           => 'File harus berupa gambar.',
            'image.mimes'           => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'             => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}