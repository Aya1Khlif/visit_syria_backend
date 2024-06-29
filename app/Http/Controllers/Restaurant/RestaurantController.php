<?php


namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($request->has('location')) {
            $query->where('location', $request->input('location'));
        }

        if ($request->has('sort')) {
            $query->orderBy($request->input('sort'), $request->input('order', 'asc'));
        }

        $restaurants = $query->get();

        return response()->json($restaurants);
    }

    public function show($id)
    {

        $restaurant = Restaurant::with('images')->findOrFail($id);

        return response()->json([
            'id' => $restaurant->id,
            'user_id' => $restaurant->user_id,
            'name' => $restaurant->name,
            'location' => $restaurant->location,
            'short_description' => $restaurant->short_description,
            'long_description' => $restaurant->long_description,
            'exterior_photos' => $restaurant->exterior_photos,
            'interior_photos' => $restaurant->interior_photos,
            'price' => $restaurant->price,
            'more_images' => $restaurant->images->pluck('image'), // Extracting image paths
        ]);
        // $restaurant = Restaurant::findOrFail($id);

        // return response()->json($restaurant);
    }

    public function store(RestaurantRequest $request)

    {

        if($request->exterior_photos){
            $exterior_photos = uploadImage($request->exterior_photos);}
            else $exterior_photos='null';
            $interior_photos = uploadImage($request->interior_photos);



        $restaurant = Restaurant::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'location' => $request->location,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'exterior_photos' => $exterior_photos,
            'interior_photos' => $interior_photos,
            'price' => $request->price,
        ]);

        $more_images = [];
        if ($request->hasFile('more_images')) {
            $images = $request->file('more_images');

            foreach ($images as $image) {
                $originalName = $image->getClientOriginalName();

                // Check for double extensions in the image name
                if (preg_match('/\.[^.]+\./', $originalName)) {
                    throw new Exception(trans('general.notAllowedAction'), 403);
                }

                $storagePath = Storage::disk('public')->put('images', $image, [
                    'visibility' => 'public',
                ]);

                $more_image = $restaurant->images()->create([
                    'image' => $storagePath,
                ]);

                $more_images[] = $more_image->image; // Collecting the image paths
            }
        }

        return response()->json([
            'message' => 'Restaurant Created Successfully',
            'restaurant' => $restaurant,
            'more_images' => $more_images, // Including more_images in the response
        ], 201);
    }



    public function update(UpdateRestaurantRequest $request,Restaurant $restaurant)
    {   $restaurantData=[];

       try {
           DB::beginTransaction();


            if($request->name){
                $restaurantData['name']=$request->name;
            }
            if($request->location){
                $restaurantData['location']=$request->location;
            }
            if($request->short_description){
                $restaurantData['short_description']=$request->short_description;
            }
            if($request->long_description){
                $restaurantData['long_description']=$request->long_description;
            }
            if($request->services){
                $restaurantData['services']=$request->services;
            }
            if($request->price){
                $restaurantData['price']=$request->price;
            }


            if($request->exterior_photos){
                $exterior_photos=uploadImage($request->exterior_photos);
                $restaurantData['exterior_photos']=$exterior_photos;

            }
            if($request->interior_photos){
                $interior_photos=uploadImage($request->interior_photos);
                $restaurantData['interior_photos']=$interior_photos;

            }

            $oldImages = $restaurant->images->pluck('image')->toArray();
                foreach ($oldImages as $oldImage) {
                    $imagePath = storage_path( 'app/public/' .$oldImage);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    } else {

                        Log::warning("File not found: " . $imagePath);
                    }
                }


                $restaurant->images()->delete();


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
                        $more_image = $restaurant->images()->create([
                            'image' => $storagePath
                        ]);

                        $more_images[] = $more_image->image;
                    }}


                     $restaurant->update($restaurantData);


            // DB::commit();


            return response()->json([
                'message'=>'restaurant Updated successfully',
                'status' => 200,
                 'restaurant'=>$restaurantData,
                'more_images'=>$more_images

            ]);
       }catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['message' => 'Something Error !'], 500);
    }
            }

    public function destroy(Restaurant $restaurant)
    {
        $oldImages = $restaurant->images->pluck('image')->toArray();
        foreach ($oldImages as $oldImage) {
            $imagePath = storage_path( 'app/public/' .$oldImage);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            } else {

                Log::warning("File not found: " . $imagePath);
            }
        }


        $restaurant->images()->delete();

        $restaurant->delete();
        return response()->json(['message' => 'Resturant Deleted'], 200);
    }
}
