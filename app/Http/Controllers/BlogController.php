<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Traits\UploadFileTrait;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\SaveImageTrait;
use App\Services\ApiResponseService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    use ApiResponseTrait, UploadFileTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        $data = BlogResource::collection($blogs);
        return $this->customeResponse($data, 'Done!', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {


            $blog = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'city' => $request->city,
                'category' => $request->category,
                'user_id'=>$request->user_id,
                'main_image'=>uploadImage($request->main_image)
                //'main_image' => $this->UploadFile($request, 'Blog', 'main_image'),


            ]);


            if ($request->hasFile('more_images')) {
                $images= $request->file('more_images');

        foreach($images as $image){
            $originalName = $image->getClientOriginalName();

            // Check for double extensions in the image name
            if (preg_match('/\.[^.]+\./', $originalName)) {
                throw new Exception(trans('general.notAllowedAction'), 403);
            }

            $storagePath = Storage::disk('public')->put('images', $image, [
                'visibility' => Visibility::PUBLIC
            ]);
            $blog->images()->create([
                'image' => $storagePath
            ]);


        }



                }
    return response()->json([
        'message' => 'Blog Created Successfully',
        'blog' => new BlogResource($blog)
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
        $data = new BlogResource($blog);
        return $this->customeResponse($data, 'Done!', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest  $request,Blog $blog)
    {
            // //code...
            $blogData=[];

            // try {


            //     DB::beginTransaction();
                if($request->title){
                    $blogData['title']=$request->title;
                }
                if($request->content){
                    $blogData['content']=$request->content;
                }
                if($request->city){
                    $blogData['city']=$request->city;
                }
                if($request->category){
                    $blogData['category']=$request->category;
                }
                if($request->main_image){

                if ($request->hasFile('main_image')) {

                    if ($blog->main_image && Storage::exists("images".$blog->main_image)) {
                        Storage::delete(public_path("images".$blog->main_image));
                        }



                    $main_image=uploadImage($request->main_image);
                    $blogData['main_image']=$main_image;

                }
                $blog->update($blogData);


                DB::commit();


                return ApiResponseService::success([
                    'blog'=>$blog,

                ],'Blog Updated successfully',200);
                //return $this->customeResponse(new BlogResource($blogData), 'Blog Updated Successfully', 200);
            // }catch (\Throwable $th) {
            //     DB::rollBack();
            //     Log::error($th);
            //     return response()->json(['message' => 'Something Error !'], 500);
            // }

    }}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
        $blog->delete();
        return response()->json(['message' => 'Blog Deleted'], 200);
    }
}
