<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
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
            'title'       => 'required|string|max:255',
            'content'       => 'required|string|max:1000',
            'city'           => 'required|string|max:20',
            'category'           => 'required|string|max:1',
            'main_image'       => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            //'more_images'       => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',

        ];
    }
}
