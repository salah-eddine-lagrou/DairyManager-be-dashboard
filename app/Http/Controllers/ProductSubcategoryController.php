<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSubcategory;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class ProductSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $subcategories = ProductSubcategory::all();
            return response()->json([
                'success' => true,
                'data' => $subcategories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching product subcategories.',
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
            // Validate the incoming request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'product_category_id' => 'nullable|exists:product_categories,id'
            ]);

            $validated['code'] = $this->generateUniqueCode();
            // Create a new product subcategory
            $subcategory = ProductSubcategory::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product subcategory created successfully.',
                'data' => $subcategory
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while creating the product subcategory.',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the product subcategory.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode()
    {
        $prefix = "PS";

        // Get the latest code from the database
        $latestCode = ProductSubcategory::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate the next number in the sequence
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $subcategory = ProductSubcategory::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $subcategory
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product subcategory not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the product subcategory.',
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
            $subcategory = ProductSubcategory::findOrFail($id);

            // Validate the incoming request data
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('product_subcategories')->ignore($subcategory->id),
                ],
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'product_category_id' => 'nullable|exists:product_categories,id'
            ]);

            // Find the product subcategory and update it
            $subcategory->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product subcategory updated successfully.',
                'data' => $subcategory
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
                'message' => 'Product subcategory not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the product subcategory.',
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
            // Find the product subcategory and delete it
            $subcategory = ProductSubcategory::findOrFail($id);
            $subcategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product subcategory deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product subcategory not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the product subcategory.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
