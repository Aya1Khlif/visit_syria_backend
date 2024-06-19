<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','name', 'location', 'short_description', 'long_description', 'exterior_photos', 'interior_photos', 'more_images', 'services', 'price'
    ];

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
