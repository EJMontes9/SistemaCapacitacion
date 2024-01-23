<?php

namespace App\Http\Requests\Sections;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:255',
            'course_id' => 'required|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
