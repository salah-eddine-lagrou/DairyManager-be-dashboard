<?php

namespace App\Http\Controllers;

use App\Models\DiscountSale;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscountSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $discounts = DiscountSale::all();
            return response()->json([
                'success' => true,
                'message' => 'Retrieved all discount sales successfully.',
                'data' => $discounts,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving discount sales.',
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
                'code' => 'required|string|unique:discount_sales,code',
                'discount' => 'required|numeric',
                'discount_type' => 'required|in:permanent-discounts,periodic-discounts',
            ]);

            $discount = DiscountSale::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Discount sale created successfully.',
                'data' => $discount,
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
                'message' => 'An error occurred while creating the discount sale.',
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
            $discount = DiscountSale::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Retrieved the discount sale successfully.',
                'data' => $discount,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Discount sale not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the discount sale.',
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
                'code' => 'sometimes|required|string|unique:discount_sales,code,' . $id,
                'discount' => 'sometimes|required|numeric',
                'discount_type' => 'sometimes|required|in:permanent-discounts,periodic-discounts',
            ]);

            $discount = DiscountSale::findOrFail($id);
            $discount->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Discount sale updated successfully.',
                'data' => $discount,
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
                'message' => 'Discount sale not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the discount sale.',
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
            $discount = DiscountSale::findOrFail($id);
            $discount->delete();

            return response()->json([
                'success' => true,
                'message' => 'Discount sale deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Discount sale not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the discount sale.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
