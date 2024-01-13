<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'user_id' => Auth::id(),
        ]);
    }

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:255|unique:courses',
            'subtitle' => 'required|min:5|max:255',
            'description' => 'required|min:5|max:255',
            'slug' => 'required|min:5|max:255',
            'level_id' => 'required|integer',
            'category_id' => 'required|integer',
            'user_id' => 'required|integer',
            //'image' => 'required|image',
        ];
    }
}
