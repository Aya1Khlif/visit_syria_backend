<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;


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

}