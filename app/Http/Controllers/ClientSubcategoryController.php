<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientSubcategory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClientSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all client subcategories
            $clientSubcategories = ClientSubcategory::all();

            // Return the list of client subcategories
            return response()->json([
                'success' => true,
                'data' => $clientSubcategories
            ], 200);

        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the client subcategories.',
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
                'description' => 'required|string',
                'client_categorie_id' => 'nullable|exists:client_categories,id',
            ]);

            // Generate the unique code for the client subcategory
            $validated['code'] = $this->generateUniqueCode();

            // Create the client subcategory
            $clientSubcategory = ClientSubcategory::create($validated);

            // Return the created response
            return response()->json([
                'success' => true,
                'message' => 'Client subcategory created successfully.',
                'data' => $clientSubcategory
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
                'message' => 'An error occurred while creating the client subcategory.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        // Prefix for client subcategory code
        $prefix = "CS";

        // Get the latest code from the database
        $latestCode = ClientSubcategory::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate the next number in the sequence
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        // Format the code as CS00001, CS00002, etc.
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find the client subcategory by ID
            $clientSubcategory = ClientSubcategory::findOrFail($id);

            // Return the client subcategory
            return response()->json([
                'success' => true,
                'data' => $clientSubcategory
            ], 200);

        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'Client subcategory not found.',
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
            // Find the client subcategory by ID
            $clientSubcategory = ClientSubcategory::findOrFail($id);

            // Validate the incoming data
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'client_categorie_id' => 'nullable|exists:client_categories,id',
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('client_subcategories')->ignore($clientSubcategory->id),
                ],
            ]);

            // Update the client subcategory
            $clientSubcategory->update($validated);

            // Return the updated response
            return response()->json([
                'success' => true,
                'message' => 'Client subcategory updated successfully.',
                'data' => $clientSubcategory
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
                'message' => 'An error occurred while updating the client subcategory.',
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
            // Find the client subcategory by ID
            $clientSubcategory = ClientSubcategory::findOrFail($id);

            // Delete the client subcategory
            $clientSubcategory->delete();

            // Return the success response
            return response()->json([
                'success' => true,
                'message' => 'Client subcategory deleted successfully.'
            ], 200);

        } catch (\Exception $e) {
            // Handle general exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the client subcategory.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
