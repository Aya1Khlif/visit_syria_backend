<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

trait SaveImageTrait
{
    public function SaveImage($images)
        {
            $Blog_images=[];

            foreach($images as $image){
                $originalName = $image->getClientOriginalName();

                // Check for double extensions in the image name
                if (preg_match('/\.[^.]+\./', $originalName)) {
                    throw new Exception(trans('general.notAllowedAction'), 403);
                }


                $storagePath = Storage::disk('public')->put('images', $originalName, [
                    'visibility' => Visibility::PUBLIC
                ]);

                return $Blog_images[]= $storagePath;


            }
            return $Blog_images;
        }

}

