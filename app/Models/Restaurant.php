<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 'location', 'short_description', 'long_description', 'exterior_photos', 'interior_photos', 'price','user_id'
    ];

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'restaurant_service');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imagable');
    }
}
