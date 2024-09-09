<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;
use Illuminate\Validation\Rule;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get all agencies
            $agencies = Agency::all();

            // Return success response
            return response()->json([
                'message' => 'Agencies retrieved successfully',
                'data' => $agencies
            ], 200);

        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'message' => 'Error retrieving agencies',
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
                'location' => 'required|string|max:255',
                'created_by_id' => 'nullable|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'status' => 'required|in:actif,inactif',
            ]);

            // Create the new agency in the database
            $validated['code'] = $this->generateUniqueCode();
            $agency = Agency::create($validated);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Agency created successfully.',
                'data' => $agency // Can be removed after testing
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a response for validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // General error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the agency.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        // Get the latest code with the given prefix
        $prefix = "AG";
        $latestCode = Agency::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate new code
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find the agency
            $agency = Agency::findOrFail($id);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Agency found.',
                'data' => $agency // Can be removed after testing
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Agency not found.',
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
            $agency = Agency::findOrFail($id);

            // Validate the incoming request data
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('warehouses')->ignore($agency->id),
                ],
                'name' => 'sometimes|required|string|max:255',
                'location' => 'sometimes|required|string|max:255',
                'created_by_id' => 'nullable|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'status' => 'sometimes|required|in:actif,inactif',
            ]);

            // Find the agency and update it
            $agency->update($validated);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Agency updated successfully.',
                'data' => $agency // Can be removed after testing
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the agency.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // set active function
    public function setActive($id)
    {
        try {
            // Find the agency by ID
            $agency = Agency::findOrFail($id);

            // Check if the agency is already active
            if ($agency->status === 'actif') {
                return response()->json([
                    'success' => false,
                    'message' => 'The agency is already active.',
                ], 400);
            }

            // Update the status to active
            $agency->status = 'actif';
            $agency->save();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Agency status updated to active successfully.',
                'data' => $agency // Can be removed after testing
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the agency status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // set inactive function
    public function setInactive($id)
    {
        try {
            // Find the agency by ID
            $agency = Agency::findOrFail($id);

            // Check if the agency is already inactive
            if ($agency->status === 'inactif') {
                return response()->json([
                    'success' => false,
                    'message' => 'The agency is already inactive.',
                ], 400);
            }

            // Update the status to inactive
            $agency->status = 'inactif';
            $agency->save();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Agency status updated to inactive successfully.',
                'data' => $agency // Can be removed after testing
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the agency status.',
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
            // Find the agency
            $agency = Agency::findOrFail($id);

            // Delete the agency
            $agency->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Agency deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the agency.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
