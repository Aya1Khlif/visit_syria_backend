<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landmarks extends Model
{
    protected $fillable = [
       'user_id', 'name', 'location', 'short_description', 'long_description', 'exterior_photos', 'interior_photos', 'services', 'price'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imagable');
    }
}
