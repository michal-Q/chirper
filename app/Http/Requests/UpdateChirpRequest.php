<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChirpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('chirp'));
    }

    public function rules(): array
    {
        return [
            'message'      => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'remove_image' => 'nullable|boolean',
        ];
    }
}