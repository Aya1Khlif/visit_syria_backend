<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = [
        'title',
        'main_image',
        'content',
        'category',
        'user_id'
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imagable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
