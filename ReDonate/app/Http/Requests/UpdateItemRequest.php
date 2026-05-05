<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya pemilik item yang boleh edit
        $item = $this->route('item'); // item_id dari route
        return auth()->check() && $item->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'category_id'  => ['sometimes', 'integer', 'exists:categories,category_id'],
            'event_id'     => ['nullable', 'integer', 'exists:events,id'],
            'item_name'    => ['sometimes', 'string', 'max:150'],
            'description'  => ['sometimes', 'string', 'min:10'],
            'condition'    => ['sometimes', 'in:baru,sangat baik,baik,cukup baik'],
            'status'       => ['sometimes', 'in:available,requested,completed'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists'    => 'Kategori tidak ditemukan.',
            'item_name.max'         => 'Nama barang maksimal 150 karakter.',
            'description.min'       => 'Deskripsi minimal 10 karakter.',
            'condition.in'          => 'Kondisi tidak valid.',
            'status.in'             => 'Status tidak valid.',
            'image.image'           => 'File harus berupa gambar.',
            'image.mimes'           => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'             => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}