<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectors = Sector::all();

        return response()->json([
            'success' => true,
            'message' => 'Sectors retrieved successfully.',
            'data' => $sectors
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'zone_id' => 'nullable|exists:zones,id', // Ensure zone_id exists in zones table
            ]);

            // Generate a unique code for the sector
            $validated['code'] = $this->generateUniqueCode();

            // Create a new sector
            $sector = Sector::create($validated);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Sector created successfully.',
                'data' => $sector
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the sector.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function generateUniqueCode()
    {
        // Define the prefix for the sector codes
        $prefix = "SC";

        // Get the latest code with the given prefix from the database
        $latestCode = Sector::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate the next sequential code
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        // Return the new code with prefix and padded number
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $sector = Sector::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Sector retrieved successfully.',
                'data' => $sector
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sector not found.'
            ], 404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $sector = Sector::findOrFail($id);

            // Validate the request
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sectors')->ignore($id)
                ],
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'zone_id' => 'sometimes|nullable|exists:zones,id',
            ]);

            // Update the sector with the validated data
            $sector->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sector updated successfully.',
                'data' => $sector
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sector not found.'
            ], 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $sector = Sector::findOrFail($id);

            // Delete the sector
            $sector->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sector deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sector not found.'
            ], 404);
        }
    }
}
