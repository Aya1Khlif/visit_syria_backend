<?php
namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\Reviews\ReviewRequest;



class ReviewController extends Controller
{
    // Fetch reviews for a specific restaurant
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $reviews = $restaurant->reviews()->with('user')->get();

        return response()->json($reviews);
    }

    // Add a new review for a restaurant
    public function store(ReviewRequest $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

    
        $review = new Review($request->validated());
        $review->reviewable_id = $restaurant->id;
        $review->reviewable_type = get_class($restaurant);
        $review->restaurant_id = $restaurant->id;
        $review->save();

        return response()->json($review, 201);
    }

    // Fetch a single review
    public function show($restaurantId, $reviewId)
    {
        $review = Review::where('reviewable_id', $restaurantId)
            ->where('reviewable_type', Restaurant::class)
            ->findOrFail($reviewId);

        return response()->json($review);
    }

    // Update a review
    public function update(Request $request, $restaurantId, $reviewId)
    {
        $review = Review::where('reviewable_id', $restaurantId)
            ->where('reviewable_type', Restaurant::class)
            ->findOrFail($reviewId);

        $validatedData = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string',
        ]);

        $review->update($validatedData);

        return response()->json($review);
    }

    // Delete a review
    public function destroy($restaurantId, $reviewId)
    {
        $review = Review::where('reviewable_id', $restaurantId)
            ->where('reviewable_type', Restaurant::class)
            ->findOrFail($reviewId);

        $review->delete();

        return response()->json(null, 204);
    }
}
