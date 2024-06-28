<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PostResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Traits\UploadFileTrait;
use App\Services\ApiResponseService;
use Exception;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

class PostController extends Controller
{
    use ApiResponseTrait,UploadFileTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return $this->customeResponse(PostResource::collection($posts),"Done",200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        // try {
            $post = Post::create([
                'title'     => $request->title,
                'category'      => $request->category,
                'content' =>$request->content,
                'main_image' => $this->UploadFile($request, 'Post', 'main_image'),
                'user_id'=>$request->user_id,


            ]);
            $more_images = [];


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

            $more_image=$post->images()->create([
                'image' => $storagePath
            ]);
            $more_images[] = $more_image->image; // Collecting the image paths

        }


    return response()->json([
        'message' => 'Post Created Successfully',
        'post' => new PostResource($post),
        'more_images' => $more_images
    ], 201);
}
          //  return $this->customeResponse(new PostResource($post),"Done",200);
        // }catch (\Throwable $th) {
        //     Log::error($th);
        //     return $this->customeResponse(null,"Error!!,there is something not correct",500);
        // }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            return $this->customeResponse(new PostResource($post),"Done",200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->customeResponse(null,"post not found",404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        $blogData=[];

        try {


            DB::beginTransaction();


            if($request->title){
                $blogData['title']=$request->title;
            }
            if($request->content){
                $blogData['content']=$request->content;
            }

            if($request->category){
                $blogData['category']=$request->category;
            }
            if($request->main_image){
                $main_image=uploadImage($request->main_image);
                $blogData['main_image']=$main_image;

            }
            $post->update($blogData);



            DB::commit();


            return ApiResponseService::success([
                'post'=>$blogData
            ],'Post Updated successfully',200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['message' => 'Something Error !'], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return $this->customeResponse("","post deleted",200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->customeResponse(null,"Error!!,there is something not correct",500);
        }
    }
}
