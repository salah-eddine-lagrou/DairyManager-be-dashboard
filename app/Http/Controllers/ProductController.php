<?php

namespace App\Http\Controllers;

use App\Models\BatchProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully.',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'created_by_id' => 'nullable|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'product_subcategory_id' => 'required|exists:product_subcategories,id',
                'unit_id' => 'required|exists:units,id',
                'weight' => 'required|numeric',
                'measure' => 'required|numeric',
                'price_ht' => 'required|numeric',
                'tax_id' => 'required|exists:tva,id',
                'price_ttc' => 'required|numeric',
                'status' => 'required|in:actif,inactif',
                'product_stock_status_id' => 'nullable|exists:product_stock_status,id',
                'image' => 'nullable|string',

                // Batch attributes:
                'measure_batch' => 'nullable|numeric',
                'measure_items' => 'nullable|numeric',
                'weight_batch' => 'nullable|numeric',
                'batch_product_price' => 'nullable|numeric',
                'batch_unit_id' => 'nullable|exists:units,id',
            ]);

            // Generate unique product code
            $validated['code'] = $this->generateUniqueCode();

            // Create the product
            $product = Product::create($validated);

            // Create the BatchProduct and associate it with the product
            $batchData = Arr::only($validated, [
                'measure_batch',
                'measure_items',
                'weight_batch',
                'batch_product_price',
                'batch_unit_id'
            ]);
            $batchData['product_id'] = $product->id;

            $batchProduct = BatchProduct::create($batchData);

            // Link the batch product to the product
            $product->batch_product_id = $batchProduct->id;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product and batch product created successfully.',
                'data' => $product
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
                'message' => 'An error occurred while creating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function generateUniqueCode()
    {
        $prefix = "PR";

        $latestCode = Product::where('code', 'like', $prefix . '%')
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

    public function syncPriceLists(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:products,id',
                'price_list_ids' => 'required|array',
                'price_list_ids.*' => 'exists:price_lists,id',
                'pivot_data' => 'required|array',
                'pivot_data.*.product_id' => 'required|exists:products,id',
                'pivot_data.*.price_list_id' => 'required|exists:price_lists,id',
                'pivot_data.*.code' => 'nullable|string',
                'pivot_data.*.sale_price' => 'nullable|numeric',
                'pivot_data.*.return_price' => 'nullable|numeric',
                'pivot_data.*.valid_from' => 'nullable|date',
                'pivot_data.*.valid_to' => 'nullable|date',
                'pivot_data.*.closed' => 'nullable|boolean',
            ]);

            // Iterate over each pivot data entry and sync
            foreach ($validated['pivot_data'] as $data) {
                $product = Product::findOrFail($data['product_id']);
                $product->priceLists()->sync([$data['price_list_id'] => $data]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Price Lists successfully synced with the products.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'One or more records not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while syncing the price lists with the products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully.',
                'data' => $product
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the product.',
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
            // Find the product and related batch product
            $product = Product::findOrFail($id);
            $batchProduct = BatchProduct::findOrFail($product->batch_product_id);

            // Validate the incoming request
            $validated = $request->validate([
                // Product fields
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('products')->ignore($product->id),
                ],
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'created_by_id' => 'sometimes|nullable|exists:users,id',
                'modified_by_id' => 'sometimes|nullable|exists:users,id',
                'product_subcategory_id' => 'sometimes|nullable|exists:product_subcategories,id',
                'unit_id' => 'sometimes|nullable|exists:units,id',
                'weight' => 'sometimes|nullable|numeric',
                'measure' => 'sometimes|nullable|numeric',
                'price_ht' => 'sometimes|nullable|numeric',
                'tax_id' => 'sometimes|nullable|exists:tva,id',
                'price_ttc' => 'sometimes|nullable|numeric',
                'status' => 'sometimes|nullable|in:actif,inactif',
                'product_stock_status_id' => 'sometimes|nullable|exists:product_stock_status,id',
                'image' => 'sometimes|nullable|string',

                // Batch product fields
                'measure_batch' => 'sometimes|nullable|numeric',
                'measure_items' => 'sometimes|nullable|numeric',
                'weight_batch' => 'sometimes|nullable|numeric',
                'batch_product_price' => 'sometimes|nullable|numeric',
                'batch_unit_id' => 'sometimes|nullable|exists:units,id',
            ]);

            // Update the product fields
            $productData = Arr::only($validated, [
                'code',
                'name',
                'description',
                'created_by_id',
                'modified_by_id',
                'product_subcategory_id',
                'unit_id',
                'weight',
                'measure',
                'price_ht',
                'tax_id',
                'price_ttc',
                'status',
                'product_stock_status_id',
                'image'
            ]);
            $product->update($productData);

            // Update the batch product fields
            $batchProductData = Arr::only($validated, [
                'measure_batch',
                'measure_items',
                'weight_batch',
                'batch_product_price',
                'batch_unit_id'
            ]);
            $batchProduct->update($batchProductData);

            return response()->json([
                'success' => true,
                'message' => 'Product and batch product updated successfully.',
                'data' => [
                    'product' => $product,
                    'batch_product' => $batchProduct
                ]
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
                'message' => 'Product or Batch Product not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the product and batch product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function setActive($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = 'actif';
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product status set to actif successfully.',
                'data' => $product
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating product status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setInactive($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = 'inactif';
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product status set to inactif successfully.',
                'data' => $product
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating product status.',
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
            // Find the product and its related batch product
            $product = Product::findOrFail($id);
            $batchProductId = $product->batch_product_id;

            // Delete the product
            $product->delete();

            // Delete the associated batch product if it exists
            if ($batchProductId) {
                $batchProduct = BatchProduct::find($batchProductId);
                if ($batchProduct) {
                    $batchProduct->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product and associated batch product deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product or Batch Product not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the product and batch product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
