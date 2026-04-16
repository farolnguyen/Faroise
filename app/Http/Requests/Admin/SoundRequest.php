<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SoundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'icon_file'   => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'color'       => ['required', 'string', 'max:7'],
            'audio_file'  => ['nullable', 'file', 'mimes:mp3,ogg,wav,aac', 'max:20480'],
            'audio_url'   => ['nullable', 'url', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'sort_order'  => ['integer', 'min:0'],
            'loop_start'  => ['numeric', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }
}
