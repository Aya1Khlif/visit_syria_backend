<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagesRequest;
use App\Models\Blog;
use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function BlogImages(ImagesRequest $request,$blog_id)

    {
        DB::beginTransaction();
        try{
            $blog=Blog::where('id',$blog_id)->first();

            $image=UploadImage($request->image);
            $blog->images()->create([
                'image'=>$image
            ]);
            DB::commit();
            return response()->json(
                [
                    'message'=>'Image Added Successfully',
                    'image'=>$image

                ],200
                );
            }catch (\Exception $e) {
                DB::rollBack();
                return response()->json(
                    [
                        'message'=>'image Not Added',
                        'error'=>$e->getMessage()

                    ],400

                );
            }



        }




    /**
     * Display the specified resource.
     */
    public function show(Images $images)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Images $images)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Images $images)
    {
        //
    }
}
