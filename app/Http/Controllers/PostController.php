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

class PostController extends Controller
{
    use ApiResponseTrait;
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
        try {
            $post = Post::create([
                'title'     => $request->title,
                'body'      => $request->body,
            ]);
            return $this->customeResponse(new PostResource($post),"Done",200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->customeResponse(null,"Error!!,there is something not correct",500);
        }
      
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
        try {
                if ($request->has('title')) {
                    $post->title = $request->title;
                }
                if ($request->has('body')) {
                    $post->body = $request->body;
                }
                $post->save();
            return $this->customeResponse(new PostResource($post),"Post updated successfully",200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->customeResponse(null,"Error!!,there is something not correct",500);
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
