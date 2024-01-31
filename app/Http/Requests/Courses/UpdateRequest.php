<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class StoreRequest
 *
 * This class is responsible for handling the validation of the course creation request.
 * It prepares the data for validation by adding the 'slug' and 'user_id' fields.
 * It also specifies the validation rules that the request data must adhere to.
 */
class UpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * This method is called before the validation rules are applied.
     * It adds the 'slug' and 'user_id' fields to the request data.
     */
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
     * These rules are applied to the request data after it has been prepared.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:255|unique:courses,title,'.$this->route('course')->id,
            'subtitle' => 'required|min:5|max:255',
            'description' => 'required|min:5|max:255',
            'slug' => 'required|min:5|max:255',
            'level_id' => 'required|integer',
            'category_id' => 'required|integer',
            'user_id' => 'required|integer',
            'image' => 'mimes:jpg,jpeg,png|max:10240',
            //'image' => 'required',
        ];
    }
}
