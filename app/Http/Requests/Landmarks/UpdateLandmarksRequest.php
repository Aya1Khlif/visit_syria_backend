<?php

namespace App\Http\Requests\Landmarks;

use Illuminate\Foundation\Http\FormRequest;



class UpdateLandmarksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'short_description' => 'sometimes|string',
            'long_description' => 'nullable|string',
            'exterior_photos' => 'nullable|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            'interior_photos' => 'nullable|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
           // 'more_images' => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            'services' => 'nullable|json',
            'price' => 'sometimes|numeric',
        ];
    }
}
