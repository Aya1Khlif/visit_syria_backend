<?php

namespace App\Http\Controllers;

use App\Models\RestaurantReservations;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Booking;


class ReportController extends Controller
{
    
    public function index()
    {
        // Fetch top-rated restaurants (assuming 'rating' field exists in reviews)
        $topRatedRestaurants = Restaurant::with('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(5)
            ->get();

        // Fetch most visited restaurants (assuming 'visits' field exists in restaurants)
        $mostVisitedRestaurants = Restaurant::orderByDesc('visits')->take(5)->get();

        // Fetch total reservations
        $totalReservations = RestaurantReservations::count();

        // Fetch total revenue (assuming 'price' field exists in bookings)
        $totalRevenue = RestaurantReservations::sum('price');

        // Construct the response
        $data = [
            'top_rated_restaurants' => $topRatedRestaurants,
            'most_visited_restaurants' => $mostVisitedRestaurants,
            'total_reservations' => $totalReservations,
            'total_revenue' => $totalRevenue,
        ];

        return response()->json($data);
    }
}

