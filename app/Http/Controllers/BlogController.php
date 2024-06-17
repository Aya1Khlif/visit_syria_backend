<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Traits\UploadFileTrait;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



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
        try {
            DB::beginTransaction();
            $blog = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'city' => $request->city,
                'category' => $request->category,
                'more_images' => $request->more_images,
                 //   'main_image' => $this->UploadFile($request, 'Blog', 'main_image'),
                 //for test in postman
                'main_image' => $request->more_images,
            
            ]);
            DB::commit();
            return $this->customeResponse(new BlogResource($blog), 'Blog Created Successfully', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->customeResponse(null, 'Failed To Create Blog', 500);
        }
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
    public function update(UpdateBlogRequest  $request, Blog $blog)
    {
            //code...
            try {
                DB::beginTransaction();
    
                $blog->title       = $request->input('title') ?? $blog->title;
                $blog->content = $request->input('content') ?? $blog->content;
                $blog->city = $request->input('city') ?? $blog->city;
                $blog->category = $request->input('category') ?? $blog->category;
                // //for test in postman
                $blog->main_image   = $request->input('main_image') ?? $blog->main_image;
                $blog->more_images   = $request->input('more_images') ?? $blog->more_images;
    
                $blog->save();
    
                DB::commit();
    
                return $this->customeResponse(new BlogResource($blog), 'Blog Updated Successfully', 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error($th);
                return response()->json(['message' => 'Something Error !'], 500);
            }
        
    }

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
