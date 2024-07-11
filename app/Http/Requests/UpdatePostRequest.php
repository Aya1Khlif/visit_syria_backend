<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'category' =>'nullable','string',
            'content'=>'nullable',
            'main_image'=>'nullable|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            ];

    }
}
