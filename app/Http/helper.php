
<?php

//use Exception;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;


if(!function_exists('UploadImage')){

    function UploadImage($file)
    {
        $originalName = $file->getClientOriginalName();

        // Check for double extensions in the file name
        if (preg_match('/\.[^.]+\./', $originalName)) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }


        $storagePath = Storage::disk('public')->put('images', $file, [
            'visibility' => Visibility::PUBLIC
        ]);
        return $storagePath;


     }



    }

