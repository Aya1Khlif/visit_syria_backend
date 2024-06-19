<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Landmarks;
use Illuminate\Http\Request;

use App\Http\Requests\Landmarks\LandmarksRequest;
use App\Http\Requests\Landmarks\UpdateLandmarksRequest;

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
        
        $landmarks = Landmarks::create($request->validated());

        return response()->json($landmarks, 201);
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
    public function update(UpdateLandmarksRequest $request, $id)
    {
        $landmarks = Landmarks::findOrFail($id);

        $landmarks->update($request->validated());

        return response()->json($landmarks);
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
