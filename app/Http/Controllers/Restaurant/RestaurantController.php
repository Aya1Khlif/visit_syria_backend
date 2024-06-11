<?php


namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;

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
        $restaurant = Restaurant::findOrFail($id);

        return response()->json($restaurant);
    }

    public function store(RestaurantRequest $request)
    {
        // dd($request->all());
        $restaurant = Restaurant::create($request->validated());

        return response()->json($restaurant, 201);
    }

    public function update(UpdateRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $restaurant->update($request->validated());

        return response()->json($restaurant);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return response()->json(null, 204);
    }
}
