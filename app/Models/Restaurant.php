<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 'location', 'short_description', 'long_description', 'exterior_photos', 'interior_photos', 'more_images', 'services', 'price'
    ];

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
