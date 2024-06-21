<?php



namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RestaurantReservations;  
use App\Models\Restaurant;  
use App\Http\Requests\Restaurant\ReservationRequest;
use App\Http\Requests\Restaurant\UpdateReservationRequest;



class RestaurantReservationsController extends Controller
{
    // Display a listing of the reservations
    public function index()
    {
        $reservations = RestaurantReservations::all();
        return response()->json(['reservations' => $reservations]);
    }

    // Store a newly created reservation in storage
    public function store(ReservationRequest $request)
    {
        $reservation = RestaurantReservations::create($request->validated());

        return response()->json(['message' => 'Reservation created successfully.', 'reservation' => $reservation], 201);
    }

    // Display the specified reservation
    public function show($id)
    {
        $reservation = RestaurantReservations::findOrFail($id);
        return response()->json(['reservation' => $reservation]);
    }

    // Update the specified reservation in storage
    public function update(UpdateReservationRequest $request, $id)
    {
        $reservation = RestaurantReservations::findOrFail($id);
        $reservation->update($request->validated());

        return response()->json(['message' => 'Reservation updated successfully.', 'reservation' => $reservation]);
    }

    // Remove the specified reservation from storage
    public function destroy($id)
    {
        $reservation = RestaurantReservations::findOrFail($id);
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully.']);
    }
}
