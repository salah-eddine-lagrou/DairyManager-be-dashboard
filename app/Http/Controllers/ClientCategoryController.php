<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientCategory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClientCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all client categories
            $clientCategories = ClientCategory::all();

            // Return the list of client categories
            return response()->json([
                'success' => true,
                'data' => $clientCategories
            ], 200);
        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the client categories.',
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
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string'
            ]);

            // Generate the unique code for the client category
            $validated['code'] = $this->generateUniqueCode();

            // Create the client category
            $clientCategory = ClientCategory::create($validated);

            // Return the created response
            return response()->json([
                'success' => true,
                'message' => 'Client category created successfully.',
                'data' => $clientCategory
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exception
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the client category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        // Prefix for client category code
        $prefix = "CC";

        // Get the latest code from the database
        $latestCode = ClientCategory::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate the next number in the sequence
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        // Format the code as CC00001, CC00002, etc.
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find the client category by ID
            $clientCategory = ClientCategory::findOrFail($id);

            // Return the client category
            return response()->json([
                'success' => true,
                'data' => $clientCategory
            ], 200);
        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'Client category not found.',
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
            // Find the client category by ID
            $clientCategory = ClientCategory::findOrFail($id);

            // Validate the incoming data
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('client_categories')->ignore($clientCategory->id),
                ],
            ]);

            // Update the client category
            $clientCategory->update($validated);

            // Return the updated response
            return response()->json([
                'success' => true,
                'message' => 'Client category updated successfully.',
                'data' => $clientCategory
            ], 200);
        } catch (ValidationException $e) {
            // Handle validation exception
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the client category.',
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
            // Find the client category by ID
            $clientCategory = ClientCategory::findOrFail($id);

            // Delete the client category
            $clientCategory->delete();

            // Return the success response
            return response()->json([
                'success' => true,
                'message' => 'Client category deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the client category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
