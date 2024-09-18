<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $equipments = Equipement::with('category')->get();

            return response()->json([
                'success' => true,
                'message' => 'Equipments retrieved successfully.',
                'data' => $equipments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving equipments.',
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
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'equipement_category_id' => 'nullable|exists:equipement_categories,id',
                'equipement_state' => 'required|in:confort,bon-etat-mais-vide,mal-presente,autres-produits',
            ]);

            $validated['code'] = $this->generateUniqueCode();

            $equipement = Equipement::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Equipement created successfully.',
                'data' => $equipement
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
                'message' => 'An error occurred while creating the equipement.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        $prefix = "EQ";

        $latestCode = Equipement::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function syncEquipementsClients(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Validate the request to ensure both client IDs and equipement IDs are provided
            $validated = $request->validate([
                'client_ids' => 'required|array',
                'client_ids.*' => 'exists:clients,id', // Ensure all client IDs exist in the 'clients' table
                'equipement_ids' => 'required|array',
                'equipement_ids.*' => 'exists:equipements,id', // Ensure all equipement IDs exist in the 'equipements' table
            ]);

            // Iterate over each client ID and sync the related equipments
            foreach ($validated['client_ids'] as $clientId) {
                $client = Client::findOrFail($clientId);
                // Sync the provided equipement IDs with the current client
                $client->equipements()->sync($validated['equipement_ids']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Equipments successfully synced with the clients.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'One or more records not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while syncing the equipments with the clients.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $equipement = Equipement::with('category')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Equipement retrieved successfully.',
                'data' => $equipement
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Equipement not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the equipement.',
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
            $equipement = Equipement::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('equipements')->ignore($equipement->id),
                ],
                'quantity' => 'sometimes|required|integer|min:1',
                'equipement_category_id' => 'nullable|exists:equipement_categories,id',
                'equipement_state' => 'sometimes|required|in:confort,bon-etat-mais-vide,mal-presente,autres-produits',
            ]);

            $equipement->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Equipement updated successfully.',
                'data' => $equipement
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
                'message' => 'Equipement not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the equipement.',
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
            $equipement = Equipement::findOrFail($id);
            $equipement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Equipement deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Equipement not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the equipement.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
