<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::all();

        return response()->json([
            'success' => true,
            'message' => 'Warehouses retrieved successfully.',
            'data' => $warehouses
        ]);
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
                'warehouse_type' => 'required|in:mobile,livraison',
                'agency_id' => 'nullable|exists:agencies,id',
            ]);

            $validated['code'] = $this->generateUniqueCode();

            // Create the new warehouse
            $warehouse = Warehouse::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Warehouse created successfully.',
                'data' => $warehouse
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
                'message' => 'An error occurred while creating the warehouse.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        // Get the latest code with the given prefix
        $prefix = "WR";
        $latestCode = Warehouse::where('code', 'like', $prefix . '%')
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
            $warehouse = Warehouse::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Warehouse retrieved successfully.',
                'data' => $warehouse
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the warehouse.',
                'error' => $e->getMessage()
            ], 500);
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
            $warehouse = Warehouse::findOrFail($id);

            // Validate the incoming request data
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('warehouses')->ignore($warehouse->id),
                ],
                'name' => 'sometimes|required|string|max:255',
                'location' => 'sometimes|required|string|max:255',
                'created_by_id' => 'nullable|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'status' => 'sometimes|required|in:actif,inactif',
                'warehouse_type' => 'sometimes|required|in:mobile,livraison',
                'agency_id' => 'nullable|exists:agencies,id',
            ]);

            // Update the warehouse
            $warehouse->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Warehouse updated successfully.',
                'data' => $warehouse
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the warehouse.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // set actif function
    public function setActif($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);

            $warehouse->status = 'actif';
            $warehouse->save();

            return response()->json([
                'success' => true,
                'message' => 'Warehouse status set to actif.',
                'data' => $warehouse
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while setting the warehouse status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // set inactif function
    public function setInactif($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);

            $warehouse->status = 'inactif';
            $warehouse->save();

            return response()->json([
                'success' => true,
                'message' => 'Warehouse status set to inactif.',
                'data' => $warehouse
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while setting the warehouse status.',
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
            $warehouse = Warehouse::findOrFail($id);

            // Delete the warehouse
            $warehouse->delete();

            return response()->json([
                'success' => true,
                'message' => 'Warehouse deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the warehouse.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
