<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products
     * GET /api/products
     */
    public function index()
    {
        try {
            $products = Product::where('is_hidden', false)
                ->with('user:id,name')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details
     * GET /api/products/{id}
     */
    public function show($id)
    {
        try {
            $product = Product::with('user:id,name')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
} 