<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientPayment;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $payments = ClientPayment::all();
            return response()->json([
                'success' => true,
                'message' => 'Retrieved all client payments successfully.',
                'data' => $payments,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving client payments.',
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
                'amount' => 'required|numeric',
                'transaction_date' => 'required|date',
                'payment_method' => 'required|in:avoir,especes,cheque,virement,versement,effet',
                'transaction_type' => 'required|in:paiement,acompte',
                'order_id' => 'nullable|exists:orders,id',
                'client_id' => 'nullable|exists:clients,id',
                'code' => 'required|string',
                'payment_period' => 'required|string',
                'discount' => 'nullable|numeric',
                'notes' => 'nullable|string',
            ]);

            $payment = ClientPayment::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Client payment created successfully.',
                'data' => $payment,
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
                'message' => 'An error occurred while creating the client payment.',
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
            $payment = ClientPayment::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Retrieved the client payment successfully.',
                'data' => $payment,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client payment not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the client payment.',
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
                'amount' => 'sometimes|required|numeric',
                'transaction_date' => 'sometimes|required|date',
                'payment_method' => 'sometimes|required|in:avoir,especes,cheque,virement,versement,effet',
                'transaction_type' => 'sometimes|required|in:paiement,acompte',
                'order_id' => 'nullable|exists:orders,id',
                'client_id' => 'nullable|exists:clients,id',
                'code' => 'sometimes|required|string',
                'payment_period' => 'sometimes|required|string',
                'discount' => 'nullable|numeric',
                'notes' => 'nullable|string',
            ]);

            $payment = ClientPayment::findOrFail($id);
            $payment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Client payment updated successfully.',
                'data' => $payment,
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
                'message' => 'Client payment not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the client payment.',
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
            $payment = ClientPayment::findOrFail($id);
            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client payment deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client payment not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the client payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
