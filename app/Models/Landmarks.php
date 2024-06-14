<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landmarks extends Model
{
    protected $fillable = [
        'name', 'location', 'short_description', 'long_description', 'exterior_photos', 'interior_photos', 'more_images', 'services', 'price'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
