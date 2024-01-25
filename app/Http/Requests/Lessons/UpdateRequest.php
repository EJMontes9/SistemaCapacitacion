<?php

namespace App\Http\Requests\Lessons;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'iframe' => ['required'],
            'platform_id' => ['required', 'numeric'],
            'section_id' => ['required', 'numeric'],
            'url' => ['nullable', 'url'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
