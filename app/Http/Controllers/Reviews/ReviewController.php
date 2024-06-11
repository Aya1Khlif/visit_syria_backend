<?php
namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\Reviews\ReviewRequest;

class ReviewController extends Controller
{
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $reviews = $restaurant->reviews()->with('user')->get();

        return response()->json($reviews);
    }

    public function store(ReviewRequest $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $review = new Review($request->validated());
        $review->restaurant_id = $restaurant->id;
        $review->save();

        return response()->json($review, 201);
    }
}
