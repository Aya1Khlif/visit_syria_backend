<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Traits\UploadFileTrait;


class BlogController extends Controller
{
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
                //code...
                $blog = new Blog();
                $blog->title       = $request->title;
                $blog->description = $request->description;
                $blog->photo       = $this->UploadFile($request, 'Blog', 'photo');
    
                $blog->save();
    
                $data = new BlogResource($blog);
                return $this->customeResponse($data, 'Blog Created Successfully', 201);
            } catch (\Throwable $th) {
                Log::error($th);
                return $this->customeResponse(null, 'Failed To Create', 500);
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
        //
        try {
            //code...
            $blog->title       = $request->input('title') ?? $blog->title;
            $blog->description = $request->input('description') ?? $blog->description;
            $blog->photo       = $this->fileExists($request, 'Blogs', 'photo') ?? $blog->photo;

            $blog->save();

            $data = new BlogResource($blog);
            return $this->customeResponse($data, 'Blog Updated Successfully', 200);
        } catch (\Throwable $th) {
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
