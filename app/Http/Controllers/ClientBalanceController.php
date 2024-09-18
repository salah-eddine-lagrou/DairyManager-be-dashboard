<?php

namespace App\Http\Controllers;

use App\Models\ClientBalance;
use Illuminate\Http\Request;

class ClientBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $balances = ClientBalance::all();
            return response()->json([
                'success' => true,
                'data' => $balances
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching client balances.',
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
                'balance_amount' => 'required|numeric',
                'bl_amount' => 'required|numeric',
                'credit_note_amount' => 'required|numeric',
                'unpaid_amount' => 'required|numeric',
                'description' => 'nullable|string',
                'client_id' => 'required|exists:clients,id',
            ]);

            $balance = ClientBalance::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Client balance created successfully.',
                'data' => $balance
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
                'message' => 'An error occurred while creating the client balance.',
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
            $balance = ClientBalance::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $balance
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client balance not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the client balance.',
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
            $balance = ClientBalance::findOrFail($id);

            $validated = $request->validate([
                'balance_amount' => 'sometimes|required|numeric',
                'bl_amount' => 'sometimes|required|numeric',
                'credit_note_amount' => 'sometimes|required|numeric',
                'unpaid_amount' => 'sometimes|required|numeric',
                'description' => 'nullable|string',
                'client_id' => 'nullable|exists:clients,id',
            ]);

            $balance->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Client balance updated successfully.',
                'data' => $balance
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
                'message' => 'Client balance not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the client balance.',
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
            $balance = ClientBalance::findOrFail($id);
            $balance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client balance deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client balance not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the client balance.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
