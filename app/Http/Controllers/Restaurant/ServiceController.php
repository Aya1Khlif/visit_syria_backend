<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Restaurant\ServiceRequest;
use App\Http\Requests\Restaurant\UpdateServiceRequest;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
class ServiceController extends Controller
{
    


        // Display a listing of the reservations
        public function index()
        {
            $Services = Service::all();
            return response()->json(['services' => $Services]);
        }
    
        // Store a newly created reservation in storage
        public function store(ServiceRequest $request)
        {
            $Service = Service::create($request->validated());
    
            return response()->json(['message' => 'Service created successfully.', 'Service' => $Service], 201);
        }
    
        // Display the specified reservation
        public function show($id)
        {
            $Service = Service::findOrFail($id);
            return response()->json(['Service' => $Service]);
        }
    
        // Update the specified reservation in storage
        public function update(UpdateServiceRequest $request, $id)
        {
            $service = Service::findOrFail($id);
            $service->update($request->validated());
    
            return response()->json(['message' => 'service updated successfully.', 'service' => $service]);
        }
    
        public function destroy($id)
        {
            $service = Service::findOrFail($id);
            $service->delete();
    
            return response()->json(['message' => 'service deleted successfully.']);
        }
    
    

        public function syncServices(Request $request, $restaurantId)
        {
            $validator = Validator::make($request->all(), [
                'service_ids' => 'required|array',
                'service_ids.*' => 'exists:services,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $restaurant = Restaurant::find($restaurantId);
    
            if (!$restaurant) {
                return response()->json(['error' => 'Restaurant not found'], 404);
            }
    
            $restaurant->services()->sync($request->service_ids);
    
            return response()->json(['message' => 'Services synchronized successfully']);
        }




}
