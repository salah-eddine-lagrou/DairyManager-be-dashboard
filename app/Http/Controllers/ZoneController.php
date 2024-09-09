<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::all();
        return response()->json([
            'success' => true,
            'data' => $zones
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
                'description' => 'required|string',
                'warehouse_id' => 'nullable|exists:warehouses,id'
            ]);

            $validated['code'] = $this->generateUniqueCode();

            // Create a new zone
            $zone = Zone::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Zone created successfully.',
                'data' => $zone
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
                'message' => 'An error occurred while creating the zone.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        // Get the latest code with the given prefix
        $prefix = "ZN";
        $latestCode = Zone::where('code', 'like', $prefix . '%')
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
            $zone = Zone::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $zone
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Zone not found.'
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
            $zone = Zone::findOrFail($id);

            // Validate the incoming request data
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('zones')->ignore($id)
                ],
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'warehouse_id' => 'sometimes|nullable|exists:warehouses,id'
            ]);

            // Update the zone
            $zone->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Zone updated successfully.',
                'data' => $zone
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
                'message' => 'An error occurred while updating the zone.',
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
            $zone = Zone::findOrFail($id);
            $zone->delete();

            return response()->json([
                'success' => true,
                'message' => 'Zone deleted successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Zone not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the zone.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
