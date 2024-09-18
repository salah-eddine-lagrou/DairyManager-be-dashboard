<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationHistory;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OperationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $operations = OperationHistory::all();
            return response()->json([
                'success' => true,
                'message' => 'Retrieved all operations history successfully.',
                'data' => $operations,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving operations history.',
                'error' => $e->getMessage(),
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
                'operation_type' => 'required|string',
                'operation_details' => 'required|string',
                'user_id' => 'nullable|exists:users,id',
            ]);

            $operation = OperationHistory::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Operation history entry created successfully.',
                'data' => $operation,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed for the provided data.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the operation history entry.',
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
            $operation = OperationHistory::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Retrieved the operation history entry successfully.',
                'data' => $operation,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Operation history entry not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the operation history entry.',
                'error' => $e->getMessage(),
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
            $validated = $request->validate([
                'operation_type' => 'sometimes|required|string',
                'operation_details' => 'sometimes|required|string',
                'user_id' => 'nullable|exists:users,id',
            ]);

            $operation = OperationHistory::findOrFail($id);
            $operation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Operation history entry updated successfully.',
                'data' => $operation,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed for the provided data.',
                'errors' => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Operation history entry not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the operation history entry.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $operation = OperationHistory::findOrFail($id);
            $operation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Operation history entry deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Operation history entry not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the operation history entry.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
