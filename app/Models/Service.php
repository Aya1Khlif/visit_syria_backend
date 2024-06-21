<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description'];

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_service');
    }
}
