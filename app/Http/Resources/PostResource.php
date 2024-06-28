<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'   => $this->title,
            'category'    => $this->category,
            'content' =>$this->content,
            'main_image'=>asset(Storage::url($this->main_image))

        ];
    }
}
