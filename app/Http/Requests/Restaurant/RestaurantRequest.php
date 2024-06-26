<?php



namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'exterior_photos' => 'nullable|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            'interior_photos' => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
           // 'more_images' => 'nullable|string',
            'price' => 'required|numeric',
        ];

    }
}
