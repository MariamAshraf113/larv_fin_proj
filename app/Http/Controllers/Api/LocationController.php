<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index() {
        $locations = Location::all();
        return response()->json($locations, 200);
    }

    public function show($id) {
        $location = Location::find($id);

        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        return response()->json($location, 200);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $location = Location::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($location, 201);
    }


    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $location = Location::find($id);

        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        $location->name = $request->input('name');
        $location->save();

        return response()->json($location, 200);
    }

    public function destroy($id) {
        $location = Location::find($id);

        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        $location->delete();

        return response()->json(['message' => 'Location deleted'], 200);
    }
}
