<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'main_image',
        'content',
        'city',
        'category',
       // 'more_images',
        'user_id'
    ];


    /**
     * Get the user that owns(adds) the blog.
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
