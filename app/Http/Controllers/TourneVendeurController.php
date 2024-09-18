<?php

namespace App\Http\Controllers;

use App\Models\Tourne;
use App\Models\TourneVendeur;
use Illuminate\Http\Request;

class TourneVendeurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tourneVendeurs = TourneVendeur::all();

            return response()->json([
                'success' => true,
                'message' => 'TourneVendeur records retrieved successfully.',
                'data' => $tourneVendeurs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the records.',
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
            // Validation
            $validated = $request->validate([
                'tourne_id' => 'required|exists:tournes,id',
                'vendeur_id' => 'required|exists:users,id',
                'owner' => 'required|boolean',
                'status' => 'required|in:actif,inactif',
            ]);

            // Handle 'owner' status
            if ($validated['owner']) {
                // Find and update the previous owner record if exists
                TourneVendeur::where('tourne_id', $validated['tourne_id'])
                    ->where('owner', true)
                    ->update(['owner' => false]);
            }

            // Handle 'status'
            if ($validated['status'] === 'actif') {
                // Find and update the previous 'actif' record if exists
                TourneVendeur::where('tourne_id', $validated['tourne_id'])
                    ->where('status', 'actif')
                    ->update(['status' => 'inactif']);
                Tourne::where('id', $validated['tourne_id'])
                    ->update(['status' => 'actif']);
            }

            // Create a new TourneVendeur record
            $tourneVendeur = TourneVendeur::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'TourneVendeur record created successfully.',
                'data' => $tourneVendeur
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
                'message' => 'An error occurred while creating the record.',
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
            $tourneVendeur = TourneVendeur::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'TourneVendeur record retrieved successfully.',
                'data' => $tourneVendeur
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'TourneVendeur record not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the record.',
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
            $tourneVendeur = TourneVendeur::findOrFail($id);

            // Validation
            $validated = $request->validate([
                'tourne_id' => 'nullable|exists:tournes,id',
                'vendeur_id' => 'nullable|exists:users,id',
                'owner' => 'sometimes|required|boolean',
                'status' => 'sometimes|required|in:actif,inactif',
            ]);

            // Handle 'owner' status
            if (array_key_exists('owner', $validated) && $validated['owner']) {
                // If updating to owner, find and update the previous owner record if exists
                TourneVendeur::where('tourne_id', $validated['tourne_id'])
                    ->where('owner', true)
                    ->where('id', '!=', $id) // Exclude the current record
                    ->update(['owner' => false]);
            }

            // Handle 'status'
            if (array_key_exists('status', $validated) && $validated['status'] === 'actif') {
                // If updating to actif, find and update the previous actif record if exists
                TourneVendeur::where('tourne_id', $validated['tourne_id'])
                    ->where('status', 'actif')
                    ->where('id', '!=', $id) // Exclude the current record
                    ->update(['status' => 'inactif']);
            }

            // Update the TourneVendeur record
            $tourneVendeur->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'TourneVendeur record updated successfully.',
                'data' => $tourneVendeur
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
                'message' => 'TourneVendeur record not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the record.',
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
            $tourneVendeur = TourneVendeur::findOrFail($id);
            $tourneVendeur->delete();

            return response()->json([
                'success' => true,
                'message' => 'TourneVendeur record deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'TourneVendeur record not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
