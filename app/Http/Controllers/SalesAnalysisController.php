<?php

namespace App\Http\Controllers;

use App\Models\SalesAnalysis;
use Illuminate\Http\Request;

class SalesAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $salesAnalysis = SalesAnalysis::all();

            return response()->json([
                'success' => true,
                'data' => $salesAnalysis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving sales analysis data.',
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'vendeur_id' => 'nullable|exists:users,id',
                'period' => 'required|string|max:255',
                'total_sales' => 'required|numeric',
                'total_returns' => 'required|numeric',
                'total_discounts' => 'required|numeric',
                'net_sales' => 'required|numeric',
            ]);

            $salesAnalysis = SalesAnalysis::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sales analysis record created successfully.',
                'data' => $salesAnalysis
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
                'message' => 'An error occurred while creating the sales analysis record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $salesAnalysis = SalesAnalysis::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $salesAnalysis
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sales analysis record not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the sales analysis record.',
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
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $salesAnalysis = SalesAnalysis::findOrFail($id);

            $validated = $request->validate([
                'vendeur_id' => 'nullable|exists:users,id',
                'period' => 'sometimes|required|string|max:255',
                'total_sales' => 'sometimes|required|numeric',
                'total_returns' => 'sometimes|required|numeric',
                'total_discounts' => 'sometimes|required|numeric',
                'net_sales' => 'sometimes|required|numeric',
            ]);

            $salesAnalysis->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sales analysis record updated successfully.',
                'data' => $salesAnalysis
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
                'message' => 'Sales analysis record not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the sales analysis record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $salesAnalysis = SalesAnalysis::findOrFail($id);
            $salesAnalysis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sales analysis record deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sales analysis record not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the sales analysis record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
