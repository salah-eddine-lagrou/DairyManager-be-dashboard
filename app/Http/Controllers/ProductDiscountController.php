<?php

namespace App\Http\Controllers;

use App\Models\ProductDiscount;
use Illuminate\Http\Request;

class ProductDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $productDiscounts = ProductDiscount::with('products')->get(); // Fetch discounts with related products

            return response()->json([
                'success' => true,
                'data' => $productDiscounts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving product discounts.',
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
                'discount_rate' => 'required|numeric',
                'discount_type' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'description' => 'nullable|string',
                'status' => 'required|in:actif,inactif',
                'product_ids' => 'nullable|array', // Expect an array of product IDs
                'product_ids.*' => 'exists:products,id', // Validate that each product ID exists
            ]);

            $productDiscount = ProductDiscount::create($validated);

            // Sync the relationship with products in the pivot table
            $productDiscount->products()->sync($validated['product_ids']);

            return response()->json([
                'success' => true,
                'message' => 'Product discount created successfully.',
                'data' => $productDiscount->load('products') // Load products relation
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
                'message' => 'An error occurred while creating the product discount.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncDiscountsProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Validate the request to ensure both product IDs and discount IDs are provided
            $validated = $request->validate([
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:products,id', // Ensure all product IDs exist in the 'products' table
                'discount_ids' => 'required|array',
                'discount_ids.*' => 'exists:product_discounts,id', // Ensure all discount IDs exist in the 'product_discounts' table
            ]);

            // Iterate over each discount ID and sync the related products
            foreach ($validated['discount_ids'] as $discountId) {
                $productDiscount = ProductDiscount::findOrFail($discountId);
                // Sync the provided product IDs with the current discount
                $productDiscount->products()->sync($validated['product_ids']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Products successfully synced with the discounts.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'One or more records not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while syncing the products with the discounts.',
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
            $productDiscount = ProductDiscount::with('products')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $productDiscount
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product discount not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the product discount.',
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
            $productDiscount = ProductDiscount::findOrFail($id);

            $validated = $request->validate([
                'discount_rate' => 'sometimes|required|numeric',
                'discount_type' => 'sometimes|required|string|max:255',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date|after_or_equal:start_date',
                'description' => 'nullable|string',
                'status' => 'sometimes|required|in:actif,inactif',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id',
            ]);

            $productDiscount->update($validated);

            // If product_ids are provided, sync them with the pivot table
            if (isset($validated['product_ids'])) {
                $productDiscount->products()->sync($validated['product_ids']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product discount updated successfully.',
                'data' => $productDiscount->load('products')
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
                'message' => 'Product discount not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the product discount.',
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
            $productDiscount = ProductDiscount::findOrFail($id);
            $productDiscount->products()->detach(); // Detach all related products
            $productDiscount->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product discount deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product discount not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the product discount.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
