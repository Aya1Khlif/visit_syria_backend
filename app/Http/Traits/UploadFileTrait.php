<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

trait UploadFileTrait
{

    public function UploadFile(Request $request, $folderName, $fileName)
    {
        $file = time() . '.' . $request->file($fileName)->getClientOriginalName();
        $path = $request->file($fileName)->storeAs($folderName, $file, 'public');
        return $path;
    }

        /**
     * Check if a file exists and upload it.
     *
     * This method checks if a file exists in the request and uploads it to the specified folder.
     * If the file doesn't exist, it returns null.
     *
     * @param  Request  $request The HTTP request object.
     * @param  string  $folder The folder to upload the file to.
     * @param  string  $fileColumnName The name of the file input field in the request.
     * @return string|null The file path if the file exists, otherwise null.
     */
    public function fileExists(Request $request, string $folder, string $fileColumnName)
    {
        if (empty($request->file($fileColumnName))) {
            return null;
        }
        return $this->uploadFile($request, $folder, $fileColumnName);
    }

    // public function SaveImage($images)
    // {

    //     foreach($images as $image ){
    //         $originalName = $image->getClientOriginalName();

    //         // Check for double extensions in the image name
    //         if (preg_match('/\.[^.]+\./', $originalName)) {
    //             throw new Exception(trans('general.notAllowedAction'), 403);
    //         }


    //         $storagePath = Storage::disk('public')->put('images', $originalName, [
    //             'visibility' => Visibility::PUBLIC
    //         ]);
    //         return $storagePath;}









}
