<?php

namespace App\Http\Controllers;

use App\Models\PriceListProductDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PriceListProductDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $details = PriceListProductDetails::all();
            return response()->json([
                'success' => true,
                'message' => 'Retrieved all price list product details successfully.',
                'data' => $details,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the price list product details.',
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
                'product_id' => 'required|exists:products,id',
                'price_list_id' => 'required|exists:price_lists,id',
                'code' => 'required|string|unique:price_list_product_details,code',
                'sale_price' => 'required|numeric',
                'return_price' => 'required|numeric',
                'valid_from' => 'required|date',
                'valid_to' => 'required|date',
                'closed' => 'required|boolean',
            ]);

            $detail = PriceListProductDetails::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Price list product detail created successfully.',
                'data' => $detail,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException
        $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed for the provided data.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the price list product detail.',
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
            $detail = PriceListProductDetails::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Retrieved the price list product detail successfully.',
                'data' => $detail,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Price list product detail not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the price list product detail.',
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
                'product_id' => 'sometimes|required|exists:products,id',
                'price_list_id' => 'sometimes|required|exists:price_lists,id',
                'code' => 'sometimes|required|string|unique:price_list_product_details,code,' . $id,
                'sale_price' => 'sometimes|required|numeric',
                'return_price' => 'sometimes|required|numeric',
                'valid_from' => 'sometimes|required|date',
                'valid_to' => 'sometimes|required|date',
                'closed' => 'sometimes|required|boolean',
            ]);

            $detail = PriceListProductDetails::findOrFail($id);
            $detail->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Price list product detail updated successfully.',
                'data' => $detail,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed for the provided data.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Price list product detail not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the price list product detail.',
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
            $detail = PriceListProductDetails::findOrFail($id);
            $detail->delete();

            return response()->json([
                'success' => true,
                'message' => 'Price list product detail deleted successfully.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Price list product detail not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the price list product detail.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
