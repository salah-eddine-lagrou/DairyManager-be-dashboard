<?php

namespace App\Http\Controllers;

use App\Models\Tva;
use Illuminate\Http\Request;

class TvaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tvas = Tva::all();

            return response()->json([
                'success' => true,
                'message' => 'TVAs retrieved successfully.',
                'data' => $tvas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching TVAs.',
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
            $validated = $request->validate([
                'tva' => 'required|integer|min:0',
                'description' => 'required|string',
            ]);

            $tva = Tva::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'TVA created successfully.',
                'data' => $tva
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
                'message' => 'An error occurred while creating the TVA.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $tva = Tva::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'TVA retrieved successfully.',
                'data' => $tva
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'TVA not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the TVA.',
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
            $tva = Tva::findOrFail($id);

            $validated = $request->validate([
                'tva' => 'sometimes|required|integer|min:0',
                'description' => 'sometimes|required|string',
            ]);

            $tva->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'TVA updated successfully.',
                'data' => $tva
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
                'message' => 'TVA not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the TVA.',
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
            $tva = Tva::findOrFail($id);
            $tva->delete();

            return response()->json([
                'success' => true,
                'message' => 'TVA deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'TVA not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the TVA.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
