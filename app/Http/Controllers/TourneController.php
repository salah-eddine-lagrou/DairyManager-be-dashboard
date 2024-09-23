<?php

namespace App\Http\Controllers;

use App\Models\Tourne;
use App\Models\TourneVendeur;
use Illuminate\Http\Request;

class TourneController extends Controller
{
    /**
     * Display a listing of the resource.
     * TODO in API url
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $tournes = Tourne::all();

            return response()->json([
                'success' => true,
                'message' => 'Tournes retrieved successfully.',
                'data' => $tournes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the tournes.',
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
     * ! removed the request we don't need it
     */
    public function store(): \Illuminate\Http\JsonResponse
    {
        try {
            // Create new Tourne
            $tourne = Tourne::create(array_merge(['status' => 'actif'])); // Default to 'actif'

            return response()->json([
                'success' => true,
                'message' => 'Tourne created successfully.',
                'data' => $tourne
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the tourne.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     * TODO in API url
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $tourne = Tourne::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Tourne retrieved successfully.',
                'data' => $tourne
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tourne not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the tourne.',
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
            // Validate input
            $validated = $request->validate([
                'status' => 'required|in:actif,inactif',
                // Add other validation rules if needed
            ]);

            $tourne = Tourne::findOrFail($id);
            $tourne->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tourne updated successfully.',
                'data' => $tourne
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
                'message' => 'Tourne not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the tourne.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // TODO in API url
    public function setActive($id): \Illuminate\Http\JsonResponse
    {
        try {
            $tourne = Tourne::findOrFail($id);
            $tourne->status = 'actif';
            $tourne->save();

            return response()->json([
                'success' => true,
                'message' => 'Tourne set to actif successfully.',
                'data' => $tourne
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tourne not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while setting the tourne to actif.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // TODO in API url
    public function setInactive($id): \Illuminate\Http\JsonResponse
    {
        try {
            // Find the Tourne record
            $tourne = Tourne::findOrFail($id);

            // Update the status of related TourneVendeur records
            TourneVendeur::where('tourne_id', $id)
                ->update(['status' => 'inactif']);

            // Set the Tourne status to inactif
            $tourne->status = 'inactif';
            $tourne->save();

            return response()->json([
                'success' => true,
                'message' => 'Tourne and related TourneVendeur records set to inactif successfully.',
                'data' => $tourne
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tourne not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while setting the tourne to inactif.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     * TODO in API url
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $tourne = Tourne::findOrFail($id);
            $tourne->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tourne deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tourne not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the tourne.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
