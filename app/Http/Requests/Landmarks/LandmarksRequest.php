<?php



namespace App\Http\Requests\Landmarks;

use Illuminate\Foundation\Http\FormRequest;

class LandmarksRequest extends FormRequest
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
            'exterior_photos' => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            'interior_photos' => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
           // 'more_images' => 'required|image|mimes:png,jpg,jpeg,gif,sug|max:2048',
            'services' => 'nullable|json',
            'price' => 'required|numeric',
        ];

    }
}
