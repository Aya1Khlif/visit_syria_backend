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
        if(!empty($posts)){
            return response()->json([
                'message' => 'Done!',
                'post' => $posts,

            ], 201);        }
            else{
                return response()->json([
                    'message' => 'There are not any post',]);

        }}
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

            $more_images=$post->images()->get('image');
            return response()->json([
                'message' => 'Post Created Successfully',
                'post' => $post,
                'more_images' => $more_images
            ], 201);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        $postData=[];

        try {


            DB::beginTransaction();


            if($request->title){
                $postData['title']=$request->title;
            }
            if($request->content){
                $postData['content']=$request->content;
            }

            if($request->category){
                $postData['category']=$request->category;
            }
            if($request->main_image){
                $main_image=uploadImage($request->main_image);
                $postData['main_image']=$main_image;

            }
            $oldImages = $post->images->pluck('image')->toArray();
            foreach ($oldImages as $oldImage) {
                $imagePath = storage_path( 'app/public/' .$oldImage);

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                } else {

                    Log::warning("File not found: " . $imagePath);
                }
            }


            $post->images()->delete();


            $more_images = [];
            if ($request->hasFile('more_images')) {
                $images = $request->file('more_images');

                foreach ($images as $image) {
                    $originalName = $image->getClientOriginalName();

                    // التحقق من وجود امتداد مزدوج في اسم الصورة
                    if (preg_match('/\.[^.]+\./', $originalName)) {
                        throw new Exception(403, trans('general.notAllowedAction'));
                    }

                    // تخزين الصورة الجديدة في نظام التخزين
                    $storagePath = Storage::disk('public')->put('images', $image, [
                        'visibility' => 'public'
                    ]);

                    // تحديث سجلات الصور الجديدة في قاعدة البيانات
                    $more_image = $post->images()->create([
                        'image' => $storagePath
                    ]);

                    $more_images[] = $more_image->image;
                }}
            $post->update($postData);



            DB::commit();


            return response()->json([
                'message'=>'post Updated successfully',
                'status' => 200,
                 'post'=>$postData,
                'more_images'=>$more_images

            ]);
        }catch (\Throwable $th) {
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

        $oldImages = $post->images->pluck('image')->toArray();
        foreach ($oldImages as $oldImage) {
            $imagePath = storage_path( 'app/public/' .$oldImage);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            } else {

                Log::warning("File not found: " . $imagePath);
            }
        }


        $post->images()->delete();

        $post->delete();
        return response()->json(['message' => 'Post Deleted'], 200);
    }

    }

