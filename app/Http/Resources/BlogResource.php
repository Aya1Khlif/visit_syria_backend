<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'content'       => $this->content,
            'long_description' => $this->long_description,
            'main_image'       => asset(Storage::url($this->main_image)),
            'more_images'       => asset(Storage::url($this->more_images)),
        ];
    }
}