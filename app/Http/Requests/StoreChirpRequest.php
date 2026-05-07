<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChirpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Zakładamy, że sprawdzanie logowania odbywa się przez middleware 'auth'
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|max:255',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ];
    }
}