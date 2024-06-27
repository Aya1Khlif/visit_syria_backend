<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
        return[
            'title'       => 'nullable|string|max:255',
            'content'       => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:1',
             'main_image'       => 'nullable|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
             //'more_images'       => 'image|mimes:png,jpg,jpeg,gif,sug|max:2048',


    ];
    }
}
