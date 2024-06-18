<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use Illuminate\Http\Request;

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
        $hotel = Hotel::create($request->validated());

        return response()->json($hotel, 201);
    }

    public function update(UpdateHotelRequest $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $hotel->update($request->validated());

        return response()->json($hotel);
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return response()->json(null, 204);
    }
}
