<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get all units
            $units = Unit::all();

            // Return success response
            return response()->json([
                'message' => 'Units retrieved successfully',
                'data' => $units
            ], 200);

        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'message' => 'Error retrieving units',
                'error' => $e->getMessage()
            ], 500);
        }
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
                'description' => 'required|string|max:500',
                'unit_category' => 'required|in:item,batch',
            ]);

            // Create the new unit in the database
            $unit = Unit::create($validated);

            // Return a success response with the newly created resource
            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully.',
                'data' => $unit // ! return of the data created can be removed after testing
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a response for validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors() // Return the validation errors
            ], 422);

        } catch (\Exception $e) {
            // Return a general error response in case of unexpected failures
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the unit.',
                'error' => $e->getMessage() // Optionally log the error message
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find the unit by ID
            $unit = Unit::findOrFail($id);

            // Return the unit in a success response
            return response()->json([
                'message' => 'Unit retrieved successfully',
                'data' => $unit
            ], 200);

        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'message' => 'Unit not found',
                'error' => $e->getMessage()
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
            // Validate the input
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'unit_category' => 'required|in:item,batch',
            ]);

            // Find the unit by ID
            $unit = Unit::findOrFail($id);

            // Update the unit with validated data
            $unit->update($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Unit updated successfully',
                'data' => $unit
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'message' => 'Error updating unit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the unit by ID
            $unit = Unit::findOrFail($id);

            // Delete the unit
            $unit->delete();

            // Return success response
            return response()->json([
                'message' => 'Unit deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'message' => 'Error deleting unit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
