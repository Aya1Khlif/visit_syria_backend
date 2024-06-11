<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;



class UpdateRestaurantRequest extends FormRequest
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
            'exterior_photos' => 'nullable|string',
            'interior_photos' => 'nullable|string',
            'more_images' => 'nullable|string',
            'services' => 'nullable|json',
            'price' => 'sometimes|numeric',
        ];
    }
}
