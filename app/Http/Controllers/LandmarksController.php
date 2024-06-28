<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Landmarks;
use Illuminate\Http\Request;

use App\Http\Requests\Landmarks\LandmarksRequest;
use App\Http\Requests\Landmarks\UpdateLandmarksRequest;
use App\Services\ApiResponseService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

class LandmarksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Landmarks::query();

        if ($request->has('location')) {
            $query->where('location', $request->input('location'));
        }

        if ($request->has('sort')) {
            $query->orderBy($request->input('sort'), $request->input('order', 'asc'));
        }

        $landmarks = $query->get();

        return response()->json($landmarks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LandmarksRequest $request)
    {

        if($request->exterior_photos){
            $exterior_photos = uploadImage($request->exterior_photos);}
            else $exterior_photos='null';
            $interior_photos = uploadImage($request->interior_photos);


        $landmarks=Landmarks::create([
            'user_id'=>$request->user_id,
            'name'=>$request->name,
            'location'=>$request->location,
             'short_description'=>$request->short_description,
             'long_description'=>$request->long_description,
             'exterior_photos'=>$exterior_photos,
             'interior_photos'=>$interior_photos,
             //'more_images'=>$request->,
             'services'=>$request->services,
             'price'=>$request->price,
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
    $more_image=$landmarks->images()->create([
        'image' => $storagePath
    ]);

    $more_images[] = $more_image->image; // Collecting the image paths





}

        }
return response()->json([
'message' => 'Landmark Created Successfully',
'landmarks' => $landmarks,
'more_images' => $more_images,
], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $landmarks = Landmarks::findOrFail($id);

        return response()->json($landmarks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLandmarksRequest $request,Landmarks $landmark)
    {
        // $landmarks = Landmarks::findOrFail($id);

        // $landmarks->update($request->validated());

        // return response()->json($landmarks);
        $landmarkData=[];

        try {


            DB::beginTransaction();


            if($request->name){
                $landmarkData['name']=$request->name;
            }
            if($request->location){
                $landmarkData['location']=$request->location;
            }
            if($request->short_description){
                $landmarkData['short_description']=$request->short_description;
            }
            if($request->long_description){
                $landmarkData['long_description']=$request->long_description;
            }
            if($request->services){
                $landmarkData['services']=$request->services;
            }
            if($request->price){
                $landmarkData['price']=$request->price;
            }


            if($request->exterior_photos){
                $exterior_photos=uploadImage($request->exterior_photos);
                $landmarkData['exterior_photos']=$exterior_photos;

            }
            if($request->interior_photos){
                $interior_photos=uploadImage($request->interior_photos);
                $landmarkData['interior_photos']=$interior_photos;

            }

            $landmark->update($landmarkData);


            DB::commit();


            return ApiResponseService::success([
                'landmark'=>$landmarkData
            ],'landmark Updated successfully',200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['message' => 'Something Error !'], 500);
        }


    // return response()->json($landmark);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $landmarks = Landmarks::findOrFail($id);
        $landmarks->delete();

        return response()->json(null, 204);
    }
}
