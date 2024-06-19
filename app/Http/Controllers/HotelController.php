<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::query();

        if ($request->has('location')) {
            $query->where('location', $request->input('location'));
        }

        if ($request->has('sort')) {
            $query->orderBy($request->input('sort'), $request->input('order', 'asc'));
        }

        $hotels = $query->get();

        return response()->json($hotels);
    }

    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);

        return response()->json($hotel);
    }

    public function store(HotelRequest $request)
    {
        // dd($request->all());
        //$hotel = Hotel::create($request->validated());
        $exterior_photos=uploadImage($request->exterior_photos);
        $interior_photos=uploadImage($request->interior_photos);

        $hotel=Hotel::create([
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

        return response()->json($hotel, 201);
    }

    public function update(UpdateHotelRequest $request,Hotel $hotel)
    {

        $hotelData=[];

            try {


                DB::beginTransaction();


                if($request->name){
                    $hotelData['name']=$request->name;
                }
                if($request->location){
                    $hotelData['location']=$request->location;
                }
                if($request->short_description){
                    $hotelData['short_description']=$request->short_description;
                }
                if($request->long_description){
                    $hotelData['long_description']=$request->long_description;
                }
                if($request->services){
                    $hotelData['services']=$request->services;
                }
                if($request->price){
                    $hotelData['price']=$request->price;
                }


                if($request->exterior_photos){
                    $exterior_photos=uploadImage($request->exterior_photos);
                    $hotelData['exterior_photos']=$exterior_photos;

                }
                if($request->interior_photos){
                    $interior_photos=uploadImage($request->interior_photos);
                    $hotelData['interior_photos']=$interior_photos;

                }

                $hotel->update($hotelData);


                DB::commit();


                return ApiResponseService::success([
                    'hotel'=>$hotelData
                ],'hotel Updated successfully',200); // return $this->customeResponse(new hotelResource($hotel), 'hotel Updated Successfully', 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error($th);
                return response()->json(['message' => 'Something Error !'], 500);
            }
        // $hotel = Hotel::findOrFail($id);

        // $hotel->update($request->validated());

        return response()->json($hotel);
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return response()->json(null, 204);
    }
}
