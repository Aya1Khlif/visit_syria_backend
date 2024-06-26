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

class BlogController extends Controller
{
    use ApiResponseTrait, UploadFileTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        if(!empty($blogs)){
            $data = BlogResource::collection($blogs);
            return response()->json([
                'message' => 'Done!',
                'blog' => $data,

            ], 201);        }
            else{
                return response()->json([
                    'message' => 'There are not any blog',]);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {


            $blog = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id'=>$request->user_id,
                'main_image' => $this->UploadFile($request, 'Blog', 'main_image'),


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

            $more_image=$blog->images()->create([
                'image' => $storagePath
            ]);
            $more_images[] = $more_image->image; // Collecting the image paths



        }



                }
    return response()->json([
        'message' => 'Blog Created Successfully',
        'blog' => $blog,
        'more_images' => $more_images
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
       // $data = new BlogResource($blog);
        $more_images=$blog->images()->get('image');
        return response()->json([
            'message' => 'Blogs',
            'blog' => $blog,
            'more_images' => $more_images
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest  $request, Blog $blog)
    {
            //code...
            $blogData=[];
            try {
               DB::beginTransaction();
                if($request->title){
                    $blogData['title']=$request->title;
                }
                if($request->content){
                    $blogData['content']=$request->content;
                }
                if($request->main_image){
                    $main_image=uploadImage($request->main_image);
                    $blogData['main_image']=$main_image;

                }




    $oldImages = $blog->images->pluck('image')->toArray();
    foreach ($oldImages as $oldImage) {
        $imagePath = storage_path( 'app/public/' .$oldImage);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {

            Log::warning("File not found: " . $imagePath);
        }
    }


    $blog->images()->delete();


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
            $more_image = $blog->images()->create([
                'image' => $storagePath
            ]);

            $more_images[] = $more_image->image;
        }}
                $blog->update($blogData);

                DB::commit();
                return response()->json([
                    'message'=>'Blog Updated successfully',
                    'status' => 200,
                     'blog'=>$blogData,
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
    public function destroy(Blog $blog)
    {
        $oldImages = $blog->images->pluck('image')->toArray();
        foreach ($oldImages as $oldImage) {
            $imagePath = storage_path( 'app/public/' .$oldImage);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            } else {

                Log::warning("File not found: " . $imagePath);
            }
        }


        $blog->images()->delete();
        //
        $blog->delete();
        return response()->json(['message' => 'Blog Deleted'], 200);
    }
}
