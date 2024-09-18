<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $priceLists = PriceList::all();

            return response()->json([
                'success' => true,
                'message' => 'Price lists retrieved successfully.',
                'data' => $priceLists
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching price lists.',
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
                'rank' => 'required|integer',
                'code' => 'required|string|unique:price_lists,code|max:255',
                'description' => 'required|string',
                'price_list_name' => ['required', Rule::in(['direct', 'demi-gros', 'grossite', 'mensuel'])],
            ]);

            $priceList = PriceList::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Price list created successfully.',
                'data' => $priceList
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
                'message' => 'An error occurred while creating the price list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'price_list_ids' => 'required|array',
                'price_list_ids.*' => 'exists:price_lists,id',
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:products,id',
                'pivot_data' => 'required|array',
                'pivot_data.*.price_list_id' => 'required|exists:price_lists,id',
                'pivot_data.*.product_id' => 'required|exists:products,id',
                'pivot_data.*.code' => 'nullable|string',
                'pivot_data.*.sale_price' => 'nullable|numeric',
                'pivot_data.*.return_price' => 'nullable|numeric',
                'pivot_data.*.valid_from' => 'nullable|date',
                'pivot_data.*.valid_to' => 'nullable|date',
                'pivot_data.*.closed' => 'nullable|boolean',
            ]);

            // Iterate over each pivot data entry and sync
            foreach ($validated['pivot_data'] as $data) {
                $priceList = PriceList::findOrFail($data['price_list_id']);
                $priceList->products()->sync([$data['product_id'] => $data]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Products successfully synced with the price lists.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'One or more records not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while syncing the products with the price lists.',
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
            $priceList = PriceList::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Price list retrieved successfully.',
                'data' => $priceList
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Price list not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the price list.',
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
            $priceList = PriceList::findOrFail($id);

            $validated = $request->validate([
                'rank' => 'sometimes|required|integer',
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('price_lists')->ignore($priceList->id),
                ],
                'description' => 'sometimes|required|string',
                'price_list_name' => ['sometimes', 'required', Rule::in(['direct', 'demi-gros', 'grossite', 'mensuel'])],
            ]);

            $priceList->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Price list updated successfully.',
                'data' => $priceList
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
                'message' => 'Price list not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the price list.',
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
            $priceList = PriceList::findOrFail($id);
            $priceList->delete();

            return response()->json([
                'success' => true,
                'message' => 'Price list deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Price list not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the price list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
